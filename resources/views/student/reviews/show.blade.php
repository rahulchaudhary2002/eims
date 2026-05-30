@extends('layouts.student')

@section('title', 'Review')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.reviews.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">{{ $review->institution?->name }}</h1>
                <div class="flex items-center gap-1 mt-1">
                    @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-300' : 'text-white/20' }}"></i>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <p class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</p>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $review->is_approved ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $review->is_approved ? 'Approved' : 'Pending Approval' }}
                    </span>
                </div>
                <div class="px-6 py-5">
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $review->review }}</p>
                </div>
                @if(!$review->is_approved)
                <div class="flex justify-end px-6 py-4 border-t border-gray-100">
                    <a href="{{ route('student.reviews.edit', $review) }}"
                       class="inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
