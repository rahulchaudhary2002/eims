@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <!-- Title -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800">Verify Your Email 📧</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ __('Thanks for signing up! Before getting started, please verify your email address.') }}
            </p>
        </div>

        <!-- Main Message -->
        <div class="mb-6 text-sm text-gray-600 bg-blue-50 p-4 rounded-lg">
            <p class="mb-3">
                {{ __('We\'ve sent a verification link to the email address you provided during registration.') }}
            </p>
            <p>
                {{ __('If you didn\'t receive the email, click the button below to request another one.') }}
            </p>
        </div>

        <!-- Success Message -->
        @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm font-medium text-green-700">
                    {{ __('A new verification link has been sent to your email address.') }}
                </p>
            </div>
        </div>
        @endif

        <div class="space-y-5">
            <!-- Resend Verification Form -->
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <x-primary-button class="w-full justify-center py-2.5 text-base">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <!-- Logout Form -->
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>

        <!-- Additional Info -->
        <div class="mt-6 pt-6 border-t border-gray-200">
            <div class="text-center text-sm text-gray-500">
                <p class="mb-2">Didn't receive the email?</p>
                <ul class="list-disc list-inside text-left mx-auto max-w-xs space-y-1">
                    <li>Check your spam or junk folder</li>
                    <li>Make sure the email address is correct</li>
                    <li>Wait a few minutes and try again</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection