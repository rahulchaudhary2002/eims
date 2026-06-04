@extends('layouts.student')

@section('title', 'Edit Review')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.reviews.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Edit Review</h1>
                <p class="text-white/70 text-sm mt-1">{{ $review->institution?->name }}</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="student-form-shell">
            <div class="student-form-card">
                <div class="student-form-header">
                    <h2 class="student-form-title">Edit Review</h2>
                    <p class="student-form-description">Refine your feedback without losing the same form structure used across the core student experience.</p>
                </div>

                @if ($errors->any())
                    <div class="student-form-errors">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('student.reviews.update', $review) }}" class="space-y-5">
                    @csrf @method('PUT')
                    <div class="student-form-info">
                        <i class="fas fa-building mt-0.5"></i>
                        <p class="text-sm font-semibold text-gray-700">{{ $review->institution?->name }}</p>
                    </div>
                    <div>
                        <label class="student-form-label">Rating <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2" x-data="{ rating: {{ old('rating', $review->rating) }} }">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating = {{ $i }}" class="transition-transform hover:scale-110 focus:outline-none">
                                <i class="fas fa-star text-2xl cursor-pointer" :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'"></i>
                            </button>
                            @endfor
                            <input type="hidden" name="rating" :value="rating">
                        </div>
                    </div>
                    <div>
                        <label class="student-form-label">Review <span class="text-red-500">*</span></label>
                        <textarea name="review" rows="5"
                                  class="student-form-control student-form-textarea">{{ old('review', $review->review) }}</textarea>
                    </div>
                    <div class="student-form-actions">
                        <a href="{{ route('student.reviews.index') }}"
                           class="student-form-btn-secondary">Cancel</a>
                        <button type="submit"
                            class="student-form-btn-primary">
                            <i class="fas fa-save"></i> Update Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
