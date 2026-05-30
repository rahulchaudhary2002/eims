@extends('website.layouts.app')

@section('meta-title', 'Submit Inquiry - ' . config('app.name'))

@section('content')
@php
    $coverImage = $institution?->cover_image && Storage::disk('public')->exists($institution->cover_image)
        ? Storage::url($institution->cover_image)
        : asset('assets/images/logo.png');

    $logoImage = $institution?->logo && Storage::disk('public')->exists($institution->logo)
        ? Storage::url($institution->logo)
        : null;
@endphp

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Inquiry'],
            ]
        ])

        <div class="grid lg:grid-cols-[1.05fr_0.95fr] gap-10 items-center mt-12">
            <div>
                <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                    <i class="fas fa-paper-plane text-[#4299e1]"></i>
                    Student Support
                </span>
                <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-5">Submit an Inquiry</h1>
                <p class="text-[1.05rem] md:text-[1.15rem] text-white/85 leading-relaxed max-w-2xl">
                    Ask about admissions, programs, fees, scholarships, or any institution detail. Share your question and the team will get back to you shortly.
                </p>
            </div>

            <div class="bg-white/10 border border-white/20 rounded-xl p-4 shadow-[0_15px_40px_rgba(0,0,0,0.18)]">
                <div class="relative h-44 sm:h-56 overflow-hidden rounded-xl bg-white/10">
                    <img src="{{ $coverImage }}" alt="{{ $institution?->name ?? config('app.name') }}" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-[#1a365d]/85 to-transparent"></div>
                </div>

                <div class="relative -mt-10 flex items-end gap-4 px-3 pb-2">
                    <div class="h-20 w-20 rounded-xl bg-white shadow-[0_5px_15px_rgba(0,0,0,0.15)] border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                        @if($logoImage)
                            <img src="{{ $logoImage }}" alt="{{ $institution->name }} logo" class="h-full w-full object-contain p-2">
                        @else
                            <i class="fas fa-university text-3xl text-[#2c5aa0]"></i>
                        @endif
                    </div>
                    <div class="pb-2 min-w-0">
                        <p class="text-sm text-white/75 mb-1">{{ $institution ? 'Inquiry for' : 'General Inquiry' }}</p>
                        <h2 class="text-xl font-bold truncate">{{ $institution->name ?? config('app.name') }}</h2>
                        @if($institution?->address)
                            <p class="text-sm text-white/80 mt-1 truncate">
                                <i class="fas fa-map-marker-alt mr-1 text-[#4299e1]"></i>{{ $institution->address }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_340px] gap-8 items-start">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                <div class="mb-7">
                    <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Ask Your Question</h2>
                    <p class="text-gray-600 text-[0.95rem]">Fill in the details below so the right institution team can contact you.</p>
                </div>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('website.inquiry.store') }}" class="space-y-5">
                    @csrf

                    @if ($institution)
                        <input type="hidden" name="institution_id" value="{{ $institution->id }}">
                        <div class="flex items-center gap-3 bg-[#4299e1]/10 border border-[#4299e1]/20 rounded-xl px-4 py-3 text-sm text-[#2c5aa0]">
                            <i class="fas fa-university"></i>
                            <span>Inquiry for: <strong>{{ $institution->name }}</strong></span>
                        </div>
                    @else
                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Institution (optional)</label>
                            <select name="institution_id" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl bg-white focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                                <option value="">General Inquiry</option>
                                @foreach ($institutions as $inst)
                                    <option value="{{ $inst->id }}" {{ old('institution_id') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($institutionProgram)
                        <input type="hidden" name="institution_program_id" value="{{ $institutionProgram->id }}">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-700">
                            Program: <strong>{{ $institutionProgram->display_name }}</strong>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', Auth::guard('student')->user()?->name) }}"
                                required maxlength="255" placeholder="Your full name"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('name') border-red-400 @enderror">
                        </div>
                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone', Auth::guard('student')->user()?->phone) }}"
                                required maxlength="20" placeholder="+977 9800000000"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('phone') border-red-400 @enderror">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', Auth::guard('student')->user()?->email) }}"
                            required maxlength="255" placeholder="your@email.com"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('email') border-red-400 @enderror">
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="5" required maxlength="2000"
                            placeholder="Write your question or message..."
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition resize-none @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                    </div>

                    <input type="hidden" name="source" value="{{ request()->filled('program_id') ? 'program_page' : (request()->filled('institution_id') ? 'institution_page' : 'website') }}">

                    <button type="submit"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Submit Inquiry
                    </button>
                </form>
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">What Happens Next?</h3>
                    <div class="space-y-4">
                        <div class="flex gap-3">
                            <span class="h-9 w-9 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center shrink-0"><i class="fas fa-check"></i></span>
                            <div>
                                <h4 class="font-semibold text-gray-900">Inquiry received</h4>
                                <p class="text-sm text-gray-600 mt-1">Your details are saved with the selected institution or program.</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="h-9 w-9 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center shrink-0"><i class="fas fa-phone"></i></span>
                            <div>
                                <h4 class="font-semibold text-gray-900">Team follow-up</h4>
                                <p class="text-sm text-gray-600 mt-1">A counselor or institution representative can contact you directly.</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="h-9 w-9 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center shrink-0"><i class="fas fa-graduation-cap"></i></span>
                            <div>
                                <h4 class="font-semibold text-gray-900">Plan your next step</h4>
                                <p class="text-sm text-gray-600 mt-1">Get guidance about admissions, program fit, and scholarship options.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-xl font-bold mb-3">Need Faster Help?</h3>
                    <p class="text-sm text-white/85 leading-relaxed mb-5">Use the contact page if your question is not tied to a specific institution.</p>
                    <a href="{{ route('website.contact.index') }}" class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-semibold px-5 py-3 rounded-xl hover:bg-gray-100 transition">
                        <i class="fas fa-envelope"></i> Contact Us
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
