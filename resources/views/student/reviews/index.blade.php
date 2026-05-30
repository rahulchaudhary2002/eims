@extends('layouts.student')

@section('title', 'My Reviews')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">My Reviews</h1>
                <p class="text-white/70 text-sm mt-1">Feedback you've submitted for institutions</p>
            </div>
            <a href="{{ route('student.reviews.create') }}"
               class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm no-underline shrink-0">
                <i class="fas fa-star"></i> Write Review
            </a>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-4">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @forelse($reviews as $review)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="flex items-start gap-4 px-5 py-4">
                <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center shrink-0">
                    <span class="text-xs font-bold text-yellow-600">{{ $review->rating }}/5</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">{{ $review->institution?->name }}</h3>
                            <div class="flex items-center gap-0.5 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $review->is_approved ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                            {{ $review->is_approved ? 'Approved' : 'Pending Approval' }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2 line-clamp-2">{{ $review->review }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $review->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if(!$review->is_approved)
            <div class="flex items-center justify-end gap-2 px-5 py-3 border-t border-gray-50 bg-gray-50/50">
                <a href="{{ route('student.reviews.edit', $review) }}"
                   class="text-xs font-semibold text-[#4299e1] px-3 py-1.5 border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">Edit</a>
                <form method="POST" action="{{ route('student.reviews.destroy', $review) }}" onsubmit="return confirm('Delete this review?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs font-semibold text-red-500 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">Delete</button>
                </form>
            </div>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-star text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No reviews yet</p>
            <a href="{{ route('student.reviews.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">Write Review</a>
        </div>
        @endforelse

        @if($reviews->hasPages())
        <div>{{ $reviews->links() }}</div>
        @endif
    </div>
</section>

@endsection
