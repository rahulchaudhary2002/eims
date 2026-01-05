@extends('layouts.app')

@section('title', 'Confirm Password')

@section('content')
<div class="flex items-center justify-center min-h-[calc(100vh-200px)]">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <!-- Title -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800">Confirm Password 🔒</h1>
            <p class="text-sm text-gray-500 mt-1">
                {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
            @csrf

            <!-- Password Input -->
            <x-text-input
                label="Password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
                error="{{ $errors->first('password') }}" />

            <!-- Submit Button -->
            <x-primary-button class="w-full justify-center py-2.5 text-base">
                {{ __('Confirm Password') }}
            </x-primary-button>
        </form>

        <!-- Optional: Forgot Password Link -->
        @if (Route::has('password.request'))
        <div class="mt-6 text-center text-sm">
            <a href="{{ route('password.request') }}" class="text-indigo-600 hover:text-indigo-700 font-medium">
                {{ __('Forgot your password?') }}
            </a>
        </div>
        @endif
    </div>
</div>
@endsection