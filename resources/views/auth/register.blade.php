@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="flex items-center justify-center bg-gray-50 px-4 py-8 mt-[80px]">
    <div class="container mx-auto max-w-6xl">
        <!-- Main Auth Card (exact Sikuna.com registration design) -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden grid md:grid-cols-2">
            <!-- Left: Hero Section (Success/Teal theme) -->
            <div class="bg-gradient-to-br from-[#38b2ac] to-[#2c9c94] text-white p-8 md:p-10 flex flex-col justify-center">
                <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-5">Join Sikuna.com Community</h1>
                <p class="text-lg opacity-90 mb-8 leading-relaxed">
                    Create your account to unlock personalized scholarship recommendations, connect with educational institutions, and access exclusive resources for your academic journey.
                </p>

                <ul class="space-y-5 mt-2">
                    <li class="flex items-center gap-4">
                        <i class="fas fa-search w-10 h-10 flex items-center justify-center bg-white/20 rounded-full text-white"></i>
                        <span>Find scholarships matching your profile</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-bell w-10 h-10 flex items-center justify-center bg-white/20 rounded-full text-white"></i>
                        <span>Get notified about new opportunities</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-chart-line w-10 h-10 flex items-center justify-center bg-white/20 rounded-full text-white"></i>
                        <span>Track your application progress</span>
                    </li>
                    <li class="flex items-center gap-4">
                        <i class="fas fa-users w-10 h-10 flex items-center justify-center bg-white/20 rounded-full text-white"></i>
                        <span>Connect with mentors and peers</span>
                    </li>
                </ul>
            </div>

            <!-- Right: Registration Form Section -->
            <div class="p-8 md:p-10 max-h-[700px] overflow-y-auto">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-[#2c5aa0] mb-2">Create Your Account</h2>
                    <p class="text-gray-600">Select account type and fill in your details</p>
                </div>

                <!-- User Type Selector -->
                <div class="flex rounded-xl bg-gray-50/80 p-1 mb-8">
                    <button type="button"
                        class="user-type-btn flex-1 py-4 px-2 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-3 active bg-white shadow-md text-[#2c5aa0]"
                        data-type="student">
                        <i class="fas fa-user-graduate"></i>
                        Student Registration
                    </button>
                    <button type="button"
                        class="user-type-btn flex-1 py-4 px-2 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center gap-3 text-gray-500"
                        data-type="institution">
                        <i class="fas fa-university"></i>
                        Institution Registration
                    </button>
                </div>

                <!-- Student Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="auth-form active space-y-5" id="studentRegistrationForm">
                    @csrf
                    <!-- Name Row -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="studentFirstName" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                First Name <span class="text-[#ed8936]">*</span>
                            </label>
                            <input type="text" id="studentFirstName" name="first_name" value="{{ old('first_name') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="Enter first name" required>
                            @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="studentLastName" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Last Name <span class="text-[#ed8936]">*</span>
                            </label>
                            <input type="text" id="studentLastName" name="last_name" value="{{ old('last_name') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="Enter last name" required>
                            @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="studentEmail" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Email Address <span class="text-[#ed8936]">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="studentEmail" name="email" value="{{ old('email') }}"
                                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="your.email@example.com" required>
                        </div>
                        @error('email')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone + DOB -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="studentPhone" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Phone Number <span class="text-[#ed8936]">*</span>
                            </label>
                            <input type="tel" id="studentPhone" name="phone" value="{{ old('phone') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="Enter phone number" required>
                            @error('phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="studentDob" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Date of Birth <span class="text-[#ed8936]">*</span>
                            </label>
                            <input type="date" id="studentDob" name="dob" value="{{ old('dob') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                required>
                            @error('dob')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="studentAddress" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Address
                        </label>
                        <input type="text" id="studentAddress" name="address" value="{{ old('address') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                            placeholder="Enter your address">
                    </div>

                    <!-- Education + Field -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="studentEducation" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Education Level <span class="text-[#ed8936]">*</span>
                            </label>
                            <select id="studentEducation" name="education_level_id"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition appearance-none bg-no-repeat bg-right-4 bg-[length:16px]"
                                style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23718096\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e'); background-position: right 1rem center;" required>
                                <option value="">Select education level</option>
                                @foreach($educationLevels as $educationLevel)
                                <option value="{{ $educationLevel->id }}" {{ old('education_level_id') == $educationLevel->id ? 'selected' : '' }}>
                                    {{ $educationLevel->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="studentField" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Field of Interest
                            </label>
                            <select id="studentField" name="field_of_interest"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition appearance-none bg-no-repeat bg-right-4 bg-[length:16px]"
                                style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23718096\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e'); background-position: right 1rem center;">
                                <option value="">Select field</option>
                                @foreach($educationFields as $educationField)
                                <option value="{{ $educationField->name }}" {{ old('field_of_interest') == $educationField->name ? 'selected' : '' }}>
                                    {{ $educationField->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('field_of_interest')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="studentPassword" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Password <span class="text-[#ed8936]">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="studentPassword" name="password"
                                class="w-full pl-12 pr-12 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="Create a strong password" required>
                            <button type="button"
                                class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                data-target="studentPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden" id="studentPasswordStrength">
                            <div class="password-strength-bar h-full w-0 transition-all duration-300"></div>
                        </div>
                        <div class="password-requirements mt-2 text-xs text-gray-600" id="studentPasswordRequirements">
                            <p class="font-medium mb-1">Password must contain:</p>
                            <ul class="grid grid-cols-2 gap-x-2">
                                <li id="studentLength" class="flex items-center gap-1.5 text-gray-500">❌ At least 8 characters</li>
                                <li id="studentUppercase" class="flex items-center gap-1.5 text-gray-500">❌ One uppercase letter</li>
                                <li id="studentLowercase" class="flex items-center gap-1.5 text-gray-500">❌ One lowercase letter</li>
                                <li id="studentNumber" class="flex items-center gap-1.5 text-gray-500">❌ One number</li>
                                <li id="studentSpecial" class="flex items-center gap-1.5 text-gray-500">❌ One special character</li>
                            </ul>
                        </div>
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="studentConfirmPassword" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Confirm Password <span class="text-[#ed8936]">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="studentConfirmPassword" name="password_confirmation"
                                class="w-full pl-12 pr-12 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="Confirm your password" required>
                            <button type="button"
                                class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                data-target="studentConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="studentPasswordMatch" class="text-xs mt-1.5 min-h-5"></div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-3 mt-2">
                        <input type="checkbox" id="studentTerms" name="terms" class="mt-1 w-4 h-4 rounded border-gray-300 text-[#38b2ac] focus:ring-[#38b2ac]/30" required {{ old('terms') ? 'checked' : '' }}>
                        <label for="studentTerms" class="text-sm text-gray-700 leading-relaxed">
                            I agree to the <a href="#" class="text-[#38b2ac] hover:text-[#2c9c94] font-semibold">Terms of Service</a> and <a href="#" class="text-[#38b2ac] hover:text-[#2c9c94] font-semibold">Privacy Policy</a> <span class="text-[#ed8936]">*</span>
                        </label>
                    </div>

                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="studentNewsletter" name="newsletter" class="mt-1 w-4 h-4 rounded border-gray-300 text-[#38b2ac] focus:ring-[#38b2ac]/30" {{ old('newsletter') ? 'checked' : '' }}>
                        <label for="studentNewsletter" class="text-sm text-gray-700 leading-relaxed">
                            I want to receive scholarship updates and newsletters
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#38b2ac] to-[#2c9c94] hover:from-[#2c9c94] hover:to-[#1e7a74] text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-3 text-lg mt-6">
                        <i class="fas fa-user-plus"></i>
                        Create Student Account
                    </button>
                </form>

                <!-- Institution Registration Form -->
                <form method="POST" action="{{ route('vendor.register') }}" class="auth-form hidden space-y-5" id="institutionRegistrationForm">
                    @csrf
                    <div>
                        <label for="institutionName" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Institution Name <span class="text-[#ed8936]">*</span>
                        </label>
                        <input type="text" id="institutionName" name="institution_name" value="{{ old('institution_name') }}"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                            placeholder="Enter institution name" required>
                    </div>

                    <div>
                        <label for="institutionType" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Institution Type <span class="text-[#ed8936]">*</span>
                        </label>
                        <select id="institutionType" name="institution_type"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition appearance-none bg-no-repeat bg-right-4 bg-[length:16px]"
                            style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23718096\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e'); background-position: right 1rem center;" required>
                            <option value="">Select institution type</option>
                            @foreach($institutionTypes as $institutionType)
                            <option value="{{ $institutionType->id }}" {{ old('institution_type') == $institutionType->id ? 'selected' : '' }}>
                                {{ $institutionType->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="institutionCategory" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Institution Category <span class="text-[#ed8936]">*</span>
                        </label>
                        <select id="institutionCategory" name="institution_category"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition appearance-none bg-no-repeat bg-right-4 bg-[length:16px]"
                            style="background-image: url('data:image/svg+xml;charset=UTF-8,%3csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'%23718096\' stroke-width=\'2\' stroke-linecap=\'round\' stroke-linejoin=\'round\'%3e%3cpolyline points=\'6 9 12 15 18 9\'%3e%3c/polyline%3e%3c/svg%3e'); background-position: right 1rem center;" required>
                            <option value="">Select institution category</option>
                            @foreach($institutionCategories as $institutionCategory)
                            <option value="{{ $institutionCategory->id }}" {{ old('institution_category') == $institutionCategory->id ? 'selected' : '' }}>
                                {{ $institutionCategory->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Institution Contact Details Block -->
                    <div class="bg-gray-50/80 p-5 rounded-xl border-l-4 border-[#4299e1] space-y-4">
                        <h4 class="text-[#2c5aa0] font-semibold flex items-center gap-2">
                            <i class="fas fa-info-circle"></i> Contact Person Details
                        </h4>

                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label for="contactName" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Contact Person Name <span class="text-[#ed8936]">*</span>
                                </label>
                                <input type="text" id="contactName" name="contact_name" value="{{ old('contact_name') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                    placeholder="Enter contact name" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="contactEmail" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Contact Email <span class="text-[#ed8936]">*</span>
                                </label>
                                <input type="email" id="contactEmail" name="contact_email" value="{{ old('contact_email') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                    placeholder="contact@institution.edu" required>
                            </div>
                            <div>
                                <label for="contactPhone" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                    Contact Phone <span class="text-[#ed8936]">*</span>
                                </label>
                                <input type="tel" id="contactPhone" name="contact_phone" value="{{ old('contact_phone') }}"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                    placeholder="Enter contact phone" required>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="institutionAddress" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Institution Address <span class="text-[#ed8936]">*</span>
                        </label>
                        <textarea id="institutionAddress" name="address" rows="2"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                            placeholder="Enter full institution address" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="institutionWebsite" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Website
                            </label>
                            <input type="url" id="institutionWebsite" name="website" value="{{ old('website') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="https://example.com">
                        </div>
                        <div>
                            <label for="institutionEstablished" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Established Year
                            </label>
                            <input type="number" id="institutionEstablished" name="established_year" value="{{ old('established_year') }}"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="e.g., 1990" min="1900" max="2025">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="institutionPassword" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Password <span class="text-[#ed8936]">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="institutionPassword" name="password"
                                class="w-full pl-12 pr-12 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="Create a strong password" required>
                            <button type="button"
                                class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                data-target="institutionPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="password-strength mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden" id="institutionPasswordStrength">
                            <div class="password-strength-bar h-full w-0 transition-all duration-300"></div>
                        </div>
                        @error('password')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="institutionConfirmPassword" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Confirm Password <span class="text-[#ed8936]">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="institutionConfirmPassword" name="password_confirmation"
                                class="w-full pl-12 pr-12 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition"
                                placeholder="Confirm your password" required>
                            <button type="button"
                                class="password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                data-target="institutionConfirmPassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div id="institutionPasswordMatch" class="text-xs mt-1.5 min-h-5"></div>
                    </div>

                    <!-- Terms -->
                    <div class="flex items-start gap-3 mt-2">
                        <input type="checkbox" id="institutionTerms" name="terms" class="mt-1 w-4 h-4 rounded border-gray-300 text-[#38b2ac] focus:ring-[#38b2ac]/30" required {{ old('terms') ? 'checked' : '' }}>
                        <label for="institutionTerms" class="text-sm text-gray-700 leading-relaxed">
                            I agree to the <a href="#" class="text-[#38b2ac] hover:text-[#2c9c94] font-semibold">Terms of Service</a> and <a href="#" class="text-[#38b2ac] hover:text-[#2c9c94] font-semibold">Privacy Policy</a> <span class="text-[#ed8936]">*</span>
                        </label>
                    </div>

                    <div class="flex items-start gap-3">
                        <input type="checkbox" id="institutionVerification" name="verification" class="mt-1 w-4 h-4 rounded border-gray-300 text-[#38b2ac] focus:ring-[#38b2ac]/30" required {{ old('verification') ? 'checked' : '' }}>
                        <label for="institutionVerification" class="text-sm text-gray-700 leading-relaxed">
                            I certify that I am an authorized representative of this institution
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-[#38b2ac] to-[#2c9c94] hover:from-[#2c9c94] hover:to-[#1e7a74] text-white font-bold py-4 px-6 rounded-xl transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg flex items-center justify-center gap-3 text-lg mt-6">
                        <i class="fas fa-university"></i>
                        Create Institution Account
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-8 text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-[#38b2ac] hover:text-[#2c9c94] font-semibold ml-1">
                        Login here
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Note -->
        <p class="text-center text-xs text-gray-500 mt-6">
            By signing up, you agree to our
            <a href="#" class="underline hover:text-gray-700">Terms</a> and
            <a href="#" class="underline hover:text-gray-700">Privacy Policy</a>
        </p>
    </div>
</div>
@endsection

@section('page-specific-style')
<!-- Font Awesome -->
<style>
    /* Custom styles for password requirement indicators */
    .password-requirements li.valid {
        color: #38b2ac !important;
    }

    .password-requirements li.valid::before {
        content: '✓';
        margin-right: 0.375rem;
        font-weight: bold;
    }

    .password-requirements li:not(.valid)::before {
        content: '✗';
        margin-right: 0.375rem;
    }

    .password-strength.weak .password-strength-bar {
        background-color: #e53e3e;
        width: 33%;
    }

    .password-strength.medium .password-strength-bar {
        background-color: #ed8936;
        width: 66%;
    }

    .password-strength.strong .password-strength-bar {
        background-color: #38b2ac;
        width: 100%;
    }

    .bg-right-4 {
        background-position: right 1rem center;
    }
</style>
@endsection

@section('page-specific-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // User type selector toggle
        const userTypeBtns = document.querySelectorAll('.user-type-btn');
        const authForms = document.querySelectorAll('.auth-form');

        function setActiveTab(type) {
            userTypeBtns.forEach(btn => {
                const isActive = btn.dataset.type === type;
                btn.classList.toggle('active', isActive);
                btn.classList.toggle('bg-white', isActive);
                btn.classList.toggle('shadow-md', isActive);
                btn.classList.toggle('text-[#2c5aa0]', isActive);
                btn.classList.toggle('text-gray-500', !isActive);
                btn.classList.toggle('font-semibold', isActive);
            });

            authForms.forEach(form => {
                form.classList.toggle('hidden', form.id !== `${type}RegistrationForm`);
                form.classList.toggle('active', form.id === `${type}RegistrationForm`);
            });
        }

        // Set initial active state
        setActiveTab('student');

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

        // Student password strength checker
        const studentPasswordInput = document.getElementById('studentPassword');
        if (studentPasswordInput) {
            const studentPasswordStrength = document.getElementById('studentPasswordStrength');
            const studentLength = document.getElementById('studentLength');
            const studentUppercase = document.getElementById('studentUppercase');
            const studentLowercase = document.getElementById('studentLowercase');
            const studentNumber = document.getElementById('studentNumber');
            const studentSpecial = document.getElementById('studentSpecial');

            function checkStudentPasswordStrength() {
                const password = studentPasswordInput.value || '';
                let strength = 0;

                const hasLength = password.length >= 8;
                const hasUppercase = /[A-Z]/.test(password);
                const hasLowercase = /[a-z]/.test(password);
                const hasNumber = /\d/.test(password);
                const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);

                // Update requirement indicators
                if (studentLength) studentLength.classList.toggle('valid', hasLength);
                if (studentUppercase) studentUppercase.classList.toggle('valid', hasUppercase);
                if (studentLowercase) studentLowercase.classList.toggle('valid', hasLowercase);
                if (studentNumber) studentNumber.classList.toggle('valid', hasNumber);
                if (studentSpecial) studentSpecial.classList.toggle('valid', hasSpecial);

                if (hasLength) strength++;
                if (hasUppercase) strength++;
                if (hasLowercase) strength++;
                if (hasNumber) strength++;
                if (hasSpecial) strength++;

                if (studentPasswordStrength) {
                    studentPasswordStrength.className = 'password-strength mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden';
                    if (password.length === 0) {
                        // no class
                    } else if (strength <= 2) {
                        studentPasswordStrength.classList.add('weak');
                    } else if (strength <= 4) {
                        studentPasswordStrength.classList.add('medium');
                    } else {
                        studentPasswordStrength.classList.add('strong');
                    }
                }
                checkStudentPasswordMatch();
            }

            studentPasswordInput.addEventListener('input', checkStudentPasswordStrength);
            checkStudentPasswordStrength();
        }

        // Student password match
        const studentConfirmPassword = document.getElementById('studentConfirmPassword');

        function checkStudentPasswordMatch() {
            const password = studentPasswordInput?.value || '';
            const confirm = studentConfirmPassword?.value || '';
            const matchElement = document.getElementById('studentPasswordMatch');
            if (matchElement) {
                if (confirm.length === 0) {
                    matchElement.textContent = '';
                } else if (password === confirm) {
                    matchElement.textContent = '✓ Passwords match';
                    matchElement.style.color = '#38b2ac';
                } else {
                    matchElement.textContent = '✗ Passwords do not match';
                    matchElement.style.color = '#ed8936';
                }
            }
        }
        if (studentConfirmPassword) {
            studentConfirmPassword.addEventListener('input', checkStudentPasswordMatch);
        }

        // Institution password strength
        const institutionPasswordInput = document.getElementById('institutionPassword');
        if (institutionPasswordInput) {
            const institutionPasswordStrength = document.getElementById('institutionPasswordStrength');
            institutionPasswordInput.addEventListener('input', function() {
                const password = this.value || '';
                let strength = 0;
                if (password.length >= 8) strength++;
                if (/[A-Z]/.test(password)) strength++;
                if (/[a-z]/.test(password)) strength++;
                if (/\d/.test(password)) strength++;
                if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) strength++;

                if (institutionPasswordStrength) {
                    institutionPasswordStrength.className = 'password-strength mt-2 h-1.5 bg-gray-200 rounded-full overflow-hidden';
                    if (password.length === 0) {
                        // no class
                    } else if (strength <= 2) {
                        institutionPasswordStrength.classList.add('weak');
                    } else if (strength <= 4) {
                        institutionPasswordStrength.classList.add('medium');
                    } else {
                        institutionPasswordStrength.classList.add('strong');
                    }
                }
                checkInstitutionPasswordMatch();
            });
        }

        // Institution password match
        const institutionConfirmPassword = document.getElementById('institutionConfirmPassword');

        function checkInstitutionPasswordMatch() {
            const password = institutionPasswordInput?.value || '';
            const confirm = institutionConfirmPassword?.value || '';
            const matchElement = document.getElementById('institutionPasswordMatch');
            if (matchElement) {
                if (confirm.length === 0) {
                    matchElement.textContent = '';
                } else if (password === confirm) {
                    matchElement.textContent = '✓ Passwords match';
                    matchElement.style.color = '#38b2ac';
                } else {
                    matchElement.textContent = '✗ Passwords do not match';
                    matchElement.style.color = '#ed8936';
                }
            }
        }
        if (institutionConfirmPassword) {
            institutionConfirmPassword.addEventListener('input', checkInstitutionPasswordMatch);
        }

        // Set max date for DOB (at least 18 years old)
        const dobInput = document.getElementById('studentDob');
        if (dobInput) {
            const today = new Date();
            const minDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
            const maxDate = new Date(today.getFullYear() - 100, today.getMonth(), today.getDate());
            dobInput.max = minDate.toISOString().split('T')[0];
            dobInput.min = maxDate.toISOString().split('T')[0];
        }
    });
</script>
@endsection