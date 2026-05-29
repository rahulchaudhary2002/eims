@extends('layouts.app')

@section('title', 'Student Login')

@section('content')
<main class="flex-1 flex items-center justify-center py-10 px-4 mt-[80px]">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-2xl shadow-[0_6px_20px_rgba(0,0,0,0.1)] overflow-hidden grid md:grid-cols-2">

            <!-- Left: Hero Section -->
            <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] text-white p-8 md:p-12 flex flex-col justify-center">
                <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-4">Welcome Back, Student</h1>
                <p class="text-lg opacity-90 mb-8 leading-relaxed">
                    Access your personalized dashboard to explore scholarship opportunities, connect with institutions, and manage your educational journey.
                </p>
                <ul class="space-y-5 mt-4">
                    <li class="flex items-center gap-4">
                        <i class="fas fa-award w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Track your scholarship applications</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-comments w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Participate in student forums</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-bell w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Get personalized recommendations</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-university w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Connect with educational institutions</span>
                    </li>
                </ul>
            </div>

            <!-- Right: Student Login Form -->
            <div class="p-8 md:p-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-[#2c5aa0] mb-2">Student Login</h2>
                    <p class="text-gray-600">Enter your credentials to access your student account</p>
                </div>

                <form method="POST" action="{{ route('student.login') }}" class="space-y-6">
                    @csrf

                    <!-- Email / Phone -->
                    <div>
                        <label for="username" class="block font-semibold text-gray-800 mb-2">Email or Phone Number</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="text" id="username" name="username" value="{{ old('username') }}"
                                class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-base"
                                placeholder="Enter your email or phone number" required autofocus autocomplete="username">
                        </div>
                        @error('username')
                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="password" class="font-semibold text-gray-800">Password</label>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password"
                                class="w-full pl-12 pr-12 py-4 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-base"
                                placeholder="Enter your password" required autocomplete="current-password">
                            <button type="button"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                id="togglePassword">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                        @error('password')
                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <label class="flex items-center gap-3 text-gray-700">
                            <input type="checkbox" name="remember"
                                class="w-5 h-5 rounded border-gray-300 text-[#4299e1] focus:ring-[#4299e1]/30 focus:ring-offset-0"
                                {{ old('remember') ? 'checked' : '' }}>
                            <span class="text-base">Remember me</span>
                        </label>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-3 text-lg">
                        <i class="fas fa-sign-in-alt"></i>
                        Login
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-500">
                    <a href="{{ route('admin.login') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-medium">
                        Institution / Admin Login &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('page-specific-script')
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const input = document.getElementById('password');
        const icon = document.getElementById('togglePasswordIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    });
</script>
@endsection

