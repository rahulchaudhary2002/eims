@extends('admin.layouts.auth')
@section('content')
<div class="mb-4 text-sm text-gray-600">
    {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
</div>

<!-- Session Status -->
<x-auth-session-status class="mb-4" :status="session('status')" />

<form class="w-full flex flex-col gap-4" method="POST" action="{{ route('admin.password.email') }}">
    @csrf

    <x-text-input
        label="Email"
        id="email"
        type="email"
        name="email"
        :value="old('email')"
        required
        autofocus
        autocomplete="username"
        placeholder="Enter your email"
        error="{{ $errors->get('email') ? $errors->first('email') : null }}" />

    <div class="flex items-center justify-end">
        <x-primary-button>
            {{ __('Email Password Reset Link') }}
        </x-primary-button>
    </div>
</form>
@endsection