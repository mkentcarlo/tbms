@extends('layouts.modern')

@section('title', 'User Details')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="text-gray-500">{{ $user->name }}</li>
@endsection

@section('page-header')
    <div class="flex items-center gap-4">
        <a href="{{ route('users.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
            <p class="mt-1 text-sm text-gray-500">View user information</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="max-w-2xl">
        <div class="bg-white rounded-xl shadow-soft border border-gray-200 overflow-hidden">
            <!-- User Header -->
            <div class="px-6 py-6 bg-gradient-to-br from-primary-500 to-primary-600 text-white">
                <div class="flex items-center gap-4">
                    @if($user->profile_picture)
                        <img src="{{ asset($user->profile_picture) }}" alt="{{ $user->name }}" class="h-16 w-16 rounded-full object-cover border-2 border-white/30">
                    @else
                        <div class="h-16 w-16 rounded-full bg-white/20 flex items-center justify-center border-2 border-white/30">
                            <span class="text-white font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-xl font-bold">{{ $user->name }}</h3>
                        <p class="text-primary-100 flex items-center gap-2 mt-1">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- User Info -->
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-1.5 bg-blue-100 rounded-lg">
                                <svg class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Name</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->name }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-1.5 bg-purple-100 rounded-lg">
                                <svg class="h-4 w-4 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Role</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">
                            @if($user->hasRole('super_admin'))
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                    Super Admin
                                </span>
                            @elseif($user->hasRole('admin'))
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                    Admin
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-700">
                                    User
                                </span>
                            @endif
                        </p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-1.5 bg-green-100 rounded-lg">
                                <svg class="h-4 w-4 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</p>
                        @if($user->created_at)
                            <p class="text-xs text-gray-500 mt-1">{{ $user->created_at->diffForHumans() }}</p>
                        @endif
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-1.5 bg-amber-100 rounded-lg">
                                <svg class="h-4 w-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</span>
                        </div>
                        <p class="text-sm font-semibold text-gray-900">{{ $user->updated_at ? $user->updated_at->format('M d, Y') : 'N/A' }}</p>
                        @if($user->updated_at)
                            <p class="text-xs text-gray-500 mt-1">{{ $user->updated_at->diffForHumans() }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between gap-3">
                <a href="{{ route('users.index') }}" class="btn-outline inline-flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Users
                </a>
                <a href="{{ route('users.edit', $user->id) }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit User
                </a>
            </div>
        </div>
    </div>
@endsection
