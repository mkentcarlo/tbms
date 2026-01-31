@extends('layouts.modern')

@section('title', 'Create Office Group')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('office.office_groups') }}">Office Groups</a></li>
    <li class="text-gray-500">Create</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Office Group</h1>
            <p class="mt-1 text-sm text-gray-500">Add a new office group</p>
        </div>
        <a href="{{ route('office.office_groups') }}" class="btn-outline">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Office Groups
        </a>
    </div>
@endsection

@section('content')
    <div class="card">
        <form action="{{ route('office.store_office_group') }}" method="post" class="space-y-6">
            @csrf

            <div class="card-body space-y-4">
                <x-input-group label="Office Group Name" for="name" required>
                    <input 
                        type="text" 
                        class="form-input" 
                        id="name" 
                        name="name" 
                        placeholder="Enter office group name..." 
                        required
                        value="{{ old('name') }}"
                    />
                    <x-slot name="help">Please enter office group name</x-slot>
                </x-input-group>
            </div>

            <div class="card-footer flex justify-end gap-3">
                <a href="{{ route('office.office_groups') }}" class="btn-outline">Cancel</a>
                <button type="submit" class="btn-primary">Save Office Group</button>
            </div>
        </form>
    </div>
@endsection
