@extends('layouts.modern')

@section('title', 'Create Expense')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('expense.index') }}">Expenses</a></li>
    <li class="text-gray-500">Create</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Expense</h1>
            <p class="mt-1 text-sm text-gray-500">Add a new expense record</p>
        </div>
        <a href="{{ route('expense.index') }}" class="btn-outline">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Expenses
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('expense.store') }}" method="post" id="frmCreateExpense" class="space-y-6">
            <input type="hidden" name="redirect_to" value="expense.index" />
            @csrf

            <div class="card-body space-y-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="transaction_date" class="form-label">Transaction Date</label>
                            <input 
                                type="date" 
                                name="transaction_date" 
                                id="transaction_date" 
                                class="form-input" 
                                value="{{ date('Y-m-d') }}" 
                                required
                            />
                            <p class="mt-1 text-xs text-gray-500">Please select transaction date</p>
                        </div>

                        <div>
                            <label for="expense_id" class="form-label">Expense ID</label>
                            <input 
                                type="text" 
                                name="expense_id" 
                                id="expense_id"
                                class="form-input" 
                                value="{{ $expense_id }}" 
                                required
                                readonly
                            />
                            <p class="mt-1 text-xs text-gray-500">Auto-generated ID</p>
                        </div>

                        <div>
                            <label for="account_code" class="form-label">Account Code</label>
                            <input 
                                type="text" 
                                name="account_code" 
                                id="account_code"
                                class="form-input" 
                                required
                            />
                        </div>

                        <div>
                            <label for="year" class="form-label">Year</label>
                            <select name="year" id="year" class="form-input" required>
                                @for($i = 2021; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ date('Y') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Please select year</p>
                        </div>

                        <div>
                            <label for="month" class="form-label">Month</label>
                            <select name="month" id="month" class="form-input" required>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Please select month</p>
                        </div>
                    </div>
                </div>

                <!-- Office Selection -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Office Selection</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="category" class="form-label">Category</label>
                            <select name="office_category_id" id="category" class="form-input">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Please select category</p>
                        </div>

                        <div>
                            <label for="object_of_expenditures" class="form-label">Object of Expenditure</label>
                            <select name="object_of_expenditures" id="object_of_expenditures" class="form-input">
                                <option value="">Select Object</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Please select object of expenditure</p>
                        </div>

                        <div>
                            <label for="office_id" class="form-label">Expense Class</label>
                            <select name="office_id" id="office_id" class="form-input" required>
                                <option value="">Select Expense Class</option>
                            </select>
                            <p class="mt-1 text-xs text-gray-500">Please select expense class</p>
                        </div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Financial Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="total_allotment_quarter" class="form-label">4th Quarter Allotment Total</label>
                            <input 
                                type="number" 
                                id="total_allotment_quarter" 
                                name="total_allotment_quarter" 
                                class="form-input" 
                                readonly
                                value="0"
                            />
                            <p class="mt-1 text-xs text-gray-500">Total allotment release as of 4th quarter</p>
                        </div>

                        <div>
                            <label for="total_expenses" class="form-label">Total Expenses</label>
                            <input 
                                type="number" 
                                id="total_expenses" 
                                name="total_expenses" 
                                class="form-input" 
                                readonly
                                value="0"
                            />
                            <p class="mt-1 text-xs text-gray-500">Less: Total obligation incurred</p>
                        </div>

                        <div>
                            <label for="office_allotment_balance" class="form-label">Allotment Available</label>
                            <input 
                                type="number" 
                                id="office_allotment_balance" 
                                name="allotment_available" 
                                class="form-input" 
                                readonly
                                value="0"
                            />
                            <p class="mt-1 text-xs text-gray-500">Office allotment balance</p>
                        </div>

                        <div>
                            <label for="amount" class="form-label">Amount</label>
                            <input 
                                type="text" 
                                name="amount" 
                                id="amount" 
                                class="form-input" 
                                required
                            />
                            <p class="mt-1 text-xs text-gray-500">Please enter amount</p>
                        </div>

                        <div>
                            <label for="ending_balance" class="form-label">Ending Balance</label>
                            <input 
                                type="number" 
                                name="ending_balance" 
                                id="ending_balance" 
                                class="form-input" 
                                readonly
                                required
                                value="0"
                            />
                            <p class="mt-1 text-xs text-gray-500">Allotment available after the amount</p>
                        </div>
                    </div>
                </div>

                <!-- Remarks -->
                <div>
                    <label for="remarks" class="form-label">Payee / Remarks</label>
                    <textarea 
                        class="form-input" 
                        id="remarks" 
                        name="remarks" 
                        rows="4" 
                        placeholder="Enter payee or remarks..."
                    ></textarea>
                </div>
            </div>

            <div class="card-footer flex items-center justify-end gap-3">
                <a href="{{ route('expense.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Save Expense</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        var url = "{{ url('offices/expense_classes/load_ooes/') }}/";
        var url_expense_classes = "{{ url('offices/load_expense_classes/') }}/";
        
        $(document).ready(function(){
            // Load office allotment balance when office, month, or year changes
            $('#office_id').change(function(e){
                var office_id = $("#office_id").val();
                var month = $("#month").val();
                var year = $("#year").val();
                
                if (office_id && month && year) {
                    $.ajax({
                        url: "{{ route('expense.get_office_allotment_balance') }}?office_id=" + office_id + "&month=" + month + "&year=" + year,
                        method: "GET",
                        success: function(data){
                            var totalAllotmentBalance = data.total_allotment_balance || 0;
                            var totalAllotmentQuarter = data.total_allotment_quarter || 0;
                            var totalExpenses = data.total_expenses || 0;
                            
                            $("#office_allotment_balance").val(totalAllotmentBalance.toFixed(2));
                            $("#total_allotment_quarter").val(totalAllotmentQuarter.toFixed(2));
                            $("#total_expenses").val(totalExpenses.toFixed(2));
                            $("#amount").attr('max', totalAllotmentBalance);
                            
                            // Calculate ending balance
                            var currentAmount = parseFloat($("#amount").val()) || 0;
                            var endingBalance = totalAllotmentBalance - currentAmount;
                            $("#ending_balance").val(endingBalance.toFixed(2));
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
                }
            });

            // Calculate ending balance when amount changes
            $('#amount').on('keyup', function(e){
                var amount = parseFloat($(this).val()) || 0;
                var balance = parseFloat($("#office_allotment_balance").val()) || 0;
                var endingBalance = balance - amount;
                $("#ending_balance").val(endingBalance.toFixed(2));
            });

            // Category change - load object of expenditures
            $('#category').change(function(){
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url: url + id,
                        method: "GET",
                        success: function(data){
                            $("select[name=object_of_expenditures]").html('<option value="">Select Object</option>' + data);
                        } 
                    });
                } else {
                    $("select[name=object_of_expenditures]").html('<option value="">Select Object</option>');
                }
            });

            // Object of expenditures change - load expense classes
            $('#object_of_expenditures').change(function(){
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url: url_expense_classes + id,
                        method: "GET",
                        success: function(data){
                            $("#office_id").html('<option value="">Select Expense Class</option>' + data);
                            $('#office_id').select2({
                                "theme": 'bootstrap',
                                placeholder: "Select expense class"
                            });
                        }
                    });
                } else {
                    $("#office_id").html('<option value="">Select Expense Class</option>');
                }
            });

            // Form validation
            $('#frmCreateExpense').on('submit', function(e){
                var endingBalance = parseFloat($('input#ending_balance').val()) || 0;
                
                // Ensure all required hidden fields have values
                var totalExpenses = parseFloat($('input#total_expenses').val()) || 0;
                var totalAllotmentQuarter = parseFloat($('input#total_allotment_quarter').val()) || 0;
                var allotmentAvailable = parseFloat($('input#office_allotment_balance').val()) || 0;
                
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
