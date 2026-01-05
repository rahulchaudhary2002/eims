@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <!-- Title -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800">Create Account 🎉</h1>
            <p class="text-sm text-gray-500 mt-1">
                Sign up to get started with {{ config('app.name') }}
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Name -->
            <x-text-input
                label="Full Name"
                id="name"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
                placeholder="Enter your full name"
                error="{{ $errors->first('name') }}" />

            <!-- Email or Phone -->
            <x-text-input
                label="Email Address"
                id="email"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="email"
                placeholder="Enter your email address"
                error="{{ $errors->first('email') }}" />

            <!-- Phone Number -->
            <x-text-input
                label="Phone Number"
                id="phone"
                type="text"
                name="phone"
                :value="old('phone')"
                required
                autocomplete="tel"
                placeholder="Enter your phone number"
                error="{{ $errors->first('phone') }}" />

            <!-- Password -->
            <x-text-input
                label="Password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                placeholder="Create a strong password"
                error="{{ $errors->first('password') }}" />

            <!-- Confirm Password -->
            <x-text-input
                label="Confirm Password"
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm your password"
                error="{{ $errors->first('password_confirmation') }}" />

            <!-- Terms & Conditions (Optional) -->
            <div class="flex items-start gap-2 text-sm">
                <input
                    id="terms"
                    type="checkbox"
                    class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                    name="terms"
                    required>
                <label for="terms" class="text-gray-600">
                    I agree to the 
                    <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Terms of Service</a> 
                    and 
                    <a href="#" class="text-indigo-600 hover:text-indigo-700 font-medium">Privacy Policy</a>
                </label>
            </div>

            <!-- Submit Button -->
            <x-primary-button class="w-full justify-center py-2.5 text-base">
                {{ __('Create Account') }}
            </x-primary-button>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-700 font-medium ml-1">
                Sign in
            </a>
        </div>
    </div>
</div>
@endsection