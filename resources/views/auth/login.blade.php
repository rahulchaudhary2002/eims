@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="flex items-center justify-center">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">

        <!-- Title -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-gray-800">Welcome Back 👋</h1>
            <p class="text-sm text-gray-500 mt-1">
                Sign in to continue to {{ config('app.name') }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <x-text-input
                label="Email or Phone Number"
                id="username"
                type="text"
                name="username"
                :value="old('username')"
                required
                autofocus
                autocomplete="username"
                placeholder="Enter your email or phone number"
                error="{{ $errors->first('username') }}" />

            <x-text-input
                label="Password"
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="Enter your password"
                error="{{ $errors->first('password') }}" />

            <!-- Remember + Forgot -->
            <div class="flex items-center justify-between">
                <label for="remember_me" class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        name="remember">
                    Remember me
                </label>

                @if (Route::has('password.request'))
                <a
                    href="{{ route('password.request') }}"
                    class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                    Forgot password?
                </a>
                @endif
            </div>

            <!-- Submit -->
            <x-primary-button class="w-full justify-center py-2.5 text-base">
                {{ __('Log in') }}
            </x-primary-button>
        </form>

        <!-- Footer -->
        <div class="mt-6 text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-700 font-medium ml-1">
                Sign up
            </a>
        </div>
    </div>
</div>
@endsection