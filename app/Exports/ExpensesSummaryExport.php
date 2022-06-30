<?php
namespace App\Exports;

use App\Invoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\OfficeCategory;
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

class ExpensesSummaryExport implements FromView, WithEvents
{
    use Exportable, RegistersEventListeners;

    private $bordered_cells, $exportdata;
    private $bordered_left_right = array();
    public function __construct( $filters )
    {
        $this->filters = $filters;
        $this->bordered_cells = [
            'A1:E1'
        ];

        $row_counter = 0;

        $office_groups = OfficeCategory::where('parent_id', 0)->get();

        $overall_total_allotment_total = 0;
        $overall_total_expenses_total = 0;
        $overall_total_balance_total = 0;
        $overall_total_appropriation = 0;

        $exportdata = [];

        $exportdata[] = [
            'PROGRAM',
            'APPROPRIATION',
            'ALLOTMENT',
            'OBLIGATION <br> INCURRED',
            'UNOBLIGATED <br> BALANCE',
        ];

        foreach($office_groups as $office_group) {

            $exportdata[] = [
                $office_group->name,
                '',
                '',
                '',
                '',
            ];
            $row_counter++;

            foreach($office_group->children as $office) {

                $total_allotment_total = 0;
                $total_expenses_total = 0;
                $total_balance_total = 0;
                $total_appropriation = 0;

                $exportdata[] = [
                    $office->name,
                    '',
                    '',
                    '',
                    '',
                ];
                $row_counter++;

                foreach($office->getUniqueDescriptions() as $officebydescription) {

                    $d_total_allotment_total = 0;
                    $d_total_expenses_total = 0;
                    $d_total_balance_total = 0;
                    $d_total_appropriation = 0;

                    $exportdata[] = [
                        $officebydescription->description,
                        '',
                        '',
                        '',
                        '',
                    ];
                    $row_counter++;

                    foreach($officebydescription->getExpenseClassesByDescription($office->id) as $expense_class) {

                        $allotment_total = $expense_class->getAllotmentTotal(@$filters['date_from'], @$filters['date_to']);
                        $expenses_total = $expense_class->getExpensesTotal(@$filters['date_from'], @$filters['date_to']);
                        $appropriation = $expense_class->getAppropriation(@$filters['year']);
                        $balance = $allotment_total - $expenses_total;

                        $total_allotment_total+=$allotment_total;
                        $total_expenses_total+=$expenses_total;
                        $total_balance_total+=$balance;
                        $total_appropriation+=$appropriation;

                        $d_total_allotment_total+=$allotment_total;
                        $d_total_expenses_total+=$expenses_total;
                        $d_total_balance_total+=$balance;
                        $d_total_appropriation+=$appropriation;

                        $exportdata[] = [
                            $expense_class->name,
                            format_amount($appropriation),
                            format_amount($allotment_total),
                            format_amount($expenses_total),
                            format_amount($balance),
                        ];
                        $row_counter++;

                    }

                    $exportdata[] = [
                        '<b>Sub-total</b>',
                        format_amount($d_total_appropriation),
                        format_amount($d_total_allotment_total),
                        format_amount($d_total_expenses_total),
                        format_amount($d_total_balance_total),
                    ];
                    $row_counter++;

                }


                $exportdata[] = [
                    '<b>Sub-total</b>',
                    format_amount($total_appropriation),
                    format_amount($total_allotment_total),
                    format_amount($total_expenses_total),
                    format_amount($total_balance_total),
                ];
                $row_counter++;

                $this->bordered_cells[] = 'A'.($row_counter+1).':E'.($row_counter+1);

                $overall_total_allotment_total+=$total_allotment_total;
                $overall_total_appropriation+=$total_appropriation;
                $overall_total_balance_total+=$total_balance_total;
                $overall_total_expenses_total+=$total_expenses_total;


            }



        }

        $exportdata[] = [
            '<b>Grand total</b>',
            format_amount($overall_total_appropriation),
            format_amount($overall_total_allotment_total),
            format_amount($overall_total_expenses_total),
            format_amount($overall_total_balance_total),
        ];
        $row_counter++;
        $this->bordered_cells[] = 'A'.($row_counter+1).':E'.($row_counter+1);

        $this->exportdata = $exportdata;

        for ($i=1; $i < $row_counter+1; $i++) { 
            foreach(range('A', 'E') as $letter) {
                $this->bordered_left_right[] = $letter.($i);
            }
        }

    }

    public function view(): View
    {
        // echo $this->bordered_left_right;
        // exit;
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