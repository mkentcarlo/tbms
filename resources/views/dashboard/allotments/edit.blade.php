@extends('layouts.modern')

@section('title', 'Edit Allotment')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('allotment.index') }}">Allotments</a></li>
    <li class="text-gray-500">Edit #{{ $allotment->id }}</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Allotment</h1>
            <p class="mt-1 text-sm text-gray-500">Update allotment record #{{ $allotment->id }}</p>
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
            <form action="{{ route('allotment.update', ['id' => $allotment->id]) }}" method="post" id="frmEditAllotment" class="space-y-6">
                @csrf

                <div class="card-body space-y-6">
                    <!-- Period Selection -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Period</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="year" class="form-label">Year <span class="text-red-500">*</span></label>
                                <select name="year" id="year" class="form-input" required>
                                    <option value="">Select Year</option>
                                    @for($i = 2021; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}" {{ $i == $allotment->year ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Select the fiscal year</p>
                            </div>

                            <div>
                                <label for="month" class="form-label">Month</label>
                                <select name="month" id="month" class="form-input">
                                    <option value="">-- No Month (Appropriation) --</option>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}" {{ $m == $allotment->month ? 'selected' : '' }}>
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
                                <select name="office_category_id" id="category" class="form-input">
                                    <option value="">Select Office Group</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $allotment->expense_class->category->parent->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Select office group</p>
                            </div>

                            <div>
                                <label for="object_of_expenditures" class="form-label">Office <span class="text-red-500">*</span></label>
                                <select name="object_of_expenditures" id="object_of_expenditures" class="form-input">
                                    @foreach($object_expenditures as $object_expenditure)
                                        <option value="{{ $object_expenditure->id }}" {{ $object_expenditure->id == $allotment->expense_class->category->id ? 'selected' : '' }}>
                                            {{ $object_expenditure->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-1 text-xs text-gray-500">Select office</p>
                            </div>

                            <div>
                                <label for="office_id" class="form-label">Expense Class <span class="text-red-500">*</span></label>
                                <select name="office_id" id="office_id" class="form-input" required>
                                    @foreach($expense_classes as $expense_class)
                                        <option value="{{ $expense_class->id }}" {{ $expense_class->id == $allotment->office_id ? 'selected' : '' }}>
                                            {{ $expense_class->name }}
                                        </option>
                                    @endforeach
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
                                        value="{{ $allotment->amount }}"
                                        required
                                    />
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Enter the allotment amount</p>
                            </div>

                            <div>
                                <label class="form-label">Current Balance</label>
                                <div class="px-4 py-2 bg-gray-50 rounded-lg border border-gray-200">
                                    <span class="text-lg font-semibold text-gray-900">
                                        ₱{{ number_format($allotment->amount, 2) }}
                                    </span>
                                    <span class="text-sm text-gray-500 ml-2">allocated</span>
                                </div>
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
                        >{{ $allotment->remarks }}</textarea>
                    </div>

                    <!-- Record Info -->
                    <div class="rounded-lg bg-gray-50 p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Record Information</h4>
                        <dl class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <dt class="text-gray-500">Created</dt>
                                <dd class="font-medium text-gray-900">{{ $allotment->created_at ? $allotment->created_at->format('M d, Y h:i A') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-gray-500">Last Updated</dt>
                                <dd class="font-medium text-gray-900">{{ $allotment->updated_at ? $allotment->updated_at->format('M d, Y h:i A') : 'N/A' }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="card-footer flex items-center justify-between">
                    <a 
                        href="{{ route('allotment.delete', ['id' => $allotment->id]) }}" 
                        class="btn-danger inline-flex items-center gap-2"
                        onclick="return confirm('Are you sure you want to delete this allotment? This action cannot be undone.');">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </a>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('allotment.index') }}" class="btn-outline">Cancel</a>
                        <button type="submit" class="btn-primary">Update Allotment</button>
                    </div>
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
        });
    </script>
@endsection
