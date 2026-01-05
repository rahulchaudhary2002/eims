@extends('admin.layouts.auth')
@section('content')
<div class="mb-4 text-sm text-gray-600">
    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
</div>

<form class="w-full flex flex-col gap-4" method="POST" action="{{ route('admin.password.confirm') }}">
    @csrf

    <x-text-input
        label="Password"
        id="password"
        type="password"
        name="password"
        required
        autocomplete="current-password"
        placeholder="Enter your password"
        error="{{ $errors->get('password') ? $errors->first('password') : null }}" />

    <div class="flex justify-end">
        <x-primary-button>
            {{ __('Confirm') }}
        </x-primary-button>
    </div>
</form>
@endsection