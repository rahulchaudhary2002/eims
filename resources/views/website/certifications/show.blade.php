@extends('website.layouts.app')

@section('meta-title', $certification->title . ' - ' . config('app.name'))
@section('meta-description', $certification->description ? Str::limit(strip_tags($certification->description), 160) : 'Learn about ' . $certification->title . ' at ' . ($certification->institution->name ?? '') . '. View fee, duration, and apply.')

@section('content')
@php
    $inst = $certification->institution;
    $logo = $inst?->logo && Storage::disk('public')->exists(storage_exists($inst->logo))
        ? storage_url($inst->logo)
        : null;
    $applyUrl = route('website.applications.create', [
        'institution'   => $inst?->slug,
        'certification' => $certification->slug,
    ]);
@endphp

{{-- Hero --}}
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Certifications', 'url' => route('website.certifications.index')],
                ['label' => $certification->title],
            ],
        ])

        <div class="grid lg:grid-cols-[minmax(0,1fr)_340px] gap-10 items-center mt-12">
            <div>
                <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                    <i class="fas fa-certificate text-purple-300"></i> Certification
                </span>

                <h1 class="text-[2.6rem] md:text-[3.2rem] font-bold leading-[1.15] mb-5">{{ $certification->title }}</h1>

                @if ($certification->description)
                    <p class="text-[1.05rem] text-white/85 leading-relaxed max-w-2xl">
                        {{ Str::limit(strip_tags($certification->description), 220) }}
                    </p>
                @endif

                @if ($inst)
                    <a href="{{ route('website.institutions.show', $inst->slug) }}"
                       class="inline-flex items-center gap-3 mt-7 text-white hover:text-[#4299e1] transition no-underline">
                        <span class="h-12 w-12 rounded-xl bg-white flex items-center justify-center overflow-hidden shrink-0">
                            @if ($logo)
                                <img src="{{ $logo }}" alt="{{ $inst->name }}" class="h-full w-full object-contain p-1.5">
                            @else
                                <i class="fas fa-university text-[#2c5aa0]"></i>
                            @endif
                        </span>
                        <span>
                            <span class="block text-sm text-white/70">Offered by</span>
                            <span class="font-bold">{{ $inst->name }}</span>
                        </span>
                    </a>
                @endif
            </div>

            {{-- Quick stats card --}}
            <div class="bg-white/10 border border-white/20 rounded-xl p-6 shadow-[0_15px_40px_rgba(0,0,0,0.18)]">
                <div class="text-center mb-5">
                    <div class="w-14 h-14 rounded-full bg-purple-400/20 border border-purple-400/40 flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-certificate text-2xl text-purple-300"></i>
                    </div>
                    <p class="text-sm text-white/70">Industry Certification</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    @if ($certification->fee)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                            <p class="text-xs text-white/65 mb-1">Exam Fee</p>
                            <p class="font-bold text-lg">NPR {{ number_format($certification->fee) }}</p>
                        </div>
                    @endif
                    @if ($certification->duration_hours)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                            <p class="text-xs text-white/65 mb-1">Prep Hours</p>
                            <p class="font-bold text-lg">{{ $certification->duration_hours }} hrs</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Body --}}
<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">

            {{-- Main content --}}
            <div class="space-y-8">

                {{-- Details card --}}
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                    <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-6 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[60px] after:h-[3px] after:bg-[#4299e1]">Certification Details</h2>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                        @if ($certification->fee)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-10 h-10 rounded-xl bg-green-100 text-green-600 flex items-center justify-center mb-3">
                                    <i class="fas fa-money-bill-wave"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Exam Fee</p>
                                <p class="text-lg font-bold text-gray-900">NPR {{ number_format($certification->fee) }}</p>
                            </div>
                        @endif

                        @if ($certification->duration_hours)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center mb-3">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Preparation Hours</p>
                                <p class="text-lg font-bold text-gray-900">{{ $certification->duration_hours }} hours</p>
                            </div>
                        @endif

                        @if ($inst)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-10 h-10 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-3">
                                    <i class="fas fa-university"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Institution</p>
                                <p class="text-base font-bold text-gray-900 leading-tight">{{ $inst->name }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if ($certification->description)
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <h2 class="text-[1.6rem] font-bold text-[#2c5aa0] mb-4">About This Certification</h2>
                        <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed">
                            {!! nl2br(e($certification->description)) !!}
                        </div>
                    </div>
                @endif

                {{-- Related certifications --}}
                @if ($relatedCertifications->isNotEmpty())
                    <div>
                        <h2 class="text-[1.6rem] font-bold text-[#2c5aa0] mb-5">More Certifications from {{ $inst?->name }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($relatedCertifications as $related)
                                @include('website.partials.certification-card', ['certification' => $related])
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- Sidebar --}}
            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-3">Get Certified</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-5">Submit an application and the institution will contact you about registration and exam schedules.</p>
                    @auth('student')
                        <a href="{{ $applyUrl }}"
                           class="w-full px-5 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2 no-underline mb-3">
                            <i class="fas fa-paper-plane"></i> Apply Now
                        </a>
                    @else
                        <a href="{{ route('student.login') }}"
                           class="w-full px-5 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2 no-underline mb-3">
                            <i class="fas fa-sign-in-alt"></i> Login to Apply
                        </a>
                    @endauth
                    @if ($inst)
                        <a href="{{ route('website.inquiry.create', ['institution' => $inst->slug]) }}"
                           class="w-full px-5 py-3.5 bg-white border-2 border-[#4299e1] text-[#2c5aa0] font-bold rounded-xl hover:bg-[#4299e1]/10 transition flex items-center justify-center gap-2 no-underline">
                            <i class="fas fa-question-circle"></i> Ask a Question
                        </a>
                    @endif
                </div>

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-lg font-bold mb-4">Quick Info</h3>
                    <div class="space-y-3 text-sm">
                        @if ($certification->fee)
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Fee</span>
                                <span class="font-semibold">NPR {{ number_format($certification->fee) }}</span>
                            </div>
                        @endif
                        @if ($certification->duration_hours)
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Prep Hours</span>
                                <span class="font-semibold">{{ $certification->duration_hours }} hours</span>
                            </div>
                        @endif
                        @if ($inst)
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Institution</span>
                                <span class="font-semibold text-right">{{ $inst->name }}</span>
                            </div>
                            @if ($inst->city || $inst->province)
                                <div class="flex justify-between gap-4">
                                    <span class="text-white/70">Location</span>
                                    <span class="font-semibold text-right">{{ collect([$inst->city, $inst->province])->filter()->implode(', ') }}</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>
@endsection
