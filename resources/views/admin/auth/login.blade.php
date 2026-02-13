@extends('admin.layouts.auth')

@section('title', 'Login to Your Account')

@section('content')
<!-- Main Content - Exact Sikuna.com Auth Layout -->
<main class="flex-1 flex items-center justify-center py-10 px-4 mt-[80px]">
    <div class="container mx-auto max-w-6xl">
        <!-- Auth Container - Original grid from Sikuna.com -->
        <div class="bg-white rounded-2xl shadow-[0_6px_20px_rgba(0,0,0,0.1)] overflow-hidden grid md:grid-cols-2">

            <!-- Left: Hero Section (exact Sikuna.com style) -->
            <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] text-white p-8 md:p-12 flex flex-col justify-center">
                <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-4">Welcome Back to Sikuna.com</h1>
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

            <!-- Right: Login Form Section (exact replica) -->
            <div class="p-8 md:p-12">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-[#2c5aa0] mb-2">Login to Your Account</h2>
                    <p class="text-gray-600">Enter your credentials to access your account</p>
                </div>

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login') }}" class="auth-form active space-y-6" id="studentLoginForm">
                    @csrf
                    <!-- Email -->
                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="adminEmail" class="font-semibold text-gray-800">Email Address</label>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="adminEmail" name="username" value="{{ old('username') }}"
                                class="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-base"
                                placeholder="Enter your email address" required autofocus>
                        </div>
                        @error('username')
                        <p class="text-red-500 text-sm mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex justify-between mb-2">
                            <label for="adminPassword" class="font-semibold text-gray-800">Password</label>
                            @if (Route::has('admin.password.request'))
                            <a href="{{ route('admin.password.request') }}" class="text-sm text-[#4299e1] hover:text-[#2c5aa0] font-medium">
                                Forgot Password?
                            </a>
                            @endif
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="adminPassword" name="password"
                                class="w-full pl-12 pr-12 py-4 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-base"
                                placeholder="Enter your password" required>
                            <button type="button"
                                class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                data-target="adminPassword">
                                <i class="fas fa-eye"></i>
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

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-3 text-lg">
                        <i class="fas fa-sign-in-alt"></i>
                        Login as Admin
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

@endsection

@section('page-specific-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User type selector
        const userTypeBtns = document.querySelectorAll('.user-type-btn');
        const authForms = document.querySelectorAll('.auth-form');

        function setActiveTab(type) {
            // Update buttons
            userTypeBtns.forEach(btn => {
                btn.classList.toggle('active', btn.dataset.type === type);
                btn.classList.toggle('bg-white', btn.dataset.type === type);
                btn.classList.toggle('shadow-md', btn.dataset.type === type);
                btn.classList.toggle('text-[#2c5aa0]', btn.dataset.type === type);
                btn.classList.toggle('text-gray-500', btn.dataset.type !== type);
            });

            // Update forms
            authForms.forEach(form => {
                if (form.id === `${type}LoginForm`) {
                    form.classList.remove('hidden');
                    form.classList.add('active');
                } else {
                    form.classList.add('hidden');
                    form.classList.remove('active');
                }
            });
        }

        // Set initial active tab
        setActiveTab('student');

        // Add click handlers
        userTypeBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                setActiveTab(this.dataset.type);
            });
        });

        // Password visibility toggle
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });

        // Social login simulation
        document.querySelectorAll('.social-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const platform = this.classList.contains('google') ? 'Google' :
                    this.classList.contains('facebook') ? 'Facebook' : 'LinkedIn';
                alert(`In a real implementation, this would redirect to ${platform} authentication.`);
            });
        });

        // Remember me functionality
        const rememberStudent = document.querySelector('#studentLoginForm input[name="remember"]');
        if (rememberStudent) {
            rememberStudent.addEventListener('change', function() {
                localStorage.setItem('rememberStudent', this.checked);
            });
            if (localStorage.getItem('rememberStudent') === 'true') {
                rememberStudent.checked = true;
            }
        }

        const rememberInstitution = document.querySelector('#institutionLoginForm input[name="remember"]');
        if (rememberInstitution) {
            rememberInstitution.addEventListener('change', function() {
                localStorage.setItem('rememberInstitution', this.checked);
            });
            if (localStorage.getItem('rememberInstitution') === 'true') {
                rememberInstitution.checked = true;
            }
        }
    });
</script>
@endsection