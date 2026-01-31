@extends('layouts.modern')

@section('title', 'Create User')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="text-gray-500">Create</li>
@endsection

@section('page-header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Create User</h1>
        <p class="mt-1 text-sm text-gray-500">Add a new user to the system</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-gray-900">User Information</h3>
        </div>
        <form method="POST" action="{{ route('users.store') }}" class="card-body">
            @csrf

            <!-- Name -->
            <div class="mb-6">
                <x-input-group label="Name" required>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autofocus
                            class="form-input pl-10 @error('name') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                            placeholder="John Doe"
                        >
                    </div>
                </x-input-group>
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-6">
                <x-input-group label="Email Address" required>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required
                            class="form-input pl-10 @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                            placeholder="user@example.com"
                        >
                    </div>
                </x-input-group>
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-6">
                <x-input-group label="Password" required>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input 
                            type="password" 
                            name="password" 
                            required
                            minlength="6"
                            class="form-input pl-10 @error('password') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                            placeholder="••••••••"
                        >
                    </div>
                </x-input-group>
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Password must be at least 6 characters long.</p>
            </div>

            <!-- Form Actions -->
            <div class="card-footer">
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-secondary">Create User</button>
                </div>
            </div>
        </form>
    </div>
@endsection
