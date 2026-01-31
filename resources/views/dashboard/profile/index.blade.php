@extends('layouts.modern')

@section('title', 'Your Profile')

@section('breadcrumbs')
    <li><a href="{{ route('dashboard.index') }}">Home</a></li>
    <li class="text-gray-500">Profile</li>
@endsection

@section('page-header')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Your Profile</h1>
            <p class="mt-1 text-sm text-gray-500">Manage your account information and password</p>
        </div>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Profile Information -->
        <div class="card">
            <div class="card-body">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Profile Information</h3>
                    <p class="mt-1 text-sm text-gray-500">Update your account's profile information and email address.</p>
                </div>

                <!-- Profile Picture Section -->
                <div class="flex items-center gap-6 mb-8 pb-6 border-b border-gray-200">
                    <div class="relative group">
                        @if($user->profile_picture)
                            <img src="{{ asset($user->profile_picture) }}" alt="{{ $user->name }}" class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg">
                        @else
                            <div class="h-24 w-24 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0 border-4 border-white shadow-lg">
                                <span class="text-primary-600 font-bold text-3xl">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        
                        <!-- Overlay on hover -->
                        <div class="absolute inset-0 rounded-full bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('profile_picture_input').click()">
                            <svg class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <h4 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400 mt-1">Member since {{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('F Y') : 'N/A' }}</p>
                        
                        <div class="flex items-center gap-2 mt-3">
                            <form action="{{ route('profile.picture') }}" method="POST" enctype="multipart/form-data" id="picture_form">
                                @csrf
                                <input type="file" name="profile_picture" id="profile_picture_input" accept="image/*" class="hidden" onchange="document.getElementById('picture_form').submit()">
                                <button type="button" onclick="document.getElementById('profile_picture_input').click()" class="btn-secondary btn-sm inline-flex items-center gap-1">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    Change Photo
                                </button>
                            </form>
                            @if($user->profile_picture)
                                <form action="{{ route('profile.picture.remove') }}" method="POST" onsubmit="return confirm('Are you sure you want to remove your profile picture?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm inline-flex items-center gap-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Remove
                                    </button>
                                </form>
                            @endif
                        </div>
                        @error('profile_picture')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-400 mt-2">Allowed formats: JPG, PNG, GIF. Max size: 2MB</p>
                    </div>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div>
                            <label for="name" class="form-label">Name</label>
                            <input 
                                type="text" 
                                id="name"
                                name="name" 
                                class="form-input @error('name') border-red-500 @enderror" 
                                value="{{ old('name', $user->name) }}"
                                required
                            />
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="form-label">Email Address</label>
                            <input 
                                type="email" 
                                id="email"
                                name="email" 
                                class="form-input @error('email') border-red-500 @enderror" 
                                value="{{ old('email', $user->email) }}"
                                required
                            />
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Update Password -->
        <div class="card">
            <div class="card-body">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Update Password</h3>
                    <p class="mt-1 text-sm text-gray-500">Ensure your account is using a long, random password to stay secure.</p>
                </div>

                <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Current Password -->
                        <div>
                            <label for="current_password" class="form-label">Current Password</label>
                            <input 
                                type="password" 
                                id="current_password"
                                name="current_password" 
                                class="form-input @error('current_password') border-red-500 @enderror"
                                required
                            />
                            @error('current_password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="password" class="form-label">New Password</label>
                            <input 
                                type="password" 
                                id="password"
                                name="password" 
                                class="form-input @error('password') border-red-500 @enderror"
                                required
                            />
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input 
                                type="password" 
                                id="password_confirmation"
                                name="password_confirmation" 
                                class="form-input"
                                required
                            />
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Account Information -->
        <div class="card">
            <div class="card-body">
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900">Account Information</h3>
                    <p class="mt-1 text-sm text-gray-500">View your account details and role information.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Role</p>
                                <p class="text-lg font-semibold text-gray-900">{{ ucfirst($user->menuroles ?? 'User') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Account Created</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Last Updated</p>
                                <p class="text-lg font-semibold text-gray-900">{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="flex-shrink-0">
                                <svg class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">User ID</p>
                                <p class="text-lg font-semibold text-gray-900">#{{ $user->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
