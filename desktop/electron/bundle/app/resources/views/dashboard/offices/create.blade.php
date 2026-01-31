@extends('layouts.modern')

@section('title', 'Create Expense Class')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('office.expense_classes') }}">Expense Classes</a></li>
    <li class="text-gray-500">Create</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Expense Class</h1>
            <p class="mt-1 text-sm text-gray-500">Add a new expense class</p>
        </div>
        <a href="{{ route('office.expense_classes') }}" class="btn-outline">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Expense Classes
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('office.store') }}" method="post" class="space-y-6">
            @csrf

            <div class="card-body space-y-4">
                <x-input-group label="Expense Class Name" for="name" required>
                    <input 
                        type="text" 
                        class="form-input" 
                        id="name" 
                        name="name" 
                        placeholder="Enter expense class name..." 
                        required
                        value="{{ old('name') }}"
                    />
                    <x-slot name="help">Please enter expense class name</x-slot>
                </x-input-group>

                <div>
                    <label for="office_category_id" class="form-label">Select Office Group</label>
                    <select name="office_category_id" id="office_category_id" class="form-input" required>
                        <option value="">Select Office Group</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('office_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Please select office group</p>
                </div>

                <div>
                    <label for="object_of_expenditures" class="form-label">Select Object of Expenditures</label>
                    <select name="object_of_expenditures" id="object_of_expenditures" class="form-input" required>
                        <option value="">Select Object of Expenditures</option>
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Please select object of expenditures</p>
                </div>

                <x-input-group label="Tag" for="description">
                    <input 
                        type="text" 
                        class="form-input" 
                        id="description" 
                        name="description" 
                        placeholder="Enter tag..." 
                        value="{{ old('description') }}"
                    />
                    <x-slot name="help">Optional tag for this expense class</x-slot>
                </x-input-group>
            </div>

            <div class="card-footer flex justify-end gap-3">
                <a href="{{ route('office.expense_classes') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Save Expense Class</button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/typeahead.js') }}"></script>
    <script>
        var url = "{{ url('offices/expense_classes/load_ooes/') }}/";
        $(document).ready(function(){
            $('#office_category_id').change(function(){
                var id = $(this).val();
                if (id) {
                    $.ajax({
                        url: url + id,
                        method: "GET",
                        success: function(data){
                            $("select[name=object_of_expenditures]").html('<option value="">Select Object of Expenditures</option>' + data);
                        }
                    });
                } else {
                    $("select[name=object_of_expenditures]").html('<option value="">Select Object of Expenditures</option>');
                }
            });

            var path = "{{ route('office.load_tags') }}";
            $('input#description').typeahead({
                source: function (query, process) {
                    return $.get(path, { query: query }, function (data) {
                        return process(data);
                    });
                }
            });
        });
    </script>
@endsection
