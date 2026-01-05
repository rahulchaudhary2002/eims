@extends('admin.layouts.auth')
@section('content')
<div class="flex justify-center items-center flex-col mb-4">
    <h1 class="text-2xl font-semibold text-center leading-8">
        Welcome Back
    </h1>
    <p class="text-gray-500 text-sm">Sign in to your administrator account</p>
</div>

<form class="w-full flex flex-col gap-4" method="POST" action="{{ route('admin.login') }}">
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
        error="{{ $errors->get('username') ? $errors->first('username') : null }}" />

    <x-text-input
        label="Password"
        id="password"
        type="password"
        name="password"
        required
        autocomplete="current-password"
        placeholder="Enter your password"
        error="{{ $errors->get('password') ? $errors->first('password') : null }}" />

    <div class="flex items-center justify-between gap-2">
        <label for="remember_me" class="inline-flex items-center cursor-pointer">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="rounded">
            <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
        </label>

        @if (Route::has('admin.password.request'))
        <a class="underline text-sm text-gray-600 hover:text-gray-900 outline-none"
            href="{{ route('admin.password.request') }}">
            {{ __('Forgot your password?') }}
        </a>
        @endif
    </div>

    <x-primary-button>
        {{ __('Log in') }}
    </x-primary-button>
</form>

<div class="mt-4 text-center text-sm text-gray-400">
    Not an administrator?
    <a class="text-blue-500 hover:underline" href="/">Redirect to Home</a>
</div>
@endsection