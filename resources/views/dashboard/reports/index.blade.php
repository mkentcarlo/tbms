@extends('layouts.modern')

@section('title', 'Reports')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li class="text-gray-500">Reports</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reports</h1>
            <p class="mt-1 text-sm text-gray-500">Generate and export financial reports</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Filters Card -->
        <div class="card">
            <div class="card-body">
                <form action="" method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        <!-- Year -->
                        <div>
                            <label for="year" class="form-label">Year</label>
                            <select name="year" id="year" class="form-input">
                                <option value="">Select Year</option>
                                @for($i = 2021; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ (@$filters['year'] ?? date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <!-- Quarter -->
                        <div>
                            <label for="quarter" class="form-label">Quarter</label>
                            <select name="quarter" id="quarter" class="form-input">
                                <option value="">Entire year</option>
                                <option value="1" {{ @$filters['quarter'] == 1 ? 'selected' : '' }}>1st Quarter</option>
                                <option value="2" {{ @$filters['quarter'] == 2 ? 'selected' : '' }}>2nd Quarter</option>
                                <option value="3" {{ @$filters['quarter'] == 3 ? 'selected' : '' }}>3rd Quarter</option>
                                <option value="4" {{ @$filters['quarter'] == 4 ? 'selected' : '' }}>4th Quarter</option>
                            </select>
                        </div>

                        <!-- Month -->
                        <div>
                            <label for="month" class="form-label">Month</label>
                            <select name="month" id="month" class="form-input">
                                <option value="">Entire quarter</option>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ @$filters['month'] == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label for="date_from" class="form-label">From</label>
                            <input type="date" id="date_from" name="date_from" class="form-input" value="{{ @$filters['date_from'] }}" />
                        </div>

                        <!-- Date To -->
                        <div>
                            <label for="date_to" class="form-label">To</label>
                            <input type="date" id="date_to" name="date_to" class="form-input" value="{{ @$filters['date_to'] }}" />
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="submit" name="generate_report" value="yes" class="btn-primary inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Generate Report
                        </button>
                        @if(isset($filters['generate_report']))
                            <a href="{{ route('reports.export').'?'.http_build_query($filters) }}" class="btn-secondary inline-flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Export Excel
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Report Results -->
        @if(isset($filters['generate_report']) && isset($report_data))
            @php
                $overall_totals = [
                    'appropriation' => 0,
                    'allotment' => 0,
                    'expenses' => 0,
                    'balance' => 0
                ];
                foreach($report_data as $og) {
                    if($og['totals']['appropriation'] > 0) {
                        $overall_totals['appropriation'] += $og['totals']['appropriation'];
                        $overall_totals['allotment'] += $og['totals']['allotment'];
                        $overall_totals['expenses'] += $og['totals']['expenses'];
                        $overall_totals['balance'] += $og['totals']['balance'];
                    }
                }
            @endphp

            <div x-data="{ view: localStorage.getItem('reportView') || 'table' }" x-init="$watch('view', val => localStorage.setItem('reportView', val))" class="space-y-6">
                <!-- View Toggle & Actions -->
                <div class="flex items-center justify-between">
                    <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1">
                        <button @click="view = 'table'" :class="view === 'table' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700'" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-md transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                            </svg>
                            Table View
                        </button>
                        <button @click="view = 'accordion'" :class="view === 'accordion' ? 'bg-primary-100 text-primary-700' : 'text-gray-500 hover:text-gray-700'" class="inline-flex items-center gap-2 px-3 py-1.5 text-sm font-medium rounded-md transition-colors">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Accordion View
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <button x-show="view === 'accordion'" @click="document.querySelectorAll('[data-accordion]').forEach(el => el.open = true)" class="btn-outline btn-sm">
                            Expand All
                        </button>
                        <button x-show="view === 'accordion'" @click="document.querySelectorAll('[data-accordion]').forEach(el => el.open = false)" class="btn-outline btn-sm">
                            Collapse All
                        </button>
                    </div>
                </div>

                <!-- Grand Total Summary Card -->
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-4 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Grand Total</p>
                            <p class="text-2xl font-bold">{{ format_amount($overall_totals['appropriation']) }}</p>
                        </div>
                        <div class="grid grid-cols-3 gap-6 text-right">
                            <div>
                                <p class="text-green-100 text-xs">Allotment</p>
                                <p class="font-semibold">{{ format_amount($overall_totals['allotment']) }}</p>
                            </div>
                            <div>
                                <p class="text-green-100 text-xs">Obligation</p>
                                <p class="font-semibold">{{ format_amount($overall_totals['expenses']) }}</p>
                            </div>
                            <div>
                                <p class="text-green-100 text-xs">Balance</p>
                                <p class="font-semibold">{{ format_amount($overall_totals['balance']) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TABLE VIEW -->
                <div x-show="view === 'table'" x-transition class="card">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 report-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Appropriation</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Allotment</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Obligation<br>Incurred</th>
                                    <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unobligated<br>Balance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach($report_data as $officeGroup)
                                    @if($officeGroup['totals']['appropriation'] > 0)
                                        <tr class="bg-gray-100">
                                            <td colspan="5" class="px-4 py-2 text-sm font-bold text-gray-900">
                                                {{ $officeGroup['name'] }}
                                            </td>
                                        </tr>

                                        @foreach($officeGroup['children'] as $office)
                                            @if($office['totals']['appropriation'] > 0)
                                                <tr class="bg-gray-50">
                                                    <td colspan="5" class="px-4 py-2 pl-6 text-sm font-semibold text-gray-800">
                                                        {{ $office['name'] }}
                                                    </td>
                                                </tr>

                                                @foreach($office['descriptions'] as $description)
                                                    @if($description['totals']['appropriation'] > 0)
                                                        <tr>
                                                            <td colspan="5" class="px-4 py-1.5 pl-10 text-sm font-medium text-gray-700">
                                                                {{ $description['name'] }}
                                                            </td>
                                                        </tr>

                                                        @foreach($description['expense_classes'] as $expenseClass)
                                                            <tr class="hover:bg-gray-50">
                                                                <td class="px-4 py-1.5 pl-14 text-sm text-gray-600">{{ $expenseClass['name'] }}</td>
                                                                <td class="px-4 py-1.5 text-sm text-gray-900 text-right">{{ format_amount($expenseClass['appropriation']) }}</td>
                                                                <td class="px-4 py-1.5 text-sm text-gray-900 text-right">{{ format_amount($expenseClass['allotment']) }}</td>
                                                                <td class="px-4 py-1.5 text-sm text-gray-900 text-right">{{ format_amount($expenseClass['expenses']) }}</td>
                                                                <td class="px-4 py-1.5 text-sm text-gray-900 text-right">{{ format_amount($expenseClass['balance']) }}</td>
                                                            </tr>
                                                        @endforeach

                                                        <tr class="border-t border-gray-200">
                                                            <td class="px-4 py-1.5 pl-10 text-sm font-medium text-gray-700">Sub-total</td>
                                                            <td class="px-4 py-1.5 text-sm font-medium text-gray-900 text-right">{{ format_amount($description['totals']['appropriation']) }}</td>
                                                            <td class="px-4 py-1.5 text-sm font-medium text-gray-900 text-right">{{ format_amount($description['totals']['allotment']) }}</td>
                                                            <td class="px-4 py-1.5 text-sm font-medium text-gray-900 text-right">{{ format_amount($description['totals']['expenses']) }}</td>
                                                            <td class="px-4 py-1.5 text-sm font-medium text-gray-900 text-right">{{ format_amount($description['totals']['balance']) }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach

                                                <tr class="border-t-2 border-b border-gray-300 bg-gray-50">
                                                    <td class="px-4 py-2 pl-6 text-sm font-semibold text-gray-800">Sub-total</td>
                                                    <td class="px-4 py-2 text-sm font-semibold text-gray-900 text-right">{{ format_amount($office['totals']['appropriation']) }}</td>
                                                    <td class="px-4 py-2 text-sm font-semibold text-gray-900 text-right">{{ format_amount($office['totals']['allotment']) }}</td>
                                                    <td class="px-4 py-2 text-sm font-semibold text-gray-900 text-right">{{ format_amount($office['totals']['expenses']) }}</td>
                                                    <td class="px-4 py-2 text-sm font-semibold text-gray-900 text-right">{{ format_amount($office['totals']['balance']) }}</td>
                                                </tr>
                                            @endif
                                        @endforeach

                                        <tr class="bg-blue-50 border-t-2 border-blue-200">
                                            <td class="px-4 py-2 text-sm font-bold text-blue-900">{{ $officeGroup['name'] }} Total</td>
                                            <td class="px-4 py-2 text-sm font-bold text-blue-900 text-right">{{ format_amount($officeGroup['totals']['appropriation']) }}</td>
                                            <td class="px-4 py-2 text-sm font-bold text-blue-900 text-right">{{ format_amount($officeGroup['totals']['allotment']) }}</td>
                                            <td class="px-4 py-2 text-sm font-bold text-blue-900 text-right">{{ format_amount($officeGroup['totals']['expenses']) }}</td>
                                            <td class="px-4 py-2 text-sm font-bold text-blue-900 text-right">{{ format_amount($officeGroup['totals']['balance']) }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- ACCORDION VIEW -->
                <div x-show="view === 'accordion'" x-transition class="space-y-3">
                    @foreach($report_data as $groupIndex => $officeGroup)
                        @if($officeGroup['totals']['appropriation'] > 0)
                            <!-- Office Group Accordion -->
                            <details data-accordion class="bg-white rounded-xl border border-gray-200 overflow-hidden group" open>
                                <summary class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white cursor-pointer select-none hover:from-blue-700 hover:to-blue-800 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <svg class="h-5 w-5 transform transition-transform group-open:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span class="font-semibold">{{ $officeGroup['name'] }}</span>
                                    </div>
                                    <div class="flex items-center gap-6 text-sm">
                                        <div class="text-right">
                                            <span class="text-blue-200 text-xs">Appropriation</span>
                                            <p class="font-bold">{{ format_amount($officeGroup['totals']['appropriation']) }}</p>
                                        </div>
                                        <div class="text-right hidden sm:block">
                                            <span class="text-blue-200 text-xs">Balance</span>
                                            <p class="font-bold">{{ format_amount($officeGroup['totals']['balance']) }}</p>
                                        </div>
                                    </div>
                                </summary>
                                <div class="p-3 space-y-2">
                                    @foreach($officeGroup['children'] as $officeIndex => $office)
                                        @if($office['totals']['appropriation'] > 0)
                                            <!-- Office Accordion -->
                                            <details data-accordion class="bg-gray-50 rounded-lg border border-gray-200 overflow-hidden group/office">
                                                <summary class="flex items-center justify-between px-4 py-2.5 cursor-pointer select-none hover:bg-gray-100 transition-colors">
                                                    <div class="flex items-center gap-2">
                                                        <svg class="h-4 w-4 text-gray-500 transform transition-transform group-open/office:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                        <span class="font-medium text-gray-800">{{ $office['name'] }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-4 text-sm">
                                                        <span class="text-gray-600">{{ format_amount($office['totals']['appropriation']) }}</span>
                                                    </div>
                                                </summary>
                                                <div class="px-4 pb-3 space-y-2">
                                                    @foreach($office['descriptions'] as $description)
                                                        @if($description['totals']['appropriation'] > 0)
                                                            <!-- Description Accordion -->
                                                            <details data-accordion class="bg-white rounded-lg border border-gray-200 overflow-hidden group/desc">
                                                                <summary class="flex items-center justify-between px-3 py-2 cursor-pointer select-none hover:bg-gray-50 transition-colors">
                                                                    <div class="flex items-center gap-2">
                                                                        <svg class="h-3.5 w-3.5 text-gray-400 transform transition-transform group-open/desc:rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                                        </svg>
                                                                        <span class="text-sm font-medium text-gray-700">{{ $description['name'] }}</span>
                                                                    </div>
                                                                    <span class="text-sm text-gray-500">{{ format_amount($description['totals']['appropriation']) }}</span>
                                                                </summary>
                                                                <div class="px-3 pb-2">
                                                                    <div class="space-y-1">
                                                                        @foreach($description['expense_classes'] as $expenseClass)
                                                                            <div class="flex items-center justify-between py-1.5 px-3 bg-gray-50 rounded-lg text-sm">
                                                                                <span class="text-gray-600">{{ $expenseClass['name'] }}</span>
                                                                                <div class="flex items-center gap-4 text-xs">
                                                                                    <div class="text-right">
                                                                                        <span class="text-gray-400">Approp.</span>
                                                                                        <p class="font-medium text-gray-900">{{ format_amount($expenseClass['appropriation']) }}</p>
                                                                                    </div>
                                                                                    <div class="text-right">
                                                                                        <span class="text-gray-400">Allot.</span>
                                                                                        <p class="font-medium text-gray-900">{{ format_amount($expenseClass['allotment']) }}</p>
                                                                                    </div>
                                                                                    <div class="text-right">
                                                                                        <span class="text-gray-400">Oblig.</span>
                                                                                        <p class="font-medium text-gray-900">{{ format_amount($expenseClass['expenses']) }}</p>
                                                                                    </div>
                                                                                    <div class="text-right">
                                                                                        <span class="text-gray-400">Bal.</span>
                                                                                        <p class="font-medium {{ $expenseClass['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ format_amount($expenseClass['balance']) }}</p>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                    <!-- Description Total -->
                                                                    <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-200 text-sm font-medium">
                                                                        <span class="text-gray-700">Sub-total</span>
                                                                        <div class="flex items-center gap-4 text-xs">
                                                                            <span>{{ format_amount($description['totals']['appropriation']) }}</span>
                                                                            <span>{{ format_amount($description['totals']['allotment']) }}</span>
                                                                            <span>{{ format_amount($description['totals']['expenses']) }}</span>
                                                                            <span class="{{ $description['totals']['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ format_amount($description['totals']['balance']) }}</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </details>
                                                        @endif
                                                    @endforeach
                                                    <!-- Office Total -->
                                                    <div class="flex items-center justify-between mt-2 pt-2 px-3 border-t border-gray-300 text-sm font-semibold">
                                                        <span class="text-gray-800">Office Total</span>
                                                        <div class="flex items-center gap-6">
                                                            <div class="text-right">
                                                                <span class="text-xs text-gray-500">Appropriation</span>
                                                                <p>{{ format_amount($office['totals']['appropriation']) }}</p>
                                                            </div>
                                                            <div class="text-right">
                                                                <span class="text-xs text-gray-500">Balance</span>
                                                                <p class="{{ $office['totals']['balance'] >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ format_amount($office['totals']['balance']) }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </details>
                                        @endif
                                    @endforeach
                                </div>
                            </details>
                        @endif
                    @endforeach
                </div>
            </div>
        @elseif(!isset($filters['generate_report']))
            <div class="card">
                <div class="text-center py-12 px-4">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No report generated</h3>
                    <p class="mt-1 text-sm text-gray-500">Select filters above and click "Generate Report" to view data.</p>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            function format_date(d){
                var day = ("0" + d.getDate()).slice(-2);
                var month = ("0" + (d.getMonth() + 1)).slice(-2);
                var today = d.getFullYear()+"-"+(month)+"-"+(day);
                return today;
            }

            $("select#year").change(function(){
                var $this_val = $(this).val();
                var d_from = new Date($this_val, 0, 1);
                var d_to = new Date($this_val, 12, 0);

                $('input[name=date_from]').val(format_date(d_from));
                $('input[name=date_to]').val(format_date(d_to));
            });

            $("select#quarter").change(function(){
                var year = $("select#year").val();
                var $this_val = $(this).val();
                var end = parseInt($this_val) * 3;
                var start = end - 2;

                var d_from = new Date(year, (start - 1), 1);
                var d_to = new Date(year, end, 0);

                $('input[name=date_from]').val(format_date(d_from));
                $('input[name=date_to]').val(format_date(d_to));
            });

            $("select#month").change(function(){
                var year = $("select#year").val();
                var month = $(this).val();

                var d_from = new Date(year, (month - 1), 1);
                var d_to = new Date(year, month, 0);

                $('input[name=date_from]').val(format_date(d_from));
                $('input[name=date_to]').val(format_date(d_to));
            });
        });
    </script>
@endsection
