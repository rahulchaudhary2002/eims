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
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                <form method="POST" action="{{ route('student.reviews.store') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Institution <span class="text-red-500">*</span></label>
                        <select name="institution_id" class="w-full px-4 py-3 text-sm border {{ $errors->has('institution_id') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]">
                            <option value="">Select institution</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ $selected?->id == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                        @error('institution_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-2">Rating <span class="text-red-500">*</span></label>
                        <div class="flex items-center gap-2" x-data="{ rating: {{ old('rating', 0) }} }">
                            @for($i = 1; $i <= 5; $i++)
                            <button type="button" @click="rating = {{ $i }}" class="transition-transform hover:scale-110 focus:outline-none">
                                <i class="fas fa-star text-2xl cursor-pointer transition-colors"
                                   :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'"></i>
                            </button>
                            @endfor
                            <input type="hidden" name="rating" :value="rating">
                        </div>
                        @error('rating')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Review <span class="text-red-500">*</span></label>
                        <textarea name="review" rows="5"
                                  class="w-full px-4 py-3 text-sm border {{ $errors->has('review') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]"
                                  placeholder="Share your honest experience (min 10 characters)...">{{ old('review') }}</textarea>
                        @error('review')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('student.reviews.index') }}"
                           class="px-5 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 no-underline">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:opacity-90 transition">
                            <i class="fas fa-star"></i> Submit Review
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@endsection
