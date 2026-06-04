@extends('layouts.student')

@section('title', 'Settings')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <h1 class="text-2xl md:text-3xl font-bold">Account Settings</h1>
        <p class="text-white/70 text-sm mt-1">Manage your password and account preferences</p>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="student-form-shell space-y-6">

            @if(session('success'))
            <div class="student-form-info text-green-700 border-green-200 bg-green-50">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
            @endif

            {{-- Change Password --}}
            <div class="student-form-card">
                <div class="student-form-header">
                    <h2 class="student-form-title">Change Password</h2>
                    <p class="student-form-description">Keep your account secure with the same modern form presentation used across the updated student dashboard.</p>
                </div>

                <form method="POST" action="{{ route('student.settings.password.update') }}" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="student-form-label">Current Password</label>
                        <input type="password" name="current_password"
                               class="student-form-control {{ $errors->has('current_password') ? 'is-invalid' : '' }}">
                        @error('current_password')<p class="student-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="student-form-label">New Password</label>
                        <input type="password" name="password"
                               class="student-form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                        @error('password')<p class="student-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="student-form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation"
                               class="student-form-control">
                    </div>
                    <div class="student-form-actions">
                        <button type="submit"
                            class="student-form-btn-primary">
                            <i class="fas fa-lock"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Account Info --}}
            <div class="student-form-panel">
                <div class="student-form-panel-head">
                    <h2>Account Information</h2>
                </div>
                <div class="student-form-panel-body divide-y divide-gray-50 space-y-0">
                    @foreach([
                        ['Email', $student->email],
                        ['Email Verified', $student->email_verified_at ? 'Verified' : 'Not Verified'],
                        ['Account Status', $student->is_active ? 'Active' : 'Inactive'],
                        ['Member Since', $student->created_at->format('M d, Y')],
                    ] as [$label, $value])
                    <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0">
                        <span class="text-sm text-gray-500">{{ $label }}</span>
                        <span class="text-sm font-semibold
                            {{ $label === 'Account Status' ? ($student->is_active ? 'text-green-600' : 'text-red-500') : '' }}
                            {{ $label === 'Email Verified' ? ($student->email_verified_at ? 'text-green-600' : 'text-yellow-600') : 'text-gray-700' }}">
                            {{ $value }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="student-form-panel border-red-200" x-data="{ open: false }">
                <div class="px-6 py-4 border-b border-red-100">
                    <h2 class="text-base font-bold text-red-600">Delete Account</h2>
                    <p class="text-xs text-gray-400 mt-0.5">Permanently delete your account and all associated data</p>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-500 mb-4">Once your account is deleted, all resources and data will be permanently removed. This cannot be undone.</p>
                    <button @click="open = true"
                        class="inline-flex items-center gap-2 bg-red-600 text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-red-700 transition">
                        <i class="fas fa-trash"></i> Delete Account
                    </button>
                </div>
                <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
                    <div class="bg-white rounded-2xl w-full max-w-md p-6" @click.outside="open = false">
                        <h4 class="text-base font-bold text-gray-800 mb-2">Are you sure?</h4>
                        <p class="text-sm text-gray-500 mb-5">This action cannot be undone. Enter your password to confirm.</p>
                        <form method="POST" action="{{ route('student.settings.account.destroy') }}">
                            @csrf @method('DELETE')
                            <input type="password" name="password" placeholder="Enter your password"
                                   class="student-form-control mb-4">
                            @error('password', 'userDeletion')
                                <p class="student-form-error mb-3">{{ $message }}</p>
                            @enderror
                            <div class="flex gap-3 justify-end">
                                <button type="button" @click="open = false"
                                    class="student-form-btn-secondary">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2.5 text-sm font-bold bg-red-600 text-white rounded-xl hover:bg-red-700">Delete Account</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
