@extends('website.layouts.app')

@section('meta-title', $scholarship->title . ' - ' . config('app.name'))
@section('meta-description', $scholarship->description ?? 'Apply for ' . $scholarship->title . ' scholarship.')

@section('content')
@php
    $benefitLabel = $scholarship->benefit_type === 'percentage'
        ? $scholarship->benefit_value . '% Discount'
        : 'NPR ' . number_format($scholarship->benefit_value);

    $availableSlots = $scholarship->total_slots
        ? max((int) $scholarship->total_slots - (int) $scholarship->used_slots, 0)
        : null;

    $institutionLogo = $scholarship->institution?->logo && Storage::disk('public')->exists($scholarship->institution->logo)
        ? Storage::url($scholarship->institution->logo)
        : null;
@endphp

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Scholarships', 'url' => route('website.scholarships.index')],
                ['label' => $scholarship->title],
            ],
        ])

        <div class="grid lg:grid-cols-[minmax(0,1fr)_360px] gap-10 items-center mt-12">
            <div>
                <div class="flex flex-wrap gap-2 mb-5">
                    <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold">
                        <i class="fas fa-award text-[#4299e1]"></i>
                        {{ \App\Models\Scholarship::TYPES[$scholarship->type] ?? $scholarship->type }}
                    </span>
                    <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold">
                        <i class="fas fa-gift text-[#4299e1]"></i>
                        {{ \App\Models\Scholarship::BENEFIT_TYPES[$scholarship->benefit_type] ?? $scholarship->benefit_type }}
                    </span>
                </div>

                <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-5">{{ $scholarship->title }}</h1>

                @if ($scholarship->description)
                    <p class="text-[1.05rem] md:text-[1.15rem] text-white/85 leading-relaxed max-w-3xl">
                        {{ Str::limit(strip_tags($scholarship->description), 220) }}
                    </p>
                @endif

                @if ($scholarship->institution)
                    <a href="{{ route('website.institutions.show', $scholarship->institution->slug) }}"
                        class="inline-flex items-center gap-3 mt-7 text-white hover:text-[#4299e1] transition no-underline">
                        <span class="h-12 w-12 rounded-xl bg-white flex items-center justify-center overflow-hidden">
                            @if($institutionLogo)
                                <img src="{{ $institutionLogo }}" alt="{{ $scholarship->institution->name }} logo" class="h-full w-full object-contain p-2">
                            @else
                                <i class="fas fa-university text-[#2c5aa0]"></i>
                            @endif
                        </span>
                        <span>
                            <span class="block text-sm text-white/70">Offered by</span>
                            <span class="font-bold">{{ $scholarship->institution->name }}</span>
                        </span>
                    </a>
                @endif
            </div>

            <div class="bg-white/10 border border-white/20 rounded-xl p-6 shadow-[0_15px_40px_rgba(0,0,0,0.18)]">
                <div class="text-center mb-6">
                    <div class="w-16 h-16 rounded-full bg-white text-[#2c5aa0] flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-award text-3xl"></i>
                    </div>
                    <p class="text-sm text-white/70 mb-1">Scholarship Benefit</p>
                    <p class="text-[2rem] font-bold leading-tight">{{ $benefitLabel }}</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @if ($scholarship->end_date)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                            <p class="text-xs text-white/65 mb-1">Deadline</p>
                            <p class="font-bold">{{ $scholarship->end_date->format('M d, Y') }}</p>
                        </div>
                    @endif
                    @if (! is_null($availableSlots))
                        <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                            <p class="text-xs text-white/65 mb-1">Slots Left</p>
                            <p class="font-bold">{{ $availableSlots }} / {{ $scholarship->total_slots }}</p>
                        </div>
                    @endif
                    @if ($scholarship->minimum_gpa)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                            <p class="text-xs text-white/65 mb-1">Min GPA</p>
                            <p class="font-bold">{{ $scholarship->minimum_gpa }}</p>
                        </div>
                    @endif
                    @if ($scholarship->minimum_percentage)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                            <p class="text-xs text-white/65 mb-1">Min %</p>
                            <p class="font-bold">{{ $scholarship->minimum_percentage }}%</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_340px] gap-8 items-start">
            <div class="space-y-8">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                    <div class="mb-7">
                        <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Scholarship Details</h2>
                        <p class="text-gray-600 text-[0.95rem]">Review the benefit, eligibility, available slots, and application timeline.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                            <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                <i class="fas fa-gift"></i>
                            </span>
                            <p class="text-sm text-gray-500 mb-1">Benefit</p>
                            <p class="text-lg font-bold text-gray-900">{{ $benefitLabel }}</p>
                        </div>

                        @if (! is_null($availableSlots))
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                    <i class="fas fa-users"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Available Slots</p>
                                <p class="text-lg font-bold text-gray-900">{{ $availableSlots }} / {{ $scholarship->total_slots }}</p>
                            </div>
                        @endif

                        @if ($scholarship->minimum_gpa)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Minimum GPA</p>
                                <p class="text-lg font-bold text-gray-900">{{ $scholarship->minimum_gpa }}</p>
                            </div>
                        @endif

                        @if ($scholarship->minimum_percentage)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                    <i class="fas fa-percent"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Minimum Percentage</p>
                                <p class="text-lg font-bold text-gray-900">{{ $scholarship->minimum_percentage }}%</p>
                            </div>
                        @endif

                        @if ($scholarship->start_date)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                    <i class="fas fa-calendar-plus"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Start Date</p>
                                <p class="text-lg font-bold text-gray-900">{{ $scholarship->start_date->format('M d, Y') }}</p>
                            </div>
                        @endif

                        @if ($scholarship->end_date)
                            <div class="rounded-xl bg-red-50 border border-red-100 p-5">
                                <span class="w-11 h-11 rounded-xl bg-red-100 text-red-700 flex items-center justify-center mb-4">
                                    <i class="fas fa-calendar-times"></i>
                                </span>
                                <p class="text-sm text-red-600 mb-1">Deadline</p>
                                <p class="text-lg font-bold text-red-700">{{ $scholarship->end_date->format('M d, Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($scholarship->institutionProgram)
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="text-[2rem] font-bold text-[#2c5aa0] mb-2">Related Program</h2>
                            <p class="text-gray-600 text-[0.95rem]">This scholarship is linked with the following program.</p>
                        </div>
                        @include('website.partials.program-card', ['program' => $scholarship->institutionProgram])
                    </div>
                @endif

                @if ($related->isNotEmpty())
                    <div>
                        <div class="mb-6">
                            <h2 class="text-[2rem] font-bold text-[#2c5aa0] mb-2">More Scholarships</h2>
                            <p class="text-gray-600 text-[0.95rem]">Other active scholarships from {{ $scholarship->institution?->name ?? 'this institution' }}.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            @foreach ($related as $rel)
                                @include('website.partials.scholarship-card', ['scholarship' => $rel])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-3">Apply for Scholarship</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-5">Submit your application before the deadline and keep your academic details ready.</p>
                    <a href="{{ route('website.applications.create', ['institution' => $scholarship->institution?->slug, 'program' => $scholarship->institutionProgram?->slug, 'scholarship' => $scholarship->slug]) }}"
                        class="w-full px-5 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2 no-underline">
                        <i class="fas fa-paper-plane"></i> Apply Now
                    </a>
                    @if ($scholarship->institution)
                        <a href="{{ route('website.inquiry.create', ['institution' => $scholarship->institution?->slug]) }}"
                            class="w-full mt-3 px-5 py-3.5 bg-white border-2 border-[#4299e1] text-[#2c5aa0] font-bold rounded-xl hover:bg-[#4299e1]/10 transition flex items-center justify-center gap-2 no-underline">
                            <i class="fas fa-question-circle"></i> Ask a Question
                        </a>
                    @endif
                </div>

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-xl font-bold mb-4">Quick Summary</h3>
                    <div class="space-y-4 text-sm">
                        <div class="flex justify-between gap-4">
                            <span class="text-white/70">Benefit</span>
                            <span class="font-semibold text-right">{{ $benefitLabel }}</span>
                        </div>
                        @if ($scholarship->end_date)
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Deadline</span>
                                <span class="font-semibold text-right">{{ $scholarship->end_date->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if (! is_null($availableSlots))
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Slots</span>
                                <span class="font-semibold text-right">{{ $availableSlots }} left</span>
                            </div>
                        @endif
                        @if ($scholarship->institution)
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Institution</span>
                                <span class="font-semibold text-right">{{ $scholarship->institution->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
