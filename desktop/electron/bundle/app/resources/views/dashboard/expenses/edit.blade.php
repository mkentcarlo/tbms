@extends('layouts.modern')

@section('title', 'Edit Expense')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('expense.index') }}">Expenses</a></li>
    <li class="text-gray-500">Edit</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Expense</h1>
            <p class="mt-1 text-sm text-gray-500">Update expense information</p>
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
        <form action="{{ route('expense.update', ['id' => $expense->id]) }}" method="post" class="space-y-6">
            @csrf

            <div class="card-body space-y-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Basic Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="year" class="form-label">Year</label>
                            <select name="year" id="year" class="form-input" required>
                                @for($i = 2021; $i <= date('Y'); $i++)
                                    <option value="{{ $i }}" {{ $expense->year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div>
                            <label for="month" class="form-label">Month</label>
                            <select name="month" id="month" class="form-input" required>
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $expense->month == $m ? 'selected' : '' }}>
                                        {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="office_id" class="form-label">Office</label>
                            <select name="office_id" id="office_id" class="form-input" required>
                                <option value="">Select Office</option>
                                @foreach($offices as $office)
                                    <option value="{{ $office->id }}" {{ $expense->office_id == $office->id ? 'selected' : '' }}>
                                        {{ $office->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="amount" class="form-label">Amount</label>
                            <input 
                                type="text" 
                                name="amount" 
                                id="amount" 
                                class="form-input" 
                                value="{{ $expense->amount }}"
                                required
                            />
                        </div>

                        <div class="md:col-span-2">
                            <label for="remarks" class="form-label">Remarks</label>
                            <textarea 
                                class="form-input" 
                                id="remarks" 
                                name="remarks" 
                                rows="4" 
                                placeholder="Enter remarks..."
                            >{{ $expense->remarks }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer flex items-center justify-end gap-3">
                <a href="{{ route('expense.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Update Expense</button>
            </div>
        </form>
    </div>
@endsection
