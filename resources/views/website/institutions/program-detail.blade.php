@extends('website.layouts.app')

@section('meta-title', $institutionProgram->display_name . ' at ' . $institution->name . ' - ' . config('app.name'))
@section('meta-description', 'Learn about ' . $institutionProgram->display_name . ' at ' . $institution->name . '. View fees, seats, admission schedule, and apply.')

@section('content')
@php
    $statusColors = [
        'open'     => 'bg-green-100 text-green-700 border-green-200',
        'upcoming' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
        'closed'   => 'bg-red-100 text-red-700 border-red-200',
    ];
    $logo = $institution->logo && Storage::disk('public')->exists($institution->logo)
        ? Storage::url($institution->logo)
        : null;
@endphp

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Institutions', 'url' => route('website.institutions.index')],
                ['label' => $institution->name, 'url' => route('website.institutions.show', $institution->slug)],
                ['label' => 'Programs', 'url' => route('website.institutions.programs', $institution->slug)],
                ['label' => $institutionProgram->display_name],
            ],
        ])

        <div class="grid lg:grid-cols-[minmax(0,1fr)_360px] gap-10 items-center mt-12">
            <div>
                <div class="flex flex-wrap gap-2 mb-5">
                    @if ($institutionProgram->program?->faculty)
                        <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold">
                            <i class="fas fa-layer-group text-[#4299e1]"></i>
                            {{ $institutionProgram->program->faculty->name }}
                        </span>
                    @endif
                    @if ($institutionProgram->program?->level)
                        <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold">
                            <i class="fas fa-graduation-cap text-[#4299e1]"></i>
                            {{ Str::headline($institutionProgram->program->level) }}
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-2 border rounded-full px-4 py-2 text-sm font-semibold
                        {{ $institutionProgram->status === 'open' ? 'bg-green-500/20 border-green-400/40 text-green-200' : ($institutionProgram->status === 'upcoming' ? 'bg-yellow-500/20 border-yellow-400/40 text-yellow-200' : 'bg-red-500/20 border-red-400/40 text-red-200') }}">
                        <i class="fas fa-circle text-[8px]"></i>
                        {{ \App\Models\InstitutionProgram::STATUSES[$institutionProgram->status] ?? $institutionProgram->status }}
                    </span>
                </div>

                <h1 class="text-[2.4rem] md:text-[3.2rem] font-bold leading-[1.15] mb-4">{{ $institutionProgram->display_name }}</h1>

                <a href="{{ route('website.institutions.show', $institution->slug) }}"
                   class="inline-flex items-center gap-3 mt-2 text-white hover:text-[#4299e1] transition no-underline">
                    <span class="h-12 w-12 rounded-xl bg-white flex items-center justify-center overflow-hidden">
                        @if ($logo)
                            <img src="{{ $logo }}" alt="{{ $institution->name }}" class="h-full w-full object-contain p-2">
                        @else
                            <i class="fas fa-university text-[#2c5aa0]"></i>
                        @endif
                    </span>
                    <span>
                        <span class="block text-sm text-white/70">Offered by</span>
                        <span class="font-bold">{{ $institution->name }}</span>
                    </span>
                </a>
            </div>

            <div class="bg-white/10 border border-white/20 rounded-xl p-6 shadow-[0_15px_40px_rgba(0,0,0,0.18)]">
                <div class="text-center mb-5">
                    <div class="w-14 h-14 rounded-full bg-white text-[#2c5aa0] flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-book-open text-2xl"></i>
                    </div>
                    @if ($institutionProgram->total_fee)
                        <p class="text-sm text-white/70 mb-1">Total Fee</p>
                        <p class="text-[2rem] font-bold leading-tight">NPR {{ number_format($institutionProgram->total_fee) }}</p>
                    @else
                        <p class="text-lg font-bold text-white/80">Program Details</p>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-3">
                    @if ($institutionProgram->duration_months)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-3">
                            <p class="text-xs text-white/65 mb-1">Duration</p>
                            <p class="font-bold text-sm">{{ $institutionProgram->duration_months }} months</p>
                        </div>
                    @endif
                    @if ($institutionProgram->total_seats)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-3">
                            <p class="text-xs text-white/65 mb-1">Total Seats</p>
                            <p class="font-bold text-sm">{{ $institutionProgram->total_seats }}</p>
                        </div>
                    @endif
                    @if ($institutionProgram->available_seats)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-3">
                            <p class="text-xs text-white/65 mb-1">Available</p>
                            <p class="font-bold text-sm text-green-300">{{ $institutionProgram->available_seats }}</p>
                        </div>
                    @endif
                    @if ($institutionProgram->minimum_gpa)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-3">
                            <p class="text-xs text-white/65 mb-1">Min GPA</p>
                            <p class="font-bold text-sm">{{ $institutionProgram->minimum_gpa }}</p>
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

                {{-- Program Stats --}}
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                    <div class="mb-7">
                        <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Program Details</h2>
                        <p class="text-gray-600 text-[0.95rem]">Key information about fees, seats, and eligibility requirements.</p>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                        @if ($institutionProgram->total_fee)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                    <i class="fas fa-rupee-sign"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Total Fee</p>
                                <p class="text-lg font-bold text-gray-900">NPR {{ number_format($institutionProgram->total_fee) }}</p>
                            </div>
                        @endif
                        @if ($institutionProgram->duration_months)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                    <i class="fas fa-clock"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Duration</p>
                                <p class="text-lg font-bold text-gray-900">{{ $institutionProgram->duration_months }} months</p>
                            </div>
                        @endif
                        @if ($institutionProgram->total_seats)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                    <i class="fas fa-users"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Total Seats</p>
                                <p class="text-lg font-bold text-gray-900">{{ $institutionProgram->total_seats }}</p>
                            </div>
                        @endif
                        @if ($institutionProgram->available_seats)
                            <div class="rounded-xl bg-green-50 border border-green-100 p-5">
                                <span class="w-11 h-11 rounded-xl bg-green-100 text-green-700 flex items-center justify-center mb-4">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                                <p class="text-sm text-green-600 mb-1">Available Seats</p>
                                <p class="text-lg font-bold text-green-700">{{ $institutionProgram->available_seats }}</p>
                            </div>
                        @endif
                        @if ($institutionProgram->minimum_gpa)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                    <i class="fas fa-graduation-cap"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Min. GPA</p>
                                <p class="text-lg font-bold text-gray-900">{{ $institutionProgram->minimum_gpa }}</p>
                            </div>
                        @endif
                        @if ($institutionProgram->minimum_percentage)
                            <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                <span class="w-11 h-11 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mb-4">
                                    <i class="fas fa-percent"></i>
                                </span>
                                <p class="text-sm text-gray-500 mb-1">Min. Percentage</p>
                                <p class="text-lg font-bold text-gray-900">{{ $institutionProgram->minimum_percentage }}%</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Admission Schedule --}}
                @if ($institutionProgram->admission_start_date || $institutionProgram->admission_end_date)
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Admission Schedule</h2>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-5">
                            @if ($institutionProgram->admission_start_date)
                                <div class="flex items-center gap-4 flex-1 rounded-xl bg-green-50 border border-green-100 p-5">
                                    <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-calendar-plus text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-green-600 font-semibold mb-1">Opens</p>
                                        <p class="font-bold text-gray-900">{{ $institutionProgram->admission_start_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if ($institutionProgram->admission_end_date)
                                <div class="flex items-center gap-4 flex-1 rounded-xl bg-red-50 border border-red-100 p-5">
                                    <div class="w-12 h-12 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-calendar-times text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-red-600 font-semibold mb-1">Deadline</p>
                                        <p class="font-bold text-gray-900">{{ $institutionProgram->admission_end_date->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                {{-- Subjects --}}
                @if ($institutionProgram->subjects->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Subjects / Curriculum</h2>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach ($institutionProgram->subjects as $subject)
                                <div class="flex items-center gap-2.5 text-sm text-gray-700 bg-[#f7fafc] border border-gray-200 rounded-xl px-4 py-3">
                                    <i class="fas fa-book text-[#4299e1] text-xs flex-shrink-0"></i>
                                    <span>{{ $subject->name ?? $subject->title ?? 'Subject' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Scholarships --}}
                @if ($institutionProgram->scholarships->isNotEmpty())
                    <div>
                        <div class="mb-6">
                            <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Available Scholarships</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @foreach ($institutionProgram->scholarships as $scholarship)
                                @include('website.partials.scholarship-card', ['scholarship' => $scholarship])
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-3">Interested?</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-5">Apply now or ask a question before committing - the institution team is ready to help.</p>
                    @if ($institutionProgram->status === 'open')
                        <a href="{{ route('website.applications.create', ['institution' => $institution->slug, 'program' => $institutionProgram->slug]) }}"
                           class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg no-underline mb-3">
                            <i class="fas fa-paper-plane"></i> Apply Now
                        </a>
                    @endif
                    <a href="{{ route('website.inquiry.create', ['institution' => $institution->slug, 'program' => $institutionProgram->slug]) }}"
                       class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-white border-2 border-[#4299e1] text-[#2c5aa0] font-bold rounded-xl hover:bg-[#4299e1]/10 transition no-underline">
                        <i class="fas fa-question-circle"></i> Ask a Question
                    </a>
                </div>

                @if ($institutionProgram->admission_fee || $institutionProgram->semester_fee || $institutionProgram->annual_fee || $institutionProgram->monthly_fee || $institutionProgram->total_fee)
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">Fee Breakdown</h3>
                        <dl class="space-y-3 text-sm">
                            @if ($institutionProgram->admission_fee)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-gray-500">Admission Fee</dt>
                                    <dd class="font-semibold text-gray-900">NPR {{ number_format($institutionProgram->admission_fee) }}</dd>
                                </div>
                            @endif
                            @if ($institutionProgram->semester_fee)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-gray-500">Semester Fee</dt>
                                    <dd class="font-semibold text-gray-900">NPR {{ number_format($institutionProgram->semester_fee) }}</dd>
                                </div>
                            @endif
                            @if ($institutionProgram->annual_fee)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-gray-500">Annual Fee</dt>
                                    <dd class="font-semibold text-gray-900">NPR {{ number_format($institutionProgram->annual_fee) }}</dd>
                                </div>
                            @endif
                            @if ($institutionProgram->monthly_fee)
                                <div class="flex justify-between gap-3">
                                    <dt class="text-gray-500">Monthly Fee</dt>
                                    <dd class="font-semibold text-gray-900">NPR {{ number_format($institutionProgram->monthly_fee) }}</dd>
                                </div>
                            @endif
                            @if ($institutionProgram->total_fee)
                                <div class="flex justify-between gap-3 pt-3 border-t border-gray-200">
                                    <dt class="font-bold text-gray-900">Total</dt>
                                    <dd class="font-bold text-[#2c5aa0]">NPR {{ number_format($institutionProgram->total_fee) }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-xl font-bold mb-4">Quick Summary</h3>
                    <div class="space-y-3 text-sm">
                        @if ($institutionProgram->status)
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Status</span>
                                <span class="font-semibold">{{ \App\Models\InstitutionProgram::STATUSES[$institutionProgram->status] ?? $institutionProgram->status }}</span>
                            </div>
                        @endif
                        @if ($institutionProgram->duration_months)
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Duration</span>
                                <span class="font-semibold">{{ $institutionProgram->duration_months }} months</span>
                            </div>
                        @endif
                        @if ($institutionProgram->total_seats)
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Seats</span>
                                <span class="font-semibold">{{ $institutionProgram->total_seats }}</span>
                            </div>
                        @endif
                        @if ($institutionProgram->admission_end_date)
                            <div class="flex justify-between gap-4">
                                <span class="text-white/70">Deadline</span>
                                <span class="font-semibold">{{ $institutionProgram->admission_end_date->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
