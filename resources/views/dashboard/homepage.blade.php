@extends('layouts.modern')

@section('title', 'Dashboard')

@section('breadcrumbs')
    <li class="text-gray-500">Dashboard</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500">Overview of your budget management system</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Filters and Quick Add Card -->
        <div class="card">
            <div class="px-4 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-3 flex-wrap">
                        <button 
                            data-modal-open="selectOfficeModal"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="hidden sm:inline text-gray-500">Office:</span>
                            <span class="font-medium text-gray-900">{{ $selected_office->getDescription() }}</span>
                        </button>
                        <button 
                            data-modal-open="selectDateModal"
                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                            <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="hidden sm:inline text-gray-500">Date:</span>
                            <span class="font-medium text-gray-900">{{ date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year }}</span>
                        </button>
                    </div>
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open" 
                            type="button"
                            class="btn-primary inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Quick Add
                            <svg 
                                class="h-4 w-4 transition-transform duration-200" 
                                :class="{ 'rotate-180': open }"
                                fill="none" 
                                viewBox="0 0 24 24" 
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div 
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl bg-white ring-1 ring-black ring-opacity-5 z-50"
                            style="display: none;">
                            <div class="py-1">
                                <button 
                                    type="button"
                                    onclick="if (window.openModal) { window.openModal('addExpenseModal'); } else { console.error('openModal not available'); }"
                                    @click="open = false"
                                    class="w-full text-left block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Expense
                                </button>
                                <a 
                                    href="{{ url('allotments/create') }}" 
                                    @click="open = false"
                                    class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-primary-50 hover:text-primary-700 transition-colors flex items-center gap-2">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    Allotment
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Transactions -->
            <div class="px-4 py-3 border-b border-gray-100 bg-gray-50">
                <h4 class="text-sm font-semibold text-gray-700">Last 20 Transactions</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Code</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expense Class</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ending Balance</th>
                            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($transactions as $transaction)
                            @if(!$transaction->reference)
                                @continue
                            @endif
                            @if($transaction->type == 'expense')
                                <tr class="hover:bg-red-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 font-medium">{{ $transaction->reference->account_code }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600">{{ $transaction->reference->office->getDescription() }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 font-medium text-right">{{ number_format($transaction->amount, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600 font-medium text-right">{{ number_format($transaction->ending_balance, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-red-600">{{ $transaction->transaction_date }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center text-sm">
                                        <a href="{{ route('expense.print', ['id' => $transaction->reference->id]) }}" class="btn-secondary btn-sm inline-flex items-center gap-1">
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                            </svg>
                                            Print
                                        </a>
                                    </td>
                                </tr>
                            @else
                                <tr class="hover:bg-green-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 font-medium">{{ $transaction->id }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600">{{ $transaction->reference->office->name }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 font-medium text-right">{{ number_format($transaction->amount, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600 font-medium text-right">{{ number_format($transaction->ending_balance, 2) }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-green-600">{{ $transaction->transaction_date }}</td>
                                    <td class="px-4 py-3 whitespace-nowrap text-center text-sm text-gray-400">-</td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-sm text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    No transactions found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Select Office Modal -->
    <x-modal id="selectOfficeModal" title="Select Office" size="md">
        <form action="{{ route('dashboard.select_office') }}" method="post" id="frmSelectOffice" class="space-y-4">
            @csrf
            <div>
                <label for="category" class="form-label">Category</label>
                <select id="category" class="form-input" required>
                    <option value="">Select Office</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="object_of_expenditures" class="form-label">Object of Expenditures</label>
                <select id="object_of_expenditures" name="object_of_expenditures" class="form-input" required>
                </select>
            </div>
            <div>
                <label for="office_id" class="form-label">Office</label>
                <select name="office_id" id="office_id" class="form-input" required>
                </select>
            </div>
        </form>
        
        <!-- Footer outside form -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-200">
            <button type="button" data-modal-close class="btn-outline">Close</button>
            <button type="submit" form="frmSelectOffice" class="btn-primary">Select</button>
        </div>
    </x-modal>

    <!-- Select Date Modal -->
    <x-modal id="selectDateModal" title="Select Date" size="sm">
        <form action="{{ route('dashboard.select_date') }}" method="post" id="frmSelectDate" class="space-y-4">
            @csrf
            <div>
                <label for="year" class="form-label">Year</label>
                <select name="year" id="year" class="form-input">
                    @for($i = 2021; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div>
                <label for="month" class="form-label">Month</label>
                <select name="month" id="month" class="form-input">
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                    @endforeach
                </select>
            </div>
        </form>
        
        <!-- Footer outside form -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-200">
            <button type="button" data-modal-close class="btn-outline">Close</button>
            <button type="submit" form="frmSelectDate" class="btn-primary">Select</button>
        </div>
    </x-modal>

    <!-- Add Expense Modal -->
    <x-modal id="addExpenseModal" title="Add Expense" size="lg">
        <form action="{{ route('expense.store') }}" method="post" id="frmCreateExpense" class="space-y-4">
            <input type="hidden" id="office_allotment_balance" name="allotment_available" value="0" />
            <input type="hidden" id="total_allotment_quarter" name="total_allotment_quarter" value="0" />
            <input type="hidden" id="total_expenses" name="total_expenses" value="0" />
            <input type="hidden" id="ending_balance" name="ending_balance" value="0" />
            <input type="hidden" name="year" value="{{ $year }}" />
            <input type="hidden" name="month" value="{{ $month }}" />
            <input type="hidden" name="office_id" value="{{ $selected_office->id }}" />
            <input type="hidden" name="redirect_to" value="dashboard.index" />
            <input type="hidden" class="form-input" name="transaction_date" id="transaction_date" value="{{ date('Y-m-d') }}" />
            @csrf

            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                <h4 class="text-sm font-semibold text-gray-900 mb-2">{{ $selected_office->getDescription() }}</h4>
                <p class="text-sm text-gray-600">{{ date('F', mktime(0, 0, 0, $month, 1)) . ' ' . $year }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="expense_id_input" class="form-label">Expense ID</label>
                    <input type="text" name="expense_id" id="expense_id_input" class="form-input" value="{{ $expense_id }}" />
                </div>
                <div>
                    <label for="account_code" class="form-label">Account Code</label>
                    <input type="text" name="account_code" id="account_code" class="form-input" />
                </div>
            </div>

            <div>
                <label for="amount" class="form-label">Amount</label>
                <input type="text" name="amount" id="amount" class="form-input" />
            </div>

            <div>
                <label for="remarks" class="form-label">Payee / Remarks</label>
                <textarea name="remarks" id="remarks" class="form-input" rows="3"></textarea>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-200">
                <div class="text-center p-3 bg-blue-50 rounded-lg">
                    <p class="text-xs text-blue-600 mb-1">Allotment Released</p>
                    <p class="text-lg font-semibold text-blue-900 total_allotment_quarter">0.00</p>
                </div>
                <div class="text-center p-3 bg-red-50 rounded-lg">
                    <p class="text-xs text-red-600 mb-1">Total Expenses</p>
                    <p class="text-lg font-semibold text-red-900 total_expenses">0.00</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded-lg">
                    <p class="text-xs text-green-600 mb-1">Allotment Available</p>
                    <p class="text-lg font-semibold text-green-900 office_allotment_balance">0.00</p>
                </div>
                <div class="text-center p-3 bg-purple-50 rounded-lg">
                    <p class="text-xs text-purple-600 mb-1">Ending Balance</p>
                    <p class="text-lg font-semibold text-purple-900 ending_balance">0.00</p>
                </div>
            </div>

        </form>
        
        <!-- Footer outside form to avoid nesting issues -->
        <div class="flex items-center justify-end gap-3 px-6 py-4 bg-gray-50 border-t border-gray-200">
            <button type="button" data-modal-close class="btn-outline">Close</button>
            <button type="submit" form="frmCreateExpense" class="btn-primary">Submit</button>
        </div>
    </x-modal>
@endsection

@section('scripts')
    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script src="{{ asset('js/coreui-chartjs.bundle.js') }}"></script>
    <script src="{{ asset('js/main.js') }}" defer></script>

    <script>
        var url = "{{ url('offices/expense_classes/load_ooes/') }}/";
        var url_expense_classes = "{{ url('offices/load_expense_classes/') }}/";

        $(document).ready(function(){
            $.ajax({
                url: "{{ route('expense.get_office_allotment_balance') }}?office_id={{ $selected_office->id }}&month={{ $month }}&year={{ $year }}",
                method: "GET",
                success: function(data){
                    // Set values with fallback to 0 if undefined
                    var totalAllotmentBalance = data.total_allotment_balance || 0;
                    var totalAllotmentQuarter = data.total_allotment_quarter || 0;
                    var totalExpenses = data.total_expenses || 0;
                    
                    $("#office_allotment_balance").val(totalAllotmentBalance);
                    $("#total_allotment_quarter").val(totalAllotmentQuarter);
                    $("#total_expenses").val(totalExpenses);
                    $(".office_allotment_balance").text(totalAllotmentBalance.toLocaleString());
                    $(".total_allotment_quarter").text(totalAllotmentQuarter.toLocaleString());
                    $(".total_expenses").text(totalExpenses.toLocaleString());
                    $("#amount").attr('max', data);
                    
                    // Initialize ending balance
                    var currentAmount = parseFloat($("#amount").val()) || 0;
                    var endingBalance = totalAllotmentBalance - currentAmount;
                    $("#ending_balance").val(endingBalance);
                    $(".ending_balance").text(endingBalance.toLocaleString());
                },
                error: function(xhr, status, error) {
                    console.error('Error loading office allotment balance:', error);
                    // Set default values on error
                    $("#office_allotment_balance").val(0);
                    $("#total_allotment_quarter").val(0);
                    $("#total_expenses").val(0);
                    $("#ending_balance").val(0);
                }
            });

            $('#amount').on('keyup', function(e){
                var amount = $(this).val();
                var balance = $("#office_allotment_balance").val();
                $("#ending_balance").val(balance - amount);
                $(".ending_balance").text((balance - amount).toLocaleString());
            });

            $('#category').change(function(){
                var id = $(this).val();
                $.ajax({
                    url: url + id,
                    method: "GET",
                    success: function(data){
                        $("select[name=object_of_expenditures]").html(data);
                    } 
                });
            });

            $('#object_of_expenditures').change(function(){
                var id = $(this).val();
                $.ajax({
                    url: url_expense_classes + id,
                    method: "GET",
                    success: function(data){
                        $("#office_id").html(data);
                        $('#office_id').select2({
                            "theme": 'bootstrap',
                            placeholder: "Select expense class"
                        });
                    }
                });
            });

            $('#frmCreateExpense').on('submit', function(e){
                // Ensure all required hidden fields have values before submission
                var endingBalance = parseFloat($('input#ending_balance').val()) || 0;
                var totalExpenses = parseFloat($('input#total_expenses').val()) || 0;
                var totalAllotmentQuarter = parseFloat($('input#total_allotment_quarter').val()) || 0;
                var allotmentAvailable = parseFloat($('input#office_allotment_balance').val()) || 0;
                
                // Set values if they're empty or null
                $('input#ending_balance').val(endingBalance);
                $('input#total_expenses').val(totalExpenses);
                $('input#total_allotment_quarter').val(totalAllotmentQuarter);
                $('input#office_allotment_balance').val(allotmentAvailable);
                
                if(endingBalance < 0){
                    alert('You have insufficient balance!');
                    e.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endsection
