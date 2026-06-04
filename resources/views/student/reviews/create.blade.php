@extends('layouts.student')

@section('title', 'Write Review')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.reviews.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Write a Review</h1>
                <p class="text-white/70 text-sm mt-1">Share your experience with an institution</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="student-form-shell">
            <div class="student-form-card">
                <div class="student-form-header">
                    <h2 class="student-form-title">Write Review</h2>
                    <p class="student-form-description">Share your experience in the same high-clarity form style used across the website’s main forms.</p>
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

                <form method="POST" action="{{ route('student.reviews.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="student-form-label">Institution <span class="text-red-500">*</span></label>
                        <select name="institution_id" class="student-form-control student-form-select {{ $errors->has('institution_id') ? 'is-invalid' : '' }}">
                            <option value="">Select institution</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ $selected?->id == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                        @error('institution_id')<p class="student-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="student-form-label">Rating <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2" x-data="{ rating: {{ old('rating', 0) }} }">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating = {{ $i }}" class="transition-transform hover:scale-110 focus:outline-none">
                                <i class="fas fa-star text-2xl cursor-pointer transition-colors"
                                   :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'"></i>
                            </button>
                            @endfor
                            <input type="hidden" name="rating" :value="rating">
                        </div>
                        @error('rating')<p class="student-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="student-form-label">Review <span class="text-red-500">*</span></label>
                        <textarea name="review" rows="5"
                                  class="student-form-control student-form-textarea {{ $errors->has('review') ? 'is-invalid' : '' }}"
                                  placeholder="Share your honest experience (min 10 characters)...">{{ old('review') }}</textarea>
                        @error('review')<p class="student-form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="student-form-actions">
                        <a href="{{ route('student.reviews.index') }}"
                           class="student-form-btn-secondary">Cancel</a>
                        <button type="submit"
                            class="student-form-btn-primary">
                            <i class="fas fa-star"></i> Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
