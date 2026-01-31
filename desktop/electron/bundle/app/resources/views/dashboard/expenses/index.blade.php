@extends('layouts.modern')

@section('title', 'Expenses')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li class="text-gray-500">Expenses</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Expenses</h1>
            <p class="mt-1 text-sm text-gray-500">Manage and track all expenses</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('expense.print_expenses').$print_params }}" target="_blank" class="btn-secondary btn-sm inline-flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                </svg>
                Print Results
            </a>
            <a href="{{ route('expense.create') }}" class="btn-primary btn-sm inline-flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Expense
            </a>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Filters Card -->
        <div class="card">
            <div class="card-body">
                <form action="" method="GET" id="frmFilter" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div>
                            <label for="s" class="form-label">Search</label>
                            <input 
                                type="text" 
                                id="s"
                                name="s" 
                                class="form-input" 
                                placeholder="Account code / Budget" 
                                value="{{ request('s') }}"
                            />
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category" class="form-label">Category</label>
                            <select name="office_category_id" id="category" class="form-input">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('office_category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Object of Expenditures -->
                        <div>
                            <label for="object_of_expenditures" class="form-label">Object of Expenditures</label>
                            <select name="object_of_expenditures" id="object_of_expenditures" class="form-input">
                                <option value="">All Objects</option>
                            </select>
                        </div>

                        <!-- Office -->
                        <div>
                            <label for="office_id" class="form-label">Office</label>
                            <select name="office_id" id="office_id" class="form-input">
                                <option value="">All Offices</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}" {{ request('office_id') == $office->id ? 'selected' : '' }}>
                                        {{ $office->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Month -->
                        <div>
                            <label for="month" class="form-label">Month</label>
                            <select name="m" id="month" class="form-input">
                                <option value="">All Months</option>
                                @php
                                    $sm = request('m', date('m'));
                                @endphp
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $m == $sm ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Year -->
                        <div>
                            <label for="year" class="form-label">Year</label>
                            <select name="y" id="year" class="form-input">
                                @php
                                    $sy = request('y', date('Y'));
                                @endphp
                                @for($i = 2021; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ $sy == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Expenses Table -->
        <div class="card">
            <div class="card-body">
                @if($expenses->count() > 0)
                    <div class="table-container">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expense Class</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account Code</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ending Balance</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction Date</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($expenses as $expense)
                                    @php
                                        $transaction = $expense->transaction();
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $expense->office ? $expense->office->getDescription() : 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $expense->account_code ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ₱{{ $transaction ? number_format($transaction->amount, 2) : number_format($expense->amount ?? 0, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            ₱{{ $transaction ? number_format($transaction->ending_balance, 2) : '0.00' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            {{ $transaction && $transaction->remarks ? Str::limit($transaction->remarks, 50) : ($expense->remarks ? Str::limit($expense->remarks, 50) : '-') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $transaction ? $transaction->transaction_date : ($expense->created_at ? $expense->created_at->format('Y-m-d') : '-') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                <a 
                                                    href="{{ route('expense.print', ['id' => $expense->id]) }}" 
                                                    target="_blank"
                                                    class="btn-secondary btn-sm inline-flex items-center gap-1 print-link">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                                    </svg>
                                                    Print
                                                </a>
                                                <a 
                                                    href="{{ route('expense.delete', ['id' => $expense->id]) }}" 
                                                    class="btn-danger btn-sm inline-flex items-center gap-1 delete-link"
                                                    onclick="return confirm('Are you sure you want to delete this expense?');">
                                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                    Delete
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $expenses->appends(request()->input())->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No expenses found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new expense.</p>
                        <div class="mt-6">
                            <a href="{{ route('expense.create') }}" class="btn-primary inline-flex items-center gap-2">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                New Expense
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function(){
            var url = "{{ url('offices/expense_classes/load_ooes/') }}/";
            var url_expense_classes = "{{ url('offices/load_expense_classes/') }}/";
            
            // Set initial values if they exist
            setTimeout(() => {
                var categoryId = {{ request('office_category_id', 'null') }};
                if (categoryId) {
                    $("select[name=office_category_id]").val(categoryId).trigger('change');
                }
            }, 500);

            setTimeout(() => {
                var ooeId = {{ request('object_of_expenditures', 'null') }};
                if (ooeId) {
                    $('select[name=object_of_expenditures]').val(ooeId).trigger('change');
                }
            }, 1500);

            setTimeout(() => {
                var officeId = {{ request('office_id', 'null') }};
                if (officeId) {
                    $('select[name=office_id]').val(officeId);
                }
            }, 2000);

            // Auto-submit on filter change
            $("#office_id, #month, #year").change(function(){
                $("input[name=s]").val('');
                $("#frmFilter").submit();
            });
            
            // Category change - load object of expenditures
            $('#category').change(function(){
                var id = $(this).val();
                $.ajax({
                    url: url + id,
                    method: "GET",
                    success: function(data){
                        $("select[name=object_of_expenditures]").html('<option value="">All Objects</option>' + data);
                    } 
                });
            });

            // Object of expenditures change - load expense classes
            $('#object_of_expenditures').change(function(){
                var id = $(this).val();
                $.ajax({
                    url: url_expense_classes + id,
                    method: "GET",
                    success: function(data){
                        $("#office_id").html('<option value="">All Offices</option>' + data);
                        $('#office_id').select2({
                            "theme": 'bootstrap',
                            placeholder: "Select expense class"
                        });
                    }
                });
            });

            // Print link handler
            $('.print-link').click(function(e){
                e.preventDefault();
                var link = $(this).attr('href');
                window.open(link, 'Print', 'toolbar=no,location=no,menubar=no,scrollbars=no,resizable=no,titlebar=no');
            });
        });
    </script>
@endsection
