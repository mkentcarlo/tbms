@extends('layouts.modern')

@section('title', 'Phase 2 Components Demo')

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
            <span class="ml-2 text-gray-500">Phase 2 Demo</span>
        </div>
    </li>
@endsection

@section('page-header')
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Phase 2 Components Demo</h1>
        <p class="mt-2 text-sm text-gray-600">Demonstrating all new components from Phase 2</p>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Data Table Component -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Data Table Component</h3>
            </div>
            <div class="card-body">
                <x-table 
                    :headers="['Name', 'Email', 'Role', 'Status']"
                    :rows="[
                        ['John Doe', 'john@example.com', 'Admin', 'Active'],
                        ['Jane Smith', 'jane@example.com', 'User', 'Active'],
                        ['Bob Wilson', 'bob@example.com', 'User', 'Inactive'],
                    ]"
                />
            </div>
        </div>

        <!-- Modal Component -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Modal Dialog Component</h3>
            </div>
            <div class="card-body">
                <button onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: { id: 'demo-modal' } }))" class="btn-primary">
                    Open Modal
                </button>
                
                <x-modal id="demo-modal" title="Demo Modal" size="md">
                    <p class="text-gray-700">This is a modal dialog component. You can use it to display forms, confirmations, or any content that needs user attention.</p>
                    <x-slot name="footer">
                        <button onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'demo-modal' } }))" class="btn-outline">
                            Cancel
                        </button>
                        <button onclick="window.dispatchEvent(new CustomEvent('close-modal', { detail: { id: 'demo-modal' } }))" class="btn-primary">
                            Confirm
                        </button>
                    </x-slot>
                </x-modal>
            </div>
        </div>

        <!-- Toast Notifications -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Toast Notifications</h3>
            </div>
            <div class="card-body">
                <div class="flex flex-wrap gap-3">
                    <button onclick="window.showToast('Operation completed successfully!', 'success')" class="btn-primary">
                        Show Success Toast
                    </button>
                    <button onclick="window.showToast('An error occurred!', 'error')" class="btn-danger">
                        Show Error Toast
                    </button>
                    <button onclick="window.showToast('Warning: Check your input', 'warning')" class="btn-secondary">
                        Show Warning Toast
                    </button>
                    <button onclick="window.showToast('Here is some information', 'info')" class="btn-outline">
                        Show Info Toast
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading States -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Loading States</h3>
            </div>
            <div class="card-body">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Small</h4>
                        <x-loading size="sm" />
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Medium</h4>
                        <x-loading size="md" />
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Large</h4>
                        <x-loading size="lg" text="Loading data..." />
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Empty State Component</h3>
            </div>
            <div class="card-body">
                <x-empty-state 
                    title="No expenses found"
                    description="Get started by creating a new expense entry."
                >
                    <x-slot name="action">
                        <button class="btn-primary">Create Expense</button>
                    </x-slot>
                </x-empty-state>
            </div>
        </div>

        <!-- Enhanced Form Components -->
        <div class="card">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Enhanced Form Components</h3>
            </div>
            <div class="card-body">
                <form class="space-y-6">
                    <!-- Input Group -->
                    <x-input-group label="Full Name" hint="Enter your full name" required>
                        <input type="text" class="form-input" placeholder="John Doe">
                    </x-input-group>

                    <!-- Select Component -->
                    <x-input-group label="Role" required>
                        <x-select name="role" :options="['admin' => 'Administrator', 'user' => 'User', 'guest' => 'Guest']" placeholder="Select a role" />
                    </x-input-group>

                    <!-- File Upload -->
                    <x-input-group label="Upload Document" hint="PDF, DOC, DOCX up to 10MB">
                        <x-file-upload accept=".pdf,.doc,.docx" />
                    </x-input-group>

                    <!-- Form with Error -->
                    <x-input-group label="Email Address" error="This email is already taken">
                        <input type="email" class="form-input border-red-300" value="test@example.com">
                    </x-input-group>

                    <div class="flex justify-end gap-3">
                        <button type="button" class="btn-outline">Cancel</button>
                        <button type="submit" class="btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <x-toast />
    
    <script>
        // Initialize toast component
        document.addEventListener('show-toast', function(event) {
            // This would typically be handled by a global toast component
            console.log('Toast:', event.detail);
        });
    </script>
@endsection
