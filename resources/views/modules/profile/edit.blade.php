@extends('website.layouts.app')

@section('meta-title', 'My Profile — ' . config('app.name'))

@section('content')
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="mt-6 max-w-2xl">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                <i class="fas fa-user text-[#4299e1]"></i>
                Account Settings
            </span>
            <h1 class="text-[2.6rem] md:text-[3.2rem] font-bold leading-[1.15] mb-4">My Profile</h1>
            <p class="text-white/80 text-[1.05rem]">Manage your personal information, academic interests, and account security.</p>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="space-y-8">

                @if (session('status') === 'profile-updated')
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Profile information updated successfully.
                    </div>
                @endif
                @if (session('status') === 'profile-extended-updated')
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> Additional details updated successfully.
                    </div>
                @endif

                {{-- Basic Info --}}
                <div id="profile" class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                    <div class="mb-7">
                        <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[60px] after:h-[3px] after:bg-[#4299e1]">Basic Information</h2>
                        <p class="text-gray-600 text-[0.95rem]">Your name, contact details, and profile photo.</p>
                    </div>
                    @include('modules.profile.partials.update-profile-information-form')
                </div>

                {{-- Additional Details --}}
                <div id="additional" class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                    <div class="mb-7">
                        <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[60px] after:h-[3px] after:bg-[#4299e1]">Additional Details</h2>
                        <p class="text-gray-600 text-[0.95rem]">Guardian info, location, budget, and academic interests.</p>
                    </div>
                    @include('modules.profile.partials.update-student-profile-form')
                </div>

                {{-- Password --}}
                <div id="password" class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                    <div class="mb-7">
                        <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[60px] after:h-[3px] after:bg-[#4299e1]">Update Password</h2>
                        <p class="text-gray-600 text-[0.95rem]">Use a long, random password to keep your account secure.</p>
                    </div>
                    @include('modules.profile.partials.update-password-form')
                </div>

                {{-- Delete Account --}}
                <div id="delete" class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-red-100 p-6 md:p-8">
                    <div class="mb-7">
                        <h2 class="relative inline-block text-[2rem] font-bold text-red-600 mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[60px] after:h-[3px] after:bg-red-400">Delete Account</h2>
                        <p class="text-gray-600 text-[0.95rem]">Once deleted, all your data will be permanently removed. This cannot be undone.</p>
                    </div>
                    @include('modules.profile.partials.delete-user-form')
                </div>

        </div>
    </div>
</section>
@endsection
