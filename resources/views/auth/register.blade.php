@extends('layouts.app')

@section('title', 'Register')

@section('content')
<main class="pt-[150px] pb-20 px-4 bg-gradient-to-br from-[#2c5aa0]/10 to-[#1a365d]/5">
    <div class="container mx-auto max-w-6xl">
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden grid md:grid-cols-2">

            <!-- Left: Hero -->
            <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] text-white p-8 md:p-12 flex flex-col justify-center">
                <h1 class="text-[2.4rem] md:text-[3.2rem] font-bold leading-[1.15] mb-5" id="reg-hero-title">Join Our Community</h1>
                <p class="text-[1.05rem] md:text-[1.1rem] opacity-90 mb-8 leading-relaxed" id="reg-hero-desc">
                    Create your account to unlock personalised scholarship recommendations, connect with educational institutions, and access exclusive resources.
                </p>
                <ul class="space-y-5 mt-4 text-[1rem]" id="reg-hero-student">
                    <li class="flex items-center gap-4"><i class="fas fa-search bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"></i><span>Find scholarships matching your profile</span></li>
                    <li class="flex items-center gap-4"><i class="fas fa-bell bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"></i><span>Get notified about new opportunities</span></li>
                    <li class="flex items-center gap-4"><i class="fas fa-chart-line bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"></i><span>Track your application progress</span></li>
                    <li class="flex items-center gap-4"><i class="fas fa-users bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"></i><span>Connect with mentors and peers</span></li>
                </ul>
                <ul class="space-y-5 mt-4 hidden text-[1rem]" id="reg-hero-institution">
                    <li class="flex items-center gap-4"><i class="fas fa-university bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"></i><span>List your institution and programs</span></li>
                    <li class="flex items-center gap-4"><i class="fas fa-user-graduate bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"></i><span>Receive verified student applications</span></li>
                    <li class="flex items-center gap-4"><i class="fas fa-award bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"></i><span>Publish scholarships and promotions</span></li>
                    <li class="flex items-center gap-4"><i class="fas fa-chart-bar bg-white/20 w-10 h-10 rounded-full flex items-center justify-center"></i><span>Access admissions analytics</span></li>
                </ul>
            </div>

            <!-- Right: Forms -->
            <div class="p-8 md:p-12 max-h-[860px] overflow-y-auto">
                <div class="text-center mb-7">
                    <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-1/2 after:-translate-x-1/2 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Create Account</h2>
                    <p class="text-gray-600 text-[0.95rem]">Choose your account type</p>
                </div>

                {{-- Tab switcher --}}
                <div class="flex rounded-xl bg-gray-100 p-1 mb-7">
                    <button type="button" id="reg-tab-student"
                        class="reg-tab flex-1 py-3 px-3 rounded-xl font-semibold text-base transition-all duration-200 flex items-center justify-center gap-2 bg-white shadow text-[#2c5aa0]"
                        data-target="reg-form-student">
                        <i class="fas fa-user-graduate"></i> Student
                    </button>
                    <button type="button" id="reg-tab-institution"
                        class="reg-tab flex-1 py-3 px-3 rounded-xl font-semibold text-base transition-all duration-200 flex items-center justify-center gap-2 text-gray-500"
                        data-target="reg-form-institution">
                        <i class="fas fa-university"></i> Institution
                    </button>
                </div>

                {{-- ===== STUDENT REGISTER ===== --}}
                <div id="reg-form-student" class="reg-form">
                    @if ($errors->any() && old('_form') !== 'institution')
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-4">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_form" value="student">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="First name" required>
                            </div>
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="Last name" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope text-sm"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="your@email.com" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="+977 98XXXXXXXX" required>
                            </div>
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Date of Birth <span class="text-red-500">*</span></label>
                                <input type="date" name="dob" value="{{ old('dob') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Education Level</label>
                                <select name="education_level_id"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm bg-white">
                                    <option value="">Select level</option>
                                    @foreach($educationLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('education_level_id') == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Field of Interest</label>
                                <select name="field_of_interest"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm bg-white">
                                    <option value="">Select field</option>
                                    @foreach($educationFields as $field)
                                        <option value="{{ $field->name }}" {{ old('field_of_interest') === $field->name ? 'selected' : '' }}>{{ $field->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                                <input type="password" id="stu_password" name="password"
                                    class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="Min. 8 characters" required>
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="stu_password">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                                <input type="password" id="stu_password_confirmation" name="password_confirmation"
                                    class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="Repeat password" required>
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="stu_password_confirmation">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <input type="checkbox" name="terms" id="student_terms" class="mt-1 w-4 h-4 rounded border-gray-300 text-[#4299e1]" required {{ old('terms') ? 'checked' : '' }}>
                            <label for="student_terms" class="text-sm text-gray-700">I agree to the <a href="{{ route('website.terms') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold">Terms of Service</a> and <a href="{{ route('website.privacy-policy') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold">Privacy Policy</a> <span class="text-red-500">*</span></label>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2 mt-2">
                            <i class="fas fa-user-plus"></i> Create Student Account
                        </button>
                    </form>

                    <p class="mt-5 text-center text-sm text-gray-600">
                        Already have an account?
                        <a href="{{ route('student.login') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold ml-1">Login here</a>
                    </p>
                </div>

                {{-- ===== INSTITUTION REGISTER ===== --}}
                <div id="reg-form-institution" class="reg-form hidden">
                    @if ($errors->any() && old('_form') === 'institution')
                        <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm mb-4">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('institution_registered'))
                        <div class="bg-green-50 border border-green-200 text-green-800 rounded-xl px-4 py-3 text-sm mb-4">
                            <p class="font-semibold mb-1">Registration submitted!</p>
                            <p>Your institution registration request has been received. Our team will review it and contact you within 2-3 business days.</p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('institution.register.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_form" value="institution">

                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Institution Name <span class="text-red-500">*</span></label>
                            <input type="text" name="institution_name" value="{{ old('institution_name') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                placeholder="e.g. Tribhuvan University" required>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Contact Person <span class="text-red-500">*</span></label>
                                <input type="text" name="contact_name" value="{{ old('contact_name') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="Full name" required>
                            </div>
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Designation</label>
                                <input type="text" name="contact_designation" value="{{ old('contact_designation') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="e.g. Principal">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Official Email <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope text-sm"></i></span>
                                <input type="email" name="email" value="{{ old('email') }}"
                                    class="w-full pl-11 pr-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="contact@institution.edu.np" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" value="{{ old('phone') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="+977 XXXXXXXXXX" required>
                            </div>
                            <div>
                                <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Institution Type</label>
                                <select name="institution_type"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm bg-white">
                                    <option value="">Select type</option>
                                    <option value="college"     {{ old('institution_type') === 'college'     ? 'selected' : '' }}>College</option>
                                    <option value="university"  {{ old('institution_type') === 'university'  ? 'selected' : '' }}>University</option>
                                    <option value="school"      {{ old('institution_type') === 'school'      ? 'selected' : '' }}>School</option>
                                    <option value="institute"   {{ old('institution_type') === 'institute'   ? 'selected' : '' }}>Institute</option>
                                    <option value="training"    {{ old('institution_type') === 'training'    ? 'selected' : '' }}>Training Center</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Address / Location</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                placeholder="City, District">
                        </div>

                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                                <input type="password" id="inst_reg_password" name="password"
                                    class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="Min. 8 characters" required>
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="inst_reg_password">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Confirm Password <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock text-sm"></i></span>
                                <input type="password" id="inst_reg_password_confirmation" name="password_confirmation"
                                    class="w-full pl-11 pr-11 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition text-sm"
                                    placeholder="Repeat password" required>
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 toggle-password" data-target="inst_reg_password_confirmation">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <input type="checkbox" name="terms" id="inst_terms" class="mt-1 w-4 h-4 rounded border-gray-300 text-[#4299e1]" required {{ old('terms') ? 'checked' : '' }}>
                            <label for="inst_terms" class="text-sm text-gray-700">I agree to the <a href="{{ route('website.terms') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold">Terms of Service</a> and <a href="{{ route('website.privacy-policy') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold">Privacy Policy</a> <span class="text-red-500">*</span></label>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold py-3.5 px-6 rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2 mt-2">
                            <i class="fas fa-university"></i> Submit Registration
                        </button>
                    </form>

                    <p class="mt-5 text-center text-sm text-gray-600">
                        Already registered?
                        <a href="{{ route('student.login') }}?tab=institution" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold ml-1">Login here</a>
                    </p>
                </div>

            </div>
        </div>

        <p class="text-center text-xs text-gray-500 mt-6">
            By signing up, you agree to our
            <a href="{{ route('website.terms') }}" class="underline hover:text-gray-700">Terms</a> and
            <a href="{{ route('website.privacy-policy') }}" class="underline hover:text-gray-700">Privacy Policy</a>
        </p>
    </div>
</main>
@endsection

@section('page-specific-script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tabs  = document.querySelectorAll('.reg-tab');
    const forms = document.querySelectorAll('.reg-form');
    const heroStudent     = document.getElementById('reg-hero-student');
    const heroInstitution = document.getElementById('reg-hero-institution');
    const heroTitle       = document.getElementById('reg-hero-title');
    const heroDesc        = document.getElementById('reg-hero-desc');

    function activate(targetId) {
        tabs.forEach(t => {
            const active = t.dataset.target === targetId;
            t.classList.toggle('bg-white', active);
            t.classList.toggle('shadow', active);
            t.classList.toggle('text-[#2c5aa0]', active);
            t.classList.toggle('text-gray-500', !active);
        });
        forms.forEach(f => f.classList.toggle('hidden', f.id !== targetId));

        const isInstitution = targetId === 'reg-form-institution';
        heroStudent.classList.toggle('hidden', isInstitution);
        heroInstitution.classList.toggle('hidden', !isInstitution);
        heroTitle.textContent = isInstitution ? 'Register Your Institution' : 'Join Our Community';
        heroDesc.textContent  = isInstitution
            ? 'List your institution on Sikuna to reach thousands of students seeking quality education across Nepal.'
            : 'Create your account to unlock personalised scholarship recommendations and connect with top institutions.';
    }

    tabs.forEach(tab => tab.addEventListener('click', () => activate(tab.dataset.target)));

    // Auto-open institution tab from URL param or validation failure
    const params = new URLSearchParams(window.location.search);
    if (params.get('tab') === 'institution' || "{{ old('_form') }}" === 'institution') {
        activate('reg-form-institution');
    }

    // Password visibility toggle
    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', function () {
            const input = document.getElementById(this.dataset.target);
            const icon  = this.querySelector('i');
            input.type  = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye',       input.type === 'password');
            icon.classList.toggle('fa-eye-slash', input.type !== 'password');
        });
    });
});
</script>
@endsection
