@extends('layouts.modern')

@section('title', 'Modern UI Test')

@section('breadcrumbs')
    <li>
        <div class="flex items-center">
            <a href="/" class="text-gray-400 hover:text-gray-500">Home</a>
        </div>
    </li>
    <li>
        <div class="flex items-center">
            <svg class="flex-shrink-0 h-5 w-5 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
            </svg>
            <span class="ml-2 text-gray-500">Test Page</span>
        </div>
    </li>
@endsection

@section('page-header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Modern UI Test Page</h1>
        <p class="mt-2 text-sm text-gray-600">This is a test page to verify the new modern UI components.</p>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Cards Example -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-primary-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Expenses</dt>
                                <dd class="text-lg font-semibold text-gray-900">₱ 125,000</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Approved</dt>
                                <dd class="text-lg font-semibold text-gray-900">45</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending</dt>
                                <dd class="text-lg font-semibold text-gray-900">12</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Rejected</dt>
                                <dd class="text-lg font-semibold text-gray-900">3</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buttons Example -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Button Components</h3>
            </div>
            <div class="card-body">
                <div class="flex flex-wrap gap-3">
                    <button class="btn-primary">Primary Button</button>
                    <button class="btn-secondary">Secondary Button</button>
                    <button class="btn-outline">Outline Button</button>
                    <button class="btn-ghost">Ghost Button</button>
                    <button class="btn-danger">Danger Button</button>
                </div>
            </div>
        </div>

        <!-- Badges Example -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Badge Components</h3>
            </div>
            <div class="card-body">
                <div class="flex flex-wrap gap-2">
                    <span class="badge-primary">Primary</span>
                    <span class="badge-success">Success</span>
                    <span class="badge-warning">Warning</span>
                    <span class="badge-danger">Danger</span>
                </div>
            </div>
        </div>

        <!-- Form Example -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Form Components</h3>
            </div>
            <div class="card-body">
                <form class="space-y-4">
                    <div>
                        <label class="form-label">Email Address</label>
                        <input type="email" class="form-input" placeholder="you@example.com">
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" class="form-input" placeholder="••••••••">
                    </div>
                    <div>
                        <button type="submit" class="btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
