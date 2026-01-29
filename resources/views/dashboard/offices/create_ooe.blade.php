@extends('layouts.modern')

@section('title', 'Create Object of Expenditure')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('office.object_expenditures') }}">Object of Expenditures</a></li>
    <li class="text-gray-500">Create</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Object of Expenditure</h1>
            <p class="mt-1 text-sm text-gray-500">Add a new object of expenditure</p>
        </div>
        <a href="{{ route('office.object_expenditures') }}" class="btn-outline">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Object of Expenditures
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('office.store_ooe') }}" method="post" class="space-y-6">
            @csrf

            <div class="card-body space-y-4">
                <x-input-group label="Name" for="name" required>
                    <input 
                        type="text" 
                        class="form-input" 
                        id="name" 
                        name="name" 
                        placeholder="Enter object of expenditure name..." 
                        required
                        value="{{ old('name') }}"
                    />
                    <x-slot name="help">Please enter object of expenditure name</x-slot>
                </x-input-group>

                <div>
                    <label for="office_category_id" class="form-label">Office</label>
                    <select name="office_category_id" id="office_category_id" class="form-input" required>
                        <option value="">Select Office</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('office_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Please select office</p>
                </div>
            </div>

            <div class="card-footer flex justify-end gap-3">
                <a href="{{ route('office.object_expenditures') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Save Object of Expenditure</button>
            </div>
        </form>
    </div>
@endsection
