<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\OfficeCategory;
use App\Models\Office;
use App\Models\Allotment;
use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\BeforeWriting;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\Exportable;
use Carbon\Carbon;

class ExpensesSummaryExport implements FromView, WithEvents
{
    use Exportable, RegistersEventListeners;

    private $bordered_cells, $exportdata;
    private $bordered_left_right = array();
    private $filters;
    
    public function __construct( $filters )
    {
        $this->filters = $filters;
        $this->bordered_cells = [
            'A2:E2'
        ];

        $row_counter = 0;

        // Pre-fetch all data in bulk for better performance
        $reportData = $this->generateReportData($filters);

        $overall_total_allotment_total = 0;
        $overall_total_expenses_total = 0;
        $overall_total_balance_total = 0;
        $overall_total_appropriation = 0;

        $exportdata = [];

        $exportdata[] = [
            '<center><b>MUNICIPALITY OF TALACOGON <br> PROVINCE OF AGUSAN DEL SUR <br> CURRENT LEGISLATIVE APPROPRIATION <br>STATUS OF APPRPRIATIONS, ALLOTMENT AND OBLIGATIONS <br> '.Carbon::createFromFormat('Y-m-d', $filters['date_from'])->format('d M Y').' to '.Carbon::createFromFormat('Y-m-d', $filters['date_to'])->format('d M Y').'</b></center>'
        ];

        $exportdata[] = [
            '<b>PROGRAM</b>',
            '<b>APPROPRIATION</b>',
            '<b>ALLOTMENT</b>',
            '<b>OBLIGATION <br> INCURRED</b>',
            '<b>UNOBLIGATED <br> BALANCE</b>',
        ];

        foreach($reportData as $officeGroup) {
            // Skip if no appropriation
            if ($officeGroup['totals']['appropriation'] <= 0) {
                continue;
            }

            $exportdata[] = [
                '<b>'.$officeGroup['name'].'</b>',
                '',
                '',
                '',
                '',
            ];
            $row_counter++;

            foreach($officeGroup['children'] as $office) {
                // Skip if no appropriation
                if ($office['totals']['appropriation'] <= 0) {
                    continue;
                }

                $exportdata[] = [
                    '<b>'.$office['name'].'</b>',
                    '',
                    '',
                    '',
                    '',
                ];
                $row_counter++;

                foreach($office['descriptions'] as $description) {
                    // Skip if no appropriation
                    if ($description['totals']['appropriation'] <= 0) {
                        continue;
                    }

                    $exportdata[] = [
                        '<b>'.$description['name'].'</b>',
                        '',
                        '',
                        '',
                        '',
                    ];
                    $row_counter++;

                    foreach($description['expense_classes'] as $expenseClass) {
                        $exportdata[] = [
                            $expenseClass['name'],
                            format_amount($expenseClass['appropriation']),
                            format_amount($expenseClass['allotment']),
                            format_amount($expenseClass['expenses']),
                            format_amount($expenseClass['balance']),
                        ];
                        $row_counter++;
                    }

                    $exportdata[] = [
                        '<b>'.$description['name'].' Sub-total</b>',
                        '<b>'.format_amount($description['totals']['appropriation']).'</b>',
                        '<b>'.format_amount($description['totals']['allotment']).'</b>',
                        '<b>'.format_amount($description['totals']['expenses']).'</b>',
                        '<b>'.format_amount($description['totals']['balance']).'</b>',
                    ];
                    $row_counter++;
                }

                $exportdata[] = [
                    '<b>'.$office['name'].' Sub-total</b>',
                    '<b>'.format_amount($office['totals']['appropriation']).'</b>',
                    '<b>'.format_amount($office['totals']['allotment']).'</b>',
                    '<b>'.format_amount($office['totals']['expenses']).'</b>',
                    '<b>'.format_amount($office['totals']['balance']).'</b>',
                ];
                $row_counter++;

                $this->bordered_cells[] = 'A'.($row_counter+1).':E'.($row_counter+1);
            }

            $exportdata[] = [
                '<b>'.$officeGroup['name'].' Total</b>',
                '<b>'.format_amount($officeGroup['totals']['appropriation']).'</b>',
                '<b>'.format_amount($officeGroup['totals']['allotment']).'</b>',
                '<b>'.format_amount($officeGroup['totals']['expenses']).'</b>',
                '<b>'.format_amount($officeGroup['totals']['balance']).'</b>',
            ];
            $row_counter++;

            $overall_total_appropriation += $officeGroup['totals']['appropriation'];
            $overall_total_allotment_total += $officeGroup['totals']['allotment'];
            $overall_total_expenses_total += $officeGroup['totals']['expenses'];
            $overall_total_balance_total += $officeGroup['totals']['balance'];
        }

        $exportdata[] = [
            '<b>Grand total</b>',
            '<b>'.format_amount($overall_total_appropriation).'</b>',
            '<b>'.format_amount($overall_total_allotment_total).'</b>',
            '<b>'.format_amount($overall_total_expenses_total).'</b>',
            '<b>'.format_amount($overall_total_balance_total).'</b>',
        ];
        $row_counter++;

        $this->bordered_cells[] = 'A'.($row_counter+1).':E'.($row_counter+1);

        $this->exportdata = $exportdata;

        for ($i=2; $i <= $row_counter+1; $i++) { 
            foreach(range('A', 'E') as $letter) {
                $this->bordered_left_right[] = $letter.($i);
            }
        }

        $this->bordered_cells[] = 'A'.($row_counter+2).':E'.($row_counter+2);

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
        
        return $reportData;
    }

    public function view(): View
    {
        return view('dashboard.reports.export', [
            'filters' => $this->filters,
            'exportdata' => $this->exportdata
        ]);
    }

    public function registerEvents(): array
    {
        $border_cells = $this->bordered_cells;
        return [
            AfterSheet::class    => function(AfterSheet $event) use($border_cells) {

                $event->sheet->columnWidth('A', 30);
                $event->sheet->columnWidth('B', 15);
                $event->sheet->columnWidth('C', 15);
                $event->sheet->columnWidth('D', 15);
                $event->sheet->columnWidth('E', 15);
                $event->sheet->rowHeight('1', 100);
                $event->sheet->verticalAlign('A1', 'top');
                $event->sheet->horizontalAlign('A1', 'center');
                $event->sheet->horizontalAlign('A2:E2', 'center');
                foreach($this->bordered_cells as $cell) {
                     $event->sheet->getStyle($cell)->applyFromArray([
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);
                }
                
               

                foreach($this->bordered_left_right as $cell) {
                    $event->sheet->getStyle($cell)->applyFromArray([
                        'borders' => [
                            'right' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                            'left' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ],
                    ]);
                }
            
            },
        ];
    }
}
