@extends('layouts.modern')

@section('title', 'User Details')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li><a href="{{ route('users.index') }}">Users</a></li>
    <li class="text-gray-500">{{ $user->name }}</li>
@endsection

@section('page-header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
        <p class="mt-1 text-sm text-gray-500">View user information</p>
    </div>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="flex items-center gap-4">
                <div class="h-16 w-16 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0">
                    <span class="text-primary-600 font-bold text-2xl">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>
        </div>
        <div class="card-body">
            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Email Address</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Created At</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'N/A' }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at ? $user->updated_at->format('M d, Y') : 'N/A' }}</dd>
                </div>
            </dl>
        </div>
        <div class="card-footer">
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('users.index') }}" class="btn-outline">Back to Users</a>
                <a href="{{ route('users.edit', $user->id) }}" class="btn-primary">Edit User</a>
            </div>
        </div>
    </div>
@endsection
