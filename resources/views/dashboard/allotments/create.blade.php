@extends('layouts.modern')

@section('title', 'Create Allotment')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('allotment.index') }}">Allotments</a></li>
    <li class="text-gray-500">Create</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create New Allotment</h1>
            <p class="mt-1 text-sm text-gray-500">Add a new budget allotment or appropriation</p>
        </div>
        <a href="{{ route('allotment.index') }}" class="btn-outline inline-flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Allotments
        </a>
    </div>
@endsection

@section('content')
    <div class="max-w-4xl">
        <div class="card">
            <form action="{{ route('allotment.store') }}" method="post" id="frmCreateAllotment" class="space-y-6">
                @csrf

                <div class="card-body space-y-6">
                    <!-- Info Banner -->
                    <div class="rounded-lg bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    <strong>Note:</strong> Leave the month blank if you want to add this as an appropriation (yearly budget).
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Period Selection -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Period</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="year" class="form-label">Year <span class="text-red-500">*</span></label>
                                <select name="year" id="year" class="form-input" required>
                                    <option value="">Select Year</option>
                                    @for($i = 2021; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Select the fiscal year</p>
                            </div>

                            <div>
                                <label for="month" class="form-label">Month</label>
                                <select name="month" id="month" class="form-input">
                                    <option value="0">-- No Month (Appropriation) --</option>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $m == date('m') ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Leave blank for yearly appropriation</p>
                            </div>
                        </div>
                    </div>

                    <!-- Office Selection -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Office Selection</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="category" class="form-label">Office Group <span class="text-red-500">*</span></label>
                                <select name="office_category_id" id="category" class="form-input" required>
                                    <option value="">Select Office Group</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Select office group</p>
                            </div>

                            <div>
                                <label for="object_of_expenditures" class="form-label">Office <span class="text-red-500">*</span></label>
                                <select name="object_of_expenditures" id="object_of_expenditures" class="form-input" required>
                                    <option value="">Select Office</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Select office</p>
                            </div>

                            <div>
                                <label for="office_id" class="form-label">Expense Class <span class="text-red-500">*</span></label>
                                <select name="office_id" id="office_id" class="form-input" required>
                                    <option value="">Select Expense Class</option>
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Select expense class</p>
                            </div>
                        </div>
                    </div>

                    <!-- Financial Information -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Financial Information</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="amount" class="form-label">Amount <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₱</span>
                                    </div>
                                    <input 
                                        type="number" 
                                        step="0.01"
                                        name="amount" 
                                        id="amount" 
                                        class="form-input pl-8" 
                                        placeholder="0.00"
                                        required
                                    />
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Enter the allotment amount</p>
                            </div>

                            <div>
                                <label for="appropriation_balance" class="form-label">Appropriation Balance</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₱</span>
                                    </div>
                                    <input type="hidden" id="initial_approriation_balance" value="0" />
                                    <input 
                                        type="text" 
                                        id="appropriation_balance" 
                                        class="form-input pl-8 bg-gray-50" 
                                        readonly
                                        value="0.00"
                                    />
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Remaining appropriation balance</p>
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label for="remarks" class="form-label">Remarks</label>
                        <textarea 
                            class="form-input" 
                            id="remarks" 
                            name="remarks" 
                            rows="4" 
                            placeholder="Enter any additional notes or remarks..."
                        ></textarea>
                    </div>
                </div>

                <div class="card-footer flex items-center justify-end gap-3">
                    <a href="{{ route('allotment.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Save Allotment</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var url = "{{ url('offices/expense_classes/load_ooes/') }}/";
        var url_expense_classes = "{{ url('offices/load_expense_classes/') }}/";
        
        $(document).ready(function(){
            // Category change - load offices (OOE)
            $('#category').change(function(){
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url: url + id,
                        method: "GET",
                        success: function(data){
                            $("select[name=object_of_expenditures]").html('<option value="">Select Office</option>' + data);
                        }
                    });
                } else {
                    $("select[name=object_of_expenditures]").html('<option value="">Select Office</option>');
                    $("#office_id").html('<option value="">Select Expense Class</option>');
                }
            });

            // Object of expenditures change - load expense classes
            $('#object_of_expenditures').change(function(){
                var id = $(this).val();
                var year = $("#year").val();

                if (id) {
                    $.ajax({
                        url: url_expense_classes + id + "?year=" + year,
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

            // Office change - get appropriation balance
            $("#office_id").change(function(){
                var balance = $(this).find(':selected').attr('data-balance') || 0;
                $("#initial_approriation_balance").val(balance);
                $("#appropriation_balance").val(parseFloat(balance).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            });

            // Amount keyup - calculate remaining balance
            $('#amount').on('keyup', function(e){
                var amount = parseFloat($(this).val()) || 0;
                var init_balance = parseFloat($("#initial_approriation_balance").val()) || 0;
                var remaining = init_balance - amount;
                $("#appropriation_balance").val(remaining.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            });

            // Form validation
            $("#frmCreateAllotment").submit(function(e){
                var balance = parseFloat($("#initial_approriation_balance").val()) || 0;
                var amount = parseFloat($("#amount").val()) || 0;
                var remaining = balance - amount;
                
                if(remaining < 0 && $('#month').val() == '0'){
                    e.preventDefault();
                    alert('Insufficient appropriation balance for yearly appropriation.');
                    return false;
                }
            });
        });
    </script>
@endsection
