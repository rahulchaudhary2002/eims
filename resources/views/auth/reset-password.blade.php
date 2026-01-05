@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <!-- Title -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800">Set New Password 🔒</h1>
            <p class="text-sm text-gray-500 mt-1">
                Create a new secure password for your account
            </p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email (pre-filled, read-only) -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address
                </label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email', $request->email) }}"
                    required
                    readonly
                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <p class="mt-1 text-xs text-gray-400">Your email address (cannot be changed)</p>
                @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- New Password -->
            <x-text-input
                label="New Password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Enter your new password"
                error="{{ $errors->first('password') }}" />

            <!-- Confirm Password -->
            <x-text-input
                label="Confirm New Password"
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm your new password"
                error="{{ $errors->first('password_confirmation') }}" />

            <!-- Password Requirements (Optional) -->
            <div class="bg-gray-50 p-3 rounded-lg text-sm text-gray-600">
                <p class="font-medium mb-1">Password requirements:</p>
                <ul class="list-disc list-inside space-y-0.5">
                    <li>Minimum 8 characters</li>
                    <li>Include uppercase & lowercase letters</li>
                    <li>Include at least one number</li>
                    <li>Include a special character</li>
                </ul>
            </div>

            <!-- Submit Button -->
            <x-primary-button class="w-full justify-center py-2.5 text-base">
                {{ __('Update Password') }}
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