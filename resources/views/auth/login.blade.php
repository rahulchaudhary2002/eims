@extends('layouts.app')

@section('title', 'Login')

@section('content')
<main class="pt-[150px] pb-20 px-4 bg-gradient-to-br from-[#2c5aa0]/10 to-[#1a365d]/5">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden grid md:grid-cols-2">

            <!-- Left: Hero -->
            <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] text-white p-8 md:p-12 flex flex-col justify-center">
                <h1 class="text-[2.4rem] md:text-[3.2rem] font-bold leading-[1.15] mb-5" id="hero-title">Welcome Back</h1>
                <p class="text-[1.05rem] md:text-[1.1rem] opacity-90 mb-8 leading-relaxed" id="hero-desc">
                    Access your personalised dashboard to explore scholarship opportunities, connect with institutions, and manage your educational journey.
                </p>
                <ul class="space-y-5 mt-4 text-[1rem]" id="hero-student-items">
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
                        <span>Get personalised recommendations</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-university w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Connect with educational institutions</span>
                    </li>
                </ul>
                <ul class="space-y-5 mt-4 hidden text-[1rem]" id="hero-institution-items">
                    <li class="flex items-center gap-4">
                        <i class="fas fa-university w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Manage your institution profile</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-users w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Review and accept student applications</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-chart-line w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Track admissions and analytics</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-graduation-cap w-10 h-10 flex items-center justify-center bg-white/10 rounded-full text-[#4299e1]"></i>
                        <span>Manage programs and scholarships</span>
                    </li>
                </ul>
            </div>

            <!-- Right: Forms -->
            <div class="p-8 md:p-12">
                <div class="text-center mb-7">
                    <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Sign In</h2>
                    <p class="text-gray-600 text-[0.95rem]">Choose your account type to continue</p>
                </div>

                {{-- Tab switcher --}}
                <div class="flex rounded-xl bg-gray-100 p-1 mb-7">
                    <button type="button" id="tab-student"
                        class="auth-tab flex-1 py-3 px-3 rounded-xl font-semibold text-base transition-all duration-200 flex items-center justify-center gap-2 bg-white shadow text-[#2c5aa0]"
                        data-target="form-student">
                        <i class="fas fa-user-graduate"></i> Student
                    </button>
                    <button type="button" id="tab-institution"
                        class="auth-tab flex-1 py-3 px-3 rounded-xl font-semibold text-base transition-all duration-200 flex items-center justify-center gap-2 text-gray-500"
                        data-target="form-institution">
                        <i class="fas fa-university"></i> Institution
                    </button>
                </div>

                {{-- Student Login --}}
                <div id="form-student" class="auth-form">
                    @if ($errors->any() && old('_form') !== 'institution')
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('student.login') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_form" value="student">

                        <div>
                            <label for="username" class="block font-semibold text-gray-800 mb-2 text-[0.95rem]">Email or Phone Number</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope"></i></span>
                                <input type="text" id="username" name="username" value="{{ old('username') }}"
                                    class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="Email or phone number" required autofocus autocomplete="username">
                            </div>
                            @error('username')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="block font-semibold text-gray-800 mb-2 text-[0.95rem]">Password</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock"></i></span>
                                <input type="password" id="password" name="password"
                                    class="w-full pl-12 pr-12 py-3.5 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="Enter your password" required autocomplete="current-password">
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center gap-3 text-gray-700 text-sm cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#4299e1]" {{ old('remember') ? 'checked' : '' }}>
                                Remember me
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-sign-in-alt"></i> Sign In as Student
                        </button>
                    </form>

                    <p class="mt-5 text-center text-sm text-gray-500">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold ml-1">Register here</a>
                    </p>
                </div>

                {{-- Institution Login --}}
                <div id="form-institution" class="auth-form hidden">
                    @if ($errors->any() && old('_form') === 'institution')
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                        @csrf
                        <input type="hidden" name="_form" value="institution">

                        <div>
                            <label for="inst_email" class="block font-semibold text-gray-800 mb-2 text-[0.95rem]">Email Address</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope"></i></span>
                                <input type="email" id="inst_email" name="email" value="{{ old('email') }}"
                                    class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="institution@example.com" required autocomplete="username">
                            </div>
                        </div>

                        <div>
                            <label for="inst_password" class="block font-semibold text-gray-800 mb-2 text-[0.95rem]">Password</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock"></i></span>
                                <input type="password" id="inst_password" name="password"
                                    class="w-full pl-12 pr-12 py-3.5 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="Enter your password" required autocomplete="current-password">
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="inst_password">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <label class="flex items-center gap-3 text-gray-700 text-sm cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-[#4299e1]" {{ old('remember') ? 'checked' : '' }}>
                                Remember me
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2">
                            <i class="fas fa-university"></i> Sign In as Institution
                        </button>
                    </form>

                    <p class="mt-5 text-center text-sm text-gray-500">
                        New institution?
                        <a href="{{ route('register') }}?tab=institution" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold ml-1">Register here</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection

@section('page-specific-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs   = document.querySelectorAll('.auth-tab');
    const forms  = document.querySelectorAll('.auth-form');
    const heroStudent     = document.getElementById('hero-student-items');
    const heroInstitution = document.getElementById('hero-institution-items');
    const heroTitle       = document.getElementById('hero-title');
    const heroDesc        = document.getElementById('hero-desc');

    function activate(targetId) {
        tabs.forEach(t => {
            const active = t.dataset.target === targetId;
            t.classList.toggle('bg-white', active);
            t.classList.toggle('shadow', active);
            t.classList.toggle('text-[#2c5aa0]', active);
            t.classList.toggle('text-gray-500', !active);
        });
        forms.forEach(f => f.classList.toggle('hidden', f.id !== targetId));

        const isInstitution = targetId === 'form-institution';
        heroStudent.classList.toggle('hidden', isInstitution);
        heroInstitution.classList.toggle('hidden', !isInstitution);
        heroTitle.textContent = isInstitution ? 'Institution Portal' : 'Welcome Back';
        heroDesc.textContent  = isInstitution
            ? 'Access your institution dashboard to manage admissions, programs, and student applications.'
            : 'Access your personalised dashboard to explore scholarship opportunities and manage your educational journey.';
    }

    tabs.forEach(tab => tab.addEventListener('click', () => activate(tab.dataset.target)));

    // Auto-activate institution tab if redirected from institution register link or on validation failure
    const params = new URLSearchParams(window.location.search);
    if (params.get('tab') === 'institution' || "{{ old('_form') }}" === 'institution') {
        activate('form-institution');
    }

    // Password toggle
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const icon  = this.querySelector('i');
            input.type  = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye', input.type === 'password');
            icon.classList.toggle('fa-eye-slash', input.type !== 'password');
        });
    });
});
</script>
@endsection
