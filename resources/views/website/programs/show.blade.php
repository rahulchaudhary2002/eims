@extends('website.layouts.app')

@section('meta-title', $program->name . ' - ' . config('app.name'))
@section('meta-description', $program->description ?? 'Learn about ' . $program->name . ' programs offered by top institutions.')

@section('content')
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Programs', 'url' => route('website.programs.index')],
                ['label' => $program->name],
            ],
        ])

        <div class="mt-12 max-w-3xl">
            <div class="flex flex-wrap gap-2 mb-5">
                @if ($program->faculty)
                    <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold">
                        <i class="fas fa-layer-group text-[#4299e1]"></i>
                        {{ $program->faculty->name }}
                    </span>
                @endif
                @if ($program->level)
                    <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold">
                        <i class="fas fa-graduation-cap text-[#4299e1]"></i>
                        {{ Str::headline($program->level) }}
                    </span>
                @endif
                <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold">
                    <i class="fas fa-university text-[#4299e1]"></i>
                    {{ $institutionPrograms->count() }} Institution{{ $institutionPrograms->count() !== 1 ? 's' : '' }} Offering
                </span>
            </div>

            <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-5">{{ $program->name }}</h1>

            @if ($program->description)
                <p class="text-[1.05rem] md:text-[1.15rem] text-white/85 leading-relaxed max-w-2xl">
                    {{ Str::limit($program->description, 220) }}
                </p>
            @endif
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        @if ($institutionPrograms->isEmpty())
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 py-20 text-center">
                <i class="fas fa-book-open text-gray-300 text-5xl mb-4"></i>
                <h2 class="text-lg font-semibold text-gray-700 mb-2">No Institutions Yet</h2>
                <p class="text-gray-500 text-sm mb-6">No institutions are currently offering this program.</p>
                <a href="{{ route('website.programs.index') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white font-semibold rounded-xl hover:from-[#2c5aa0] hover:to-[#1a365d] transition no-underline">
                    <i class="fas fa-arrow-left"></i> Browse Programs
                </a>
            </div>
        @else
            <div class="mb-8">
                <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Available at These Institutions</h2>
                <p class="text-gray-600 text-[0.95rem] mt-5">Choose an institution and start your application journey.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($institutionPrograms as $ip)
                    @include('website.partials.program-card', ['program' => $ip])
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
