@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <!-- Title -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800">Reset Password 🔑</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ __('Forgot your password? No problem. Just enter your email address and we\'ll send you a reset link.') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <!-- Email Input -->
            <x-text-input
                label="Email Address"
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autofocus
                autocomplete="email"
                placeholder="Enter your email address"
                error="{{ $errors->first('email') }}" />

            <!-- Submit Button -->
            <x-primary-button class="w-full justify-center py-2.5 text-base">
                {{ __('Send Reset Link') }}
            </x-primary-button>
        </form>

        <!-- Back to Login -->
        <div class="mt-6 text-center text-sm text-gray-500">
            Remember your password?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium ml-1">
                Back to login
            </a>
        </div>
    </div>
</div>
@endsection