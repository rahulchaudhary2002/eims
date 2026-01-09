@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-7xl mx-auto lg:grid lg:grid-cols-4 gap-6 px-4 sm:px-6 lg:px-8">
    @include('includes.sidebar')

    <!-- Main Content -->
    <div class="lg:col-span-3 flex flex-col gap-6">

        <!-- Profile Information -->
        <section id="profile" class="bg-white shadow rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Profile Information</h3>
            <div class="max-w-xl">
                @include('modules.profile.partials.update-profile-information-form')
            </div>
        </section>

        <!-- Password Update -->
        <section class="bg-white shadow rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Update Password</h3>
            <div class="max-w-xl">
                @include('modules.profile.partials.update-password-form')
            </div>
        </section>

        <!-- Delete Account -->
        <section class="bg-white shadow rounded-2xl p-6 sm:p-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 text-red-600">Delete Account</h3>
            <p class="text-sm text-gray-500 mb-4">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please be certain.
            </p>
            <div class="max-w-xl">
                @include('modules.profile.partials.delete-user-form')
            </div>
        </section>

    </div>
</div>
@endsection