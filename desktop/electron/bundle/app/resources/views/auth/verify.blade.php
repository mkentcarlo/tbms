@extends('layouts.auth')

@section('title', 'Verify Email')

@section('header')
    <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900">Verify your email address</h2>
    </div>
@endsection

@section('content')
    <div class="space-y-6">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 mb-4">
                <svg class="h-6 w-6 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            
            <p class="text-sm text-gray-600">
                {{ __('Before proceeding, please check your email for a verification link.') }}
            </p>
        </div>

        @if (session('resent'))
            <div class="rounded-lg bg-green-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="text-center text-sm text-gray-600">
            <p>
                {{ __('If you did not receive the email') }},
            </p>
            <form class="mt-2 inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <button type="submit" class="font-medium text-primary-600 hover:text-primary-500">
                    {{ __('click here to request another') }}
                </button>.
            </form>
        </div>
    </div>
@endsection
