@extends('layouts.auth')

@section('title', 'Reset Password')

@section('header')
    <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900">Reset your password</h2>
        <p class="mt-2 text-sm text-gray-600">
            Enter your email address and we'll send you a password reset link.
        </p>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="form-label">Email address</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="email"
                    class="form-input pl-10 @error('email') border-red-300 focus:border-red-500 focus:ring-red-500 @enderror" 
                    placeholder="you@example.com"
                >
            </div>
            @error('email')
                <p class="form-error">{{ $message }}</p>
            @enderror
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full btn-primary">
                Send password reset link
            </button>
        </div>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm font-medium text-primary-600 hover:text-primary-500">
                Back to login
            </a>
        </div>
    </form>
@endsection
