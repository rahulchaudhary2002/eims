@extends('admin.layouts.auth')
@section('content')
<form method="POST" action="{{ route('admin.password.store') }}">
    @csrf

    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <x-text-input
        label="Email"
        id="email"
        type="email"
        name="email"
        :value="old('email', $request->email)"
        required
        autofocus
        autocomplete="username"
        placeholder="Enter your email"
        error="{{ $errors->get('email') ? $errors->first('email') : null }}" />

    <x-text-input
        label="Password"
        id="password"
        type="password"
        name="password"
        required
        autocomplete="current-password"
        placeholder="Enter your password"
        error="{{ $errors->get('password') ? $errors->first('password') : null }}" />

    <x-text-input
        label="Confirmation Password"
        id="password_confirmation"
        type="password"
        name="password_confirmation"
        required
        autocomplete="current-password"
        placeholder="Enter your password"
        error="{{ $errors->get('password_confirmation') ? $errors->first('password_confirmation') : null }}" />

    <div class="flex items-center justify-end mt-4">
        <x-primary-button>
            {{ __('Reset Password') }}
        </x-primary-button>
    </div>
</form>
@endsection