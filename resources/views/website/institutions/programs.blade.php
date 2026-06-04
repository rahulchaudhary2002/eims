@extends('website.layouts.app')

@section('meta-title', 'Programs at ' . $institution->name . ' - ' . config('app.name'))
@section('meta-description', 'Explore all programs offered by ' . $institution->name . '.')

@section('content')
@php
    $logo = $institution->logo && Storage::disk('public')->exists($institution->logo)
        ? Storage::url($institution->logo)
        : null;
@endphp

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @php
            $isCollegeRoute = $routePrefix === 'website.colleges';
            $listingLabel = $isCollegeRoute ? 'Colleges' : 'Institutions';
            $listingRoute = $isCollegeRoute ? route('website.colleges.index') : route('website.institutions.index');
        @endphp
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => $listingLabel, 'url' => $listingRoute],
                ['label' => $institution->name, 'url' => route($routePrefix . '.show', $institution->slug)],
                ['label' => 'Programs'],
            ],
        ])

        <div class="grid lg:grid-cols-[minmax(0,1fr)_320px] gap-10 items-center mt-12">
            <div>
                <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                    <i class="fas fa-book-open text-[#4299e1]"></i>
                    Academic Programs
                </span>
                <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-4">Programs at {{ $institution->name }}</h1>
                <p class="text-[1.05rem] text-white/85 leading-relaxed max-w-xl">
                    Browse all available programs, check fees, seats, and admission schedules, then apply directly.
                </p>
            </div>

            <div class="bg-white/10 border border-white/20 rounded-xl p-6 shadow-[0_15px_40px_rgba(0,0,0,0.18)]">
                <div class="flex items-center gap-4 mb-5">
                    <div class="h-16 w-16 rounded-xl bg-white border border-white/20 flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if ($logo)
                            <img src="{{ $logo }}" alt="{{ $institution->name }}" class="h-full w-full object-contain p-2">
                        @else
                            <i class="fas fa-university text-[#2c5aa0] text-2xl"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-white/70 mb-1">Institution</p>
                        <h2 class="font-bold text-lg leading-tight">{{ $institution->name }}</h2>
                        @if ($institution->city)
                            <p class="text-sm text-white/80 mt-1"><i class="fas fa-map-marker-alt text-[#4299e1] mr-1"></i>{{ $institution->city }}</p>
                        @endif
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-white/10 border border-white/15 p-4 text-center">
                        <p class="text-[1.6rem] font-bold">{{ $programs->total() }}</p>
                        <p class="text-xs text-white/70 mt-1">Programs</p>
                    </div>
                    <div class="rounded-xl bg-white/10 border border-white/15 p-4 text-center">
                        <a href="{{ route($routePrefix . '.show', $institution->slug) }}"
                           class="text-sm font-semibold text-[#4299e1] hover:text-white transition no-underline flex items-center justify-center gap-1 h-full">
                            <i class="fas fa-arrow-left"></i> Institution Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        @if ($programs->isEmpty())
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 py-20 text-center">
                <i class="fas fa-graduation-cap text-gray-200 text-6xl mb-5"></i>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">No Programs Available</h2>
                <p class="text-gray-500 text-sm mb-6">This institution has no active programs listed at this time.</p>
                <a href="{{ route($routePrefix . '.show', $institution->slug) }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white font-semibold rounded-xl hover:from-[#2c5aa0] hover:to-[#1a365d] transition no-underline">
                    <i class="fas fa-arrow-left"></i> Back to Institution
                </a>
            </div>
        @else
            <div class="mb-8 flex items-end justify-between flex-wrap gap-3">
                <div>
                    <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">All Programs</h2>
                    <p class="text-gray-600 text-[0.95rem] mt-5">Showing {{ $programs->count() }} of {{ $programs->total() }} programs.</p>
                </div>
                <a href="{{ route('website.inquiry.create', ['institution' => $institution->slug]) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 border-2 border-[#4299e1] text-[#2c5aa0] font-semibold rounded-xl hover:bg-[#4299e1]/10 transition no-underline text-sm">
                    <i class="fas fa-question-circle"></i> Ask About Admission
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($programs as $program)
                    @include('website.partials.program-card', ['program' => $program])
                @endforeach
            </div>
            @include('website.partials.pagination', ['paginator' => $programs])
        @endif
    </div>
</section>
@endsection
