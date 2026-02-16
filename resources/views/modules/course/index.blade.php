@extends('layouts.app')

@section('title', 'Courses - Sikuna.com')

@section('content')

<!-- Breadcrumb -->
<section class="pt-[120px] pb-[30px] bg-[#f7fafc]/80">
    <div class="max-w-7xl mx-auto px-5">
        <div class="flex items-center gap-2 text-[#718096] text-[0.95rem]">
            <a href="{{ route('home') }}" class="text-[#4299e1] hover:underline">Home</a>
            <span class="text-[#718096] px-1">/</span>
            <span>Courses</span>
        </div>
    </div>
</section>

<!-- Page Header -->
<section class="py-[30px] pb-[50px] bg-white">
    <div class="max-w-7xl mx-auto px-5">
        <div class="text-center">
            <h1 class="text-[3rem] font-bold text-[#2c5aa0] mb-4 max-md:text-[2.5rem] max-sm:text-[1.8rem]">
                Explore Courses
            </h1>
            <p class="text-[1.2rem] text-[#718096] max-w-[700px] mx-auto">
                Discover thousands of courses across various disciplines from top colleges in Nepal. Find the perfect course that matches your career goals.
            </p>
        </div>
    </div>
</section>

<!-- Search and Filter Section -->
<section class="-mt-[30px] relative z-[100]">
    <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-[12px] shadow-[0_6px_20px_rgba(0,0,0,0.1)] p-10 text-white max-sm:p-6">
        <div class="max-w-7xl mx-auto px-5">
            <form method='get' action="{{ url()->current() }}" class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-5 bg-white/10 p-8 rounded-[12px] backdrop-blur-[10px] max-sm:grid-cols-1 max-sm:p-5" id="coursesSearchForm">
                <div class="flex flex-col">
                    <label class="mb-2 font-semibold" for="searchKeyword">Search Courses</label>
                    <input id="searchKeyword" type="text" placeholder="e.g., Computer Engineering, MBA, Nursing"
                        name="search"
                        value="{{ request('search') }}"
                        class="px-5 py-4 rounded-[12px] bg-white text-[#2d3748] text-[1rem] focus:outline-none focus:ring-4 focus:ring-white/30">
                </div>

                <div class="flex flex-col">
                    <label class="mb-2 font-semibold" for="searchCategory">Program Category</label>
                    <select id="searchCategory"
                        name="category"
                        class="px-5 py-4 rounded-[12px] bg-white text-[#2d3748] text-[1rem] focus:outline-none focus:ring-4 focus:ring-white/30">
                        <option value="">All Program Categories</option>
                        @foreach ($courseCategories as $category)
                        <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="mb-2 font-semibold" for="searchLevel">Level</label>
                    <select id="searchLevel"
                        name="level"
                        class="px-5 py-4 rounded-[12px] bg-white text-[#2d3748] text-[1rem] focus:outline-none focus:ring-4 focus:ring-white/30">
                        <option value="">All Levels</option>
                        @foreach ($levels as $level)
                        <option value="{{ $level->slug }}" {{ request('level') == $level->slug ? 'selected' : '' }}>{{ $level->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                    class="self-end h-[50px] px-6 rounded-[12px] font-bold text-[1.1rem] bg-white text-[#2c5aa0]
                       hover:bg-[#4299e1] hover:text-white transition inline-flex items-center justify-center gap-2">
                    <i class="fas fa-search"></i>
                    Search Courses
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Courses Main Content -->
<section class="py-[60px] bg-[#f8f9fa]">
    <div class="max-w-7xl mx-auto px-5">
        <div class="grid grid-cols-[300px_1fr] gap-10 max-xl:grid-cols-1 max-xl:gap-8">

            <!-- Sidebar Filters -->
            <form method="get" action="{{ url()->current() }}" class="bg-white rounded-[12px] p-6 shadow-[0_6px_20px_rgba(0,0,0,0.1)] border border-[#e2e8f0] h-fit max-xl:sticky max-xl:top-[120px] max-xl:z-10">
                <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-[#e2e8f0]">
                    <h3 class="text-[#2c5aa0] font-bold text-[1.3rem]">Filters</h3>
                    <button id="clearFilters" type="reset" class="text-[#4299e1] text-[0.9rem] font-semibold hover:underline">
                        Clear All
                    </button>
                </div>

                <!-- Filter Group -->
                <div class="mb-6">
                    <div class="flex items-center justify-between font-semibold text-[#2d3748] mb-4">
                        <span>Program Category</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        @foreach ($courseCategories as $category)
                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer">
                            <input class="w-[18px] h-[18px] cursor-pointer" type="checkbox" name="categories[]" value="{{ $category->slug }}" {{ in_array($category->slug, request()->get('categories', [])) ? 'checked' : '' }}>
                            <span>{{ $category->name }}</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">{{ $category->programs_count }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Level -->
                <div class="mb-6">
                    <div class="flex items-center justify-between font-semibold text-[#2d3748] mb-4">
                        <span>Level</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        @foreach ($levels as $level)
                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer">
                            <input class="w-[18px] h-[18px]" type="checkbox" name="levels[]" value="{{ $level->slug }}" {{ in_array($level->slug, request()->get('levels', [])) ? 'checked' : '' }}>
                            <span>{{ $level->name }}</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">{{ $level->courses_count }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- Duration -->
                <div>
                    <div class="flex items-center justify-between font-semibold text-[#2d3748] mb-4">
                        <span>Duration</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        @foreach ($coursesByDuration as $duration => $count)
                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer" >
                            <input class="w-[18px] h-[18px]" type="checkbox" name="durations[]" value="{{ $duration }}" {{ in_array($duration, request()->get('durations', [])) ? 'checked' : '' }}>
                            <span>{{ $duration }}</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">{{ $count }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <!-- sticky footer button -->
                <div class="mt-4 sticky bottom-0 bg-white border-t border-lightgray p-5 rounded-b-xl">
                    <button
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition">
                        <i class="fas fa-redo"></i>
                        Apply Filters
                    </button>
                </div>
            </form>

            <!-- Courses Grid Section -->
            <div class="bg-white rounded-[12px] p-8 shadow-[0_6px_20px_rgba(0,0,0,0.1)] border border-[#e2e8f0]">
                <div class="flex items-center justify-between mb-8 pb-5 border-b-2 border-[#e2e8f0] max-md:flex-col max-md:items-start max-md:gap-4">
                    <div>
                        <h2 class="text-[#2c5aa0] font-bold text-[1.8rem]">All Courses</h2>
                        <p class="text-[#718096] text-[1rem]">Showing {{ $courses->count() }} of {{ $courses->total() }} courses</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <label class="text-[#718096] text-[0.95rem]" for="sortBy">Sort by:</label>
                        <select id="sortBy" class="px-4 py-2 rounded-[12px] border border-[#e2e8f0] bg-white text-[#2d3748] text-[0.9rem]">
                            <option value="popular">Most Popular</option>
                            <option value="newest">Newest</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="rating">Highest Rated</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-7 mb-10 max-lg:grid-cols-[repeat(auto-fill,minmax(300px,1fr))] max-sm:grid-cols-1">

                    @forelse ($courses as $course)
                    @php
                    $primaryProgram = $course->programs->first();
                    @endphp
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200">
                        <div class="p-7 pb-4 border-b border-gray-200">
                            <span class="inline-block px-4 py-1.5 bg-orange-500/10 text-orange-500 rounded-full text-xs font-semibold mb-4">{{ $primaryProgram?->affiliation?->name ?? '—' }}</span>
                            <h3 class="text-2xl text-[#2c5aa0] mb-2.5 leading-tight font-bold">{{ $course->display_name }}</h3>
                            <div class="flex gap-4 text-gray-600 text-sm">
                                <span><i class="fas fa-clock"></i> {{ $primaryProgram?->duration ?? '—' }}</span>
                                <span><i class="fas fa-university"></i> {{ $primaryProgram?->level?->name ?? '—' }}</span>
                            </div>
                        </div>
                        <div class="p-5 pt-5">
                            <p class="text-gray-600 mb-5 text-sm line-clamp-3">
                                {{ $course->description }}
                            </p>
                            <div class="flex items-center justify-between pt-5 border-t border-[#e2e8f0] gap-4 max-sm:flex-col max-sm:items-start">
                                <div class="text-[1.2rem] font-extrabold text-[#2c5aa0] whitespace-nowrap">
                                    NPR 4,50,000 <span class="text-[0.85rem] text-[#718096] font-normal">/total</span>
                                </div>

                                <div class="flex items-center gap-3 w-full max-sm:justify-between">
                                    <a href="{{ route('course.show', $course) }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-[12px] text-white bg-[#4299e1] hover:bg-[#2c5aa0] transition font-semibold text-[0.9rem] whitespace-nowrap">
                                        View Details
                                    </a>

                                    <button class="inline-flex items-center justify-center w-10 h-10 rounded-[12px] border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 transition">
                                        <i class="fas fa-balance-scale"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    @endforelse
                </div>

                @include('includes.pagination', ['collections' => $courses])
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 text-center bg-gradient-to-br from-[#2c5aa0]/10 to-[#1a365d]/5">
    <div class="max-w-7xl mx-auto px-5">
        <h2 class="text-[2.5rem] font-bold text-[#2c5aa0] mb-5 max-sm:text-[2rem]">Can't Find Your Course?</h2>
        <p class="text-[1.2rem] text-[#718096] max-w-[600px] mx-auto mb-10">
            If you don't see the course you're looking for, let us know and we'll help you find it.
        </p>

        <div class="flex gap-5 justify-center flex-wrap">
            <a href="contact.html"
                class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-[12px] font-semibold text-white bg-[#4299e1] hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm">
                <i class="fas fa-headset"></i>
                Contact Us
            </a>

            <a href="index.html#search"
                class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-[12px] font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition">
                <i class="fas fa-search"></i>
                Advanced Search
            </a>
        </div>
    </div>
</section>

@endsection
