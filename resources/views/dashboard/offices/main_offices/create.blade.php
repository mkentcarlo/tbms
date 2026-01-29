@extends('layouts.modern')

@section('title', 'Create Office')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('office.index') }}">Offices</a></li>
    <li class="text-gray-500">Create</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Office</h1>
            <p class="mt-1 text-sm text-gray-500">Add a new office</p>
        </div>
        <a href="{{ route('office.index') }}" class="btn-outline">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Offices
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('office.store_main_office') }}" method="post" class="space-y-6">
            @csrf

            <div class="card-body space-y-4">
                <x-input-group label="Name" for="name" required>
                    <input 
                        type="text" 
                        class="form-input" 
                        id="name" 
                        name="name" 
                        placeholder="Enter office name..." 
                        required
                        value="{{ old('name') }}"
                    />
                    <x-slot name="help">Please enter office name</x-slot>
                </x-input-group>

                <div>
                    <label for="office_category_id" class="form-label">Office Group</label>
                    <select name="office_category_id" id="office_category_id" class="form-input" required>
                        <option value="">Select Office Group</option>
                        @foreach($office_groups as $office_group)
                            <option value="{{ $office_group->id }}" {{ old('office_category_id') == $office_group->id ? 'selected' : '' }}>
                                {{ $office_group->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Please select office group</p>
                </div>
            </div>

            <div class="card-footer flex justify-end gap-3">
                <a href="{{ route('office.index') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Save Office</button>
            </div>
        </form>
    </div>
@endsection
