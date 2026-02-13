@extends('admin.layouts.auth')

@section('title', 'Reset Password')

@section('content')
<main class="flex-1 flex items-center justify-center py-10 px-4 mt-[80px]">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-2xl shadow-[0_6px_20px_rgba(0,0,0,0.1)] overflow-hidden grid md:grid-cols-2">

            <!-- Left: Hero Section -->
            <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] text-white p-8 md:p-12 flex flex-col justify-center">
                <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-4">Reset Your Password</h1>
                <p class="text-lg opacity-90 mb-8 leading-relaxed">
                    Enter your new password below to regain access to your account. Make sure to choose a strong password!
                </p>
                <ul class="space-y-5 mt-4">
                    <li class="flex items-center gap-4">
                        <i class="fas fa-lock w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Set a new secure password</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-check-circle w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Confirm your new password</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-sign-in-alt w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Quickly regain access</span>
                    </li>
                </ul>
            </div>

            <!-- Right: Reset Password Form -->
            <div class="p-8 md:p-12 flex flex-col justify-center">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-[#2c5aa0] mb-2">Reset Password</h2>
                    <p class="text-gray-600">Enter your email and new password</p>
                </div>

                <form method="POST" action="{{ route('admin.password.store') }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="email" class="font-semibold text-gray-800">Email Address</label>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" value="{{ old('email', $request->email) }}"
                                class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-base"
                                placeholder="Enter your email address" required autofocus autocomplete="username">
                        </div>
                        @error('email')
                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="password" class="font-semibold text-gray-800">New Password</label>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password"
                                class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-base"
                                placeholder="Enter your new password" required autocomplete="new-password">
                        </div>
                        @error('password')
                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="password_confirmation" class="font-semibold text-gray-800">Confirm Password</label>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-base"
                                placeholder="Confirm your new password" required autocomplete="new-password">
                        </div>
                        @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-3 text-lg">
                        <i class="fas fa-sync-alt"></i>
                        Reset Password
                    </button>

                    <div class="text-center mt-6">
                        <a href="{{ route('admin.login') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection