@extends('layouts.student')

@section('title', 'Recommendation')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.recommendations.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">{{ $studentRecommendation->institution?->name }}</h1>
                <p class="text-white/70 text-sm mt-1">{{ $studentRecommendation->institutionProgram?->program?->name }}</p>
            </div>
            <div class="ml-auto w-14 h-14 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center shrink-0">
                <span class="text-sm font-bold">{{ number_format($studentRecommendation->score, 0) }}%</span>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl space-y-5">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="flex items-center gap-4 px-6 py-5 border-b border-gray-100">
                    @if($studentRecommendation->institution?->logo)
                        <img src="{{ Storage::url($studentRecommendation->institution->logo) }}" class="w-14 h-14 rounded-xl object-cover">
                    @else
                        <div class="w-14 h-14 rounded-xl bg-yellow-50 flex items-center justify-center">
                            <span class="text-yellow-600 text-2xl font-bold">{{ strtoupper(substr($studentRecommendation->institution?->name ?? 'I', 0, 1)) }}</span>
                        </div>
                    @endif
                    <div>
                        <p class="text-base font-bold text-gray-800">{{ $studentRecommendation->institution?->name }}</p>
                        <p class="text-sm text-gray-500">{{ $studentRecommendation->institutionProgram?->program?->name }}</p>
                        <div class="flex items-center gap-2 mt-1.5">
                            <div class="w-8 h-8 rounded-full bg-yellow-50 border-2 border-yellow-200 flex items-center justify-center">
                                <span class="text-xs font-bold text-yellow-600">{{ number_format($studentRecommendation->score, 0) }}%</span>
                            </div>
                            <span class="text-xs text-gray-500">match score</span>
                        </div>
                    </div>
                </div>

                @if($studentRecommendation->reasons && count($studentRecommendation->reasons))
                <div class="px-6 py-5">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wide mb-3">Why this was recommended</p>
                    <ul class="space-y-2">
                        @foreach($studentRecommendation->reasons as $reason)
                        <li class="flex items-center gap-2 text-sm text-gray-700">
                            <i class="fas fa-check-circle text-green-500 shrink-0"></i>
                            {{ $reason }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="flex gap-3 px-6 py-4 border-t border-gray-100">
                    @if($studentRecommendation->institutionProgram)
                    <a href="{{ route('student.applications.create', ['institution' => $studentRecommendation->institution?->slug, 'program' => $studentRecommendation->institutionProgram?->slug]) }}"
                       class="inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
                        <i class="fas fa-paper-plane"></i> Apply Now
                    </a>
                    @endif
                    @if($studentRecommendation->institution?->slug)
                    <a href="{{ route('website.institutions.show', $studentRecommendation->institution->slug) }}"
                       class="inline-flex items-center gap-2 border border-gray-200 text-gray-600 text-sm font-semibold px-5 py-2.5 rounded-xl hover:bg-gray-50 transition no-underline">
                        View Institution
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
