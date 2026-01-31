<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allotment;
use App\Models\Office;
use App\Models\OfficeCategory;
use App\Models\Transaction;
use Excel;
use App\Exports\ExpensesSummaryExport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the offices list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        
        // If not generating report, just return empty view
        if (!isset($filters['generate_report'])) {
            $office_groups = OfficeCategory::where('parent_id', 0)->get();
            return view('dashboard.reports.index', ['office_groups' => $office_groups, 'filters' => $filters]);
        }
        
        // Pre-calculate all data in bulk for better performance
        $reportData = $this->generateReportData($filters);
        
        return view('dashboard.reports.index', [
            'office_groups' => $reportData['office_groups'],
            'filters' => $filters,
            'report_data' => $reportData['data'],
            'use_optimized' => true
        ]);
    }

    /**
     * Generate report data with optimized queries
     */
    private function generateReportData($filters)
    {
        $year = $filters['year'] ?? date('Y');
        $dateFrom = $filters['date_from'] ?? null;
        $dateTo = $filters['date_to'] ?? null;
        
        // Get all office groups with children eagerly loaded
        $office_groups = OfficeCategory::where('parent_id', 0)
            ->with(['children.expense_classes'])
            ->get();
        
        // Get all office IDs
        $allOfficeIds = Office::pluck('id')->toArray();
        
        // Bulk query for appropriations (month = 0)
        $appropriations = Allotment::where('year', $year)
            ->where('month', 0)
            ->whereIn('office_id', $allOfficeIds)
            ->groupBy('office_id')
            ->selectRaw('office_id, SUM(amount) as total')
            ->pluck('total', 'office_id')
            ->toArray();
        
        // Bulk query for allotments (with date range, month != 0)
        $allotmentsQuery = Transaction::where('type', 'allotment')
            ->whereIn('recepient', $allOfficeIds)
            ->whereHas('reference', function($q) {
                $q->where('month', '<>', 0);
            });
        
        if ($dateFrom && $dateTo) {
            $allotmentsQuery->where('transaction_date', '>=', $dateFrom)
                ->where('transaction_date', '<=', $dateTo);
        }
        
        $allotments = $allotmentsQuery
            ->groupBy('recepient')
            ->selectRaw('recepient, SUM(amount) as total')
            ->pluck('total', 'recepient')
            ->toArray();
        
        // Bulk query for expenses (with date range)
        $expensesQuery = Transaction::where('type', 'expense')
            ->whereIn('recepient', $allOfficeIds);
        
        if ($dateFrom && $dateTo) {
            $expensesQuery->where('transaction_date', '>=', $dateFrom)
                ->where('transaction_date', '<=', $dateTo);
        }
        
        $expenses = $expensesQuery
            ->groupBy('recepient')
            ->selectRaw('recepient, SUM(amount) as total')
            ->pluck('total', 'recepient')
            ->toArray();
        
        // Get unique descriptions
        $descriptions = Office::select('description')
            ->groupBy('description')
            ->orderByRaw("FIELD(description, 'MOOE', 'Personal Services') DESC")
            ->pluck('description')
            ->toArray();
        
        // Build the report data structure
        $reportData = [];
        
        foreach ($office_groups as $officeGroup) {
            $groupData = [
                'name' => $officeGroup->name,
                'children' => [],
                'totals' => [
                    'appropriation' => 0,
                    'allotment' => 0,
                    'expenses' => 0,
                    'balance' => 0
                ]
            ];
            
            foreach ($officeGroup->children as $office) {
                $officeData = [
                    'name' => $office->name,
                    'descriptions' => [],
                    'totals' => [
                        'appropriation' => 0,
                        'allotment' => 0,
                        'expenses' => 0,
                        'balance' => 0
                    ]
                ];
                
                foreach ($descriptions as $description) {
                    $descriptionData = [
                        'name' => $description,
                        'expense_classes' => [],
                        'totals' => [
                            'appropriation' => 0,
                            'allotment' => 0,
                            'expenses' => 0,
                            'balance' => 0
                        ]
                    ];
                    
                    // Get expense classes for this description and category
                    $expenseClasses = Office::where('description', $description)
                        ->where('office_category_id', $office->id)
                        ->get();
                    
                    foreach ($expenseClasses as $expenseClass) {
                        $appropriation = $appropriations[$expenseClass->id] ?? 0;
                        
                        // Skip if appropriation is 0
                        if ($appropriation <= 0) {
                            continue;
                        }
                        
                        $allotment = $allotments[$expenseClass->id] ?? 0;
                        $expense = $expenses[$expenseClass->id] ?? 0;
                        $balance = $allotment - $expense;
                        
                        $descriptionData['expense_classes'][] = [
                            'name' => $expenseClass->name,
                            'appropriation' => $appropriation,
                            'allotment' => $allotment,
                            'expenses' => $expense,
                            'balance' => $balance
                        ];
                        
                        // Add to description totals
                        $descriptionData['totals']['appropriation'] += $appropriation;
                        $descriptionData['totals']['allotment'] += $allotment;
                        $descriptionData['totals']['expenses'] += $expense;
                        $descriptionData['totals']['balance'] += $balance;
                    }
                    
                    // Only add description if it has expense classes with appropriation > 0
                    if (!empty($descriptionData['expense_classes'])) {
                        $officeData['descriptions'][] = $descriptionData;
                        
                        // Add to office totals
                        $officeData['totals']['appropriation'] += $descriptionData['totals']['appropriation'];
                        $officeData['totals']['allotment'] += $descriptionData['totals']['allotment'];
                        $officeData['totals']['expenses'] += $descriptionData['totals']['expenses'];
                        $officeData['totals']['balance'] += $descriptionData['totals']['balance'];
                    }
                }
                
                // Only add office if it has data
                if (!empty($officeData['descriptions'])) {
                    $groupData['children'][] = $officeData;
                    
                    // Add to group totals
                    $groupData['totals']['appropriation'] += $officeData['totals']['appropriation'];
                    $groupData['totals']['allotment'] += $officeData['totals']['allotment'];
                    $groupData['totals']['expenses'] += $officeData['totals']['expenses'];
                    $groupData['totals']['balance'] += $officeData['totals']['balance'];
                }
            }
            
            // Only add group if it has data
            if (!empty($groupData['children'])) {
                $reportData[] = $groupData;
            }
        }
        
        return [
            'office_groups' => $office_groups,
            'data' => $reportData
        ];
    }

    public function export(Request $request) 
    {
        return Excel::download(new ExpensesSummaryExport($request->all()), 'reports.xlsx');
    }
    
    
}
