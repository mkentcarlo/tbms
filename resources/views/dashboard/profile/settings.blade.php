@extends('layouts.modern')

@section('title', 'System Settings')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li class="text-gray-500">Settings</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">System Settings</h1>
            <p class="mt-1 text-sm text-gray-500">Customize your application appearance and branding</p>
        </div>
    </div>
@endsection

@section('content')
    <!-- Hidden form for logo deletion -->
    @if($app_logo)
    <form id="delete-logo-form" action="{{ route('settings.logo.remove') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif

    <!-- Hidden form for background deletion -->
    @if($login_background)
    <form id="delete-background-form" action="{{ route('settings.background.remove') }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- App Branding Settings -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-soft overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Application Branding</h2>
                    <p class="text-sm text-gray-500">Customize how your application looks to users</p>
                </div>

                <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="p-6 space-y-6">
                        <!-- App Name -->
                        <div>
                            <label for="app_name" class="block text-sm font-medium text-gray-700 mb-2">Application Name</label>
                            <input 
                                type="text" 
                                id="app_name" 
                                name="app_name" 
                                value="{{ old('app_name', $app_name) }}"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('app_name') border-red-300 @enderror"
                                placeholder="Enter application name"
                            >
                            @error('app_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- App Description -->
                        <div>
                            <label for="app_description" class="block text-sm font-medium text-gray-700 mb-2">Application Description</label>
                            <textarea 
                                id="app_description" 
                                name="app_description" 
                                rows="3"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all @error('app_description') border-red-300 @enderror"
                                placeholder="Brief description of your application"
                            >{{ old('app_description', $app_description) }}</textarea>
                            @error('app_description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">This will be shown on the login page</p>
                        </div>

                        <!-- App Logo -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Application Logo</label>
                            
                            <div class="flex items-start gap-6">
                                <!-- Current Logo Preview -->
                                <div class="flex-shrink-0">
                                    @if($app_logo)
                                        <div class="relative group">
                                            <img src="{{ asset($app_logo) }}" alt="Current Logo" class="h-24 w-24 object-contain rounded-lg border border-gray-200 bg-gray-50 p-2">
                                            <button type="button" onclick="document.getElementById('delete-logo-form').submit();" class="absolute -top-2 -right-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors shadow-lg opacity-0 group-hover:opacity-100">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="h-24 w-24 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                                            <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <!-- Upload Area -->
                                <div class="flex-1">
                                    <div x-data="{ isDragging: false, fileName: '' }" 
                                         class="relative border-2 border-dashed rounded-lg p-6 transition-colors"
                                         :class="isDragging ? 'border-primary-500 bg-primary-50' : 'border-gray-300 hover:border-gray-400'"
                                         @dragover.prevent="isDragging = true"
                                         @dragleave.prevent="isDragging = false"
                                         @drop.prevent="isDragging = false; fileName = $event.dataTransfer.files[0]?.name || ''; $refs.fileInput.files = $event.dataTransfer.files">
                                        
                                        <input 
                                            type="file" 
                                            id="app_logo" 
                                            name="app_logo" 
                                            accept="image/*"
                                            x-ref="fileInput"
                                            @change="fileName = $event.target.files[0]?.name || ''"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        >
                                        
                                        <div class="text-center">
                                            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">
                                                <span class="font-medium text-primary-600">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, GIF, SVG up to 2MB</p>
                                            <p x-show="fileName" x-text="'Selected: ' + fileName" class="mt-2 text-sm text-primary-600 font-medium"></p>
                                        </div>
                                    </div>
                                    @error('app_logo')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Login Background -->
                        <div class="pt-6 border-t border-gray-200">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Login Page Background</label>
                            <p class="text-sm text-gray-500 mb-4">Upload a custom background image for the login page. Recommended size: 1920x1080px or larger.</p>
                            
                            <div class="flex items-start gap-6">
                                <!-- Current Background Preview -->
                                <div class="flex-shrink-0">
                                    @if($login_background)
                                        <div class="relative group">
                                            <img src="{{ asset($login_background) }}" alt="Current Background" class="h-32 w-48 object-cover rounded-lg border border-gray-200">
                                            <button type="button" onclick="document.getElementById('delete-background-form').submit();" class="absolute -top-2 -right-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors shadow-lg opacity-0 group-hover:opacity-100">
                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="h-32 w-48 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center bg-gradient-to-br from-primary-800 via-primary-600 to-primary-400">
                                            <span class="text-white text-xs font-medium">Default Gradient</span>
                                        </div>
                                    @endif
                                </div>

                                <!-- Upload Area -->
                                <div class="flex-1">
                                    <div x-data="{ isDragging: false, fileName: '' }" 
                                         class="relative border-2 border-dashed rounded-lg p-6 transition-colors"
                                         :class="isDragging ? 'border-primary-500 bg-primary-50' : 'border-gray-300 hover:border-gray-400'"
                                         @dragover.prevent="isDragging = true"
                                         @dragleave.prevent="isDragging = false"
                                         @drop.prevent="isDragging = false; fileName = $event.dataTransfer.files[0]?.name || ''; $refs.bgInput.files = $event.dataTransfer.files">
                                        
                                        <input 
                                            type="file" 
                                            id="login_background" 
                                            name="login_background" 
                                            accept="image/jpeg,image/png,image/jpg,image/webp"
                                            x-ref="bgInput"
                                            @change="fileName = $event.target.files[0]?.name || ''"
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                        >
                                        
                                        <div class="text-center">
                                            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            <p class="mt-2 text-sm text-gray-600">
                                                <span class="font-medium text-primary-600">Click to upload</span> or drag and drop
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP up to 5MB</p>
                                            <p x-show="fileName" x-text="'Selected: ' + fileName" class="mt-2 text-sm text-primary-600 font-medium"></p>
                                        </div>
                                    </div>
                                    @error('login_background')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Print Document Settings -->
                        <div class="pt-6 border-t border-gray-200">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="p-2 bg-amber-100 rounded-lg">
                                    <svg class="h-5 w-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Print Document Settings</h3>
                                    <p class="text-xs text-gray-500">Customize the header and signatories on printed expense documents</p>
                                </div>
                            </div>

                            <!-- Government Header Section -->
                            <div class="bg-gray-50 rounded-lg p-4 mb-4">
                                <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-3">Document Header</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="country_name" class="block text-xs font-medium text-gray-600 mb-1">Country</label>
                                        <input 
                                            type="text" 
                                            id="country_name" 
                                            name="country_name" 
                                            value="{{ old('country_name', $country_name) }}"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                            placeholder="Republic of the Philippines"
                                        >
                                    </div>
                                    <div>
                                        <label for="province_name" class="block text-xs font-medium text-gray-600 mb-1">Province</label>
                                        <input 
                                            type="text" 
                                            id="province_name" 
                                            name="province_name" 
                                            value="{{ old('province_name', $province_name) }}"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                            placeholder="PROVINCE OF AGUSAN DEL SUR"
                                        >
                                    </div>
                                    <div>
                                        <label for="municipality_name" class="block text-xs font-medium text-gray-600 mb-1">Municipality/City</label>
                                        <input 
                                            type="text" 
                                            id="municipality_name" 
                                            name="municipality_name" 
                                            value="{{ old('municipality_name', $municipality_name) }}"
                                            class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                            placeholder="Municipality of Talacogon"
                                        >
                                    </div>
                                </div>
                            </div>

                            <!-- Signatories Section -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-xs font-semibold text-gray-700 uppercase tracking-wider mb-3">Document Signatories</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Certifying Officer -->
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-2">
                                            <div class="h-6 w-6 rounded-full bg-blue-100 flex items-center justify-center">
                                                <span class="text-xs font-bold text-blue-600">1</span>
                                            </div>
                                            <span class="text-xs font-medium text-gray-700">Certifying Officer</span>
                                        </div>
                                        <div>
                                            <label for="certifying_officer_name" class="block text-xs font-medium text-gray-600 mb-1">Name</label>
                                            <input 
                                                type="text" 
                                                id="certifying_officer_name" 
                                                name="certifying_officer_name" 
                                                value="{{ old('certifying_officer_name', $certifying_officer_name) }}"
                                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                                placeholder="MARILOU P. AZUCENA,MM"
                                            >
                                        </div>
                                        <div>
                                            <label for="certifying_officer_title" class="block text-xs font-medium text-gray-600 mb-1">Position/Title</label>
                                            <input 
                                                type="text" 
                                                id="certifying_officer_title" 
                                                name="certifying_officer_title" 
                                                value="{{ old('certifying_officer_title', $certifying_officer_title) }}"
                                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                                placeholder="Budget Officer III"
                                            >
                                        </div>
                                    </div>

                                    <!-- Budget Officer -->
                                    <div class="space-y-3">
                                        <div class="flex items-center gap-2">
                                            <div class="h-6 w-6 rounded-full bg-green-100 flex items-center justify-center">
                                                <span class="text-xs font-bold text-green-600">2</span>
                                            </div>
                                            <span class="text-xs font-medium text-gray-700">Budget Officer</span>
                                        </div>
                                        <div>
                                            <label for="budget_officer_name" class="block text-xs font-medium text-gray-600 mb-1">Name</label>
                                            <input 
                                                type="text" 
                                                id="budget_officer_name" 
                                                name="budget_officer_name" 
                                                value="{{ old('budget_officer_name', $budget_officer_name) }}"
                                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                                placeholder="GWENDOLYN A. CLAROS, REB,MEM"
                                            >
                                        </div>
                                        <div>
                                            <label for="budget_officer_title" class="block text-xs font-medium text-gray-600 mb-1">Position/Title</label>
                                            <input 
                                                type="text" 
                                                id="budget_officer_title" 
                                                name="budget_officer_title" 
                                                value="{{ old('budget_officer_title', $budget_officer_title) }}"
                                                class="block w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                                placeholder="Municipal Budget Officer"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="btn-primary inline-flex items-center gap-2">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Preview Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-soft overflow-hidden sticky top-24">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Preview</h2>
                    <p class="text-sm text-gray-500">How it will look on login page</p>
                </div>
                
                <div class="p-6">
                    <div class="bg-gradient-to-br from-primary-800 via-primary-600 to-primary-400 rounded-xl p-6 text-center text-white">
                        @if($app_logo)
                            <img src="{{ asset($app_logo) }}" alt="Logo Preview" class="h-16 w-auto mx-auto mb-4 drop-shadow-lg">
                        @else
                            <div class="h-16 w-16 mx-auto mb-4 rounded-xl bg-white/20 flex items-center justify-center">
                                <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                        @endif
                        <h3 class="text-xl font-bold">{{ $app_name }}</h3>
                        <p class="text-sm text-blue-100 mt-1">{{ $app_description }}</p>
                    </div>
                </div>

                <!-- Info -->
                <div class="px-6 py-4 bg-blue-50 border-t border-blue-100">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="text-sm text-blue-700">
                            <p class="font-medium">Logo Tips</p>
                            <ul class="mt-1 list-disc list-inside space-y-1 text-blue-600">
                                <li>Use a transparent PNG for best results</li>
                                <li>Square or horizontal logos work best</li>
                                <li>Recommended size: 200x200px or larger</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
