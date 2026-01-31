@extends('layouts.modern')

@section('title', 'Edit User')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="text-gray-500">Edit</li>
@endsection

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
            <p class="mt-1 text-sm text-gray-500">Update user information</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-soft border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex items-center gap-3">
                    @if($user->profile_picture)
                        <img src="{{ asset($user->profile_picture) }}" alt="{{ $user->name }}" class="h-10 w-10 rounded-full object-cover">
                    @else
                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center">
                            <span class="text-white font-medium">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $user->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ route('users.update', $user->id) }}">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-5">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                value="{{ old('name', $user->name) }}" 
                                required 
                                autofocus
                                class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('name') border-red-300 focus:ring-red-500 @enderror" 
                                placeholder="John Doe"
                            >
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                value="{{ old('email', $user->email) }}" 
                                required
                                class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('email') border-red-300 focus:ring-red-500 @enderror" 
                                placeholder="user@example.com"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- User Info -->
                    <div class="pt-4 border-t border-gray-200">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500">Role:</span>
                                <span class="ml-2 font-medium">
                                    @if($user->hasRole('super_admin'))
                                        Super Admin
                                    @elseif($user->hasRole('admin'))
                                        Admin
                                    @else
                                        User
                                    @endif
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500">Joined:</span>
                                <span class="ml-2 font-medium">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                    <a href="{{ route('users.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary inline-flex items-center gap-2">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
