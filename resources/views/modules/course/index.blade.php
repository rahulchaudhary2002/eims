@extends('layouts.app')

@section('title', 'Courses - Sikuna.com')

@section('content')
{{--
<!-- Breadcrumb -->
<section class="pt-[120px] pb-[30px] bg-[#f7fafc]/80">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="flex items-center gap-2 text-[#718096] text-[0.95rem]">
            <a href="index.html" class="text-[#4299e1] hover:underline">Home</a>
            <span class="text-[#718096] px-1">/</span>
            <span>Courses</span>
        </div>
    </div>
</section>

<!-- Page Header -->
<section class="py-[30px] pb-[50px] bg-white">
    <div class="max-w-[1200px] mx-auto px-5">
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
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-[12px] shadow-[0_6px_20px_rgba(0,0,0,0.1)] p-10 text-white max-sm:p-6">
            <form class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-5 bg-white/10 p-8 rounded-[12px] backdrop-blur-[10px] max-sm:grid-cols-1 max-sm:p-5" id="coursesSearchForm">
                <div class="flex flex-col">
                    <label class="mb-2 font-semibold" for="searchKeyword">Search Courses</label>
                    <input id="searchKeyword" type="text" placeholder="e.g., Computer Engineering, MBA, Nursing"
                        class="px-5 py-4 rounded-[12px] bg-white text-[#2d3748] text-[1rem] focus:outline-none focus:ring-4 focus:ring-white/30">
                </div>

                <div class="flex flex-col">
                    <label class="mb-2 font-semibold" for="searchCategory">Category</label>
                    <select id="searchCategory"
                        class="px-5 py-4 rounded-[12px] bg-white text-[#2d3748] text-[1rem] focus:outline-none focus:ring-4 focus:ring-white/30">
                        <option value="">All Categories</option>
                        <option value="engineering">Engineering</option>
                        <option value="management">Management</option>
                        <option value="medicine">Medicine</option>
                        <option value="science">Science</option>
                        <option value="humanities">Humanities</option>
                        <option value="law">Law</option>
                        <option value="it">Information Technology</option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="mb-2 font-semibold" for="searchLevel">Level</label>
                    <select id="searchLevel"
                        class="px-5 py-4 rounded-[12px] bg-white text-[#2d3748] text-[1rem] focus:outline-none focus:ring-4 focus:ring-white/30">
                        <option value="">All Levels</option>
                        <option value="plus2">+2/Intermediate</option>
                        <option value="bachelor">Bachelor's</option>
                        <option value="master">Master's</option>
                        <option value="phd">PhD</option>
                        <option value="diploma">Diploma/Certificate</option>
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
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="grid grid-cols-[300px_1fr] gap-10 max-xl:grid-cols-1 max-xl:gap-8">

            <!-- Sidebar Filters -->
            <aside class="bg-white rounded-[12px] p-6 shadow-[0_6px_20px_rgba(0,0,0,0.1)] border border-[#e2e8f0] h-fit max-xl:sticky max-xl:top-[120px] max-xl:z-10">
                <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-[#e2e8f0]">
                    <h3 class="text-[#2c5aa0] font-bold text-[1.3rem]">Filters</h3>
                    <button id="clearFilters" type="button" class="text-[#4299e1] text-[0.9rem] font-semibold hover:underline">
                        Clear All
                    </button>
                </div>

                <!-- Filter Group -->
                <div class="mb-6">
                    <div class="flex items-center justify-between font-semibold text-[#2d3748] mb-4">
                        <span>Category</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer">
                            <input class="w-[18px] h-[18px] cursor-pointer" type="checkbox" name="category" value="engineering">
                            <span>Engineering</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">45</span>
                        </label>

                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer">
                            <input class="w-[18px] h-[18px] cursor-pointer" type="checkbox" name="category" value="management">
                            <span>Management</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">38</span>
                        </label>

                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer">
                            <input class="w-[18px] h-[18px] cursor-pointer" type="checkbox" name="category" value="medicine">
                            <span>Medicine</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">22</span>
                        </label>

                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer">
                            <input class="w-[18px] h-[18px] cursor-pointer" type="checkbox" name="category" value="it">
                            <span>IT & Computer</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">56</span>
                        </label>
                    </div>
                </div>

                <!-- Level -->
                <div class="mb-6">
                    <div class="flex items-center justify-between font-semibold text-[#2d3748] mb-4">
                        <span>Level</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer">
                            <input class="w-[18px] h-[18px]" type="checkbox" name="level" value="bachelor">
                            <span>Bachelor's</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">89</span>
                        </label>

                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer">
                            <input class="w-[18px] h-[18px]" type="checkbox" name="level" value="master">
                            <span>Master's</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">42</span>
                        </label>
                    </div>
                </div>

                <!-- Duration -->
                <div>
                    <div class="flex items-center justify-between font-semibold text-[#2d3748] mb-4">
                        <span>Duration</span>
                    </div>

                    <div class="flex flex-col gap-3">
                        <label class="flex items-center gap-3 text-[#718096] hover:text-[#2d3748] transition cursor-pointer">
                            <input class="w-[18px] h-[18px]" type="checkbox" name="duration" value="4year">
                            <span>4 Years</span>
                            <span class="ml-auto bg-[#4299e1]/10 text-[#4299e1] px-2 py-[2px] rounded-full text-[0.8rem] font-semibold">89</span>
                        </label>
                    </div>
                </div>
            </aside>

            <!-- Courses Grid Section -->
            <div class="bg-white rounded-[12px] p-8 shadow-[0_6px_20px_rgba(0,0,0,0.1)] border border-[#e2e8f0]">
                <div class="flex items-center justify-between mb-8 pb-5 border-b-2 border-[#e2e8f0] max-md:flex-col max-md:items-start max-md:gap-4">
                    <div>
                        <h2 class="text-[#2c5aa0] font-bold text-[1.8rem]">All Courses</h2>
                        <p class="text-[#718096] text-[1rem]">Showing 12 of 249 courses</p>
                    </div>

                    <div class="flex items-center gap-3 w-full max-md:justify-between">
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

                    <!-- Course Card -->
                    <div class="bg-white rounded-[12px] overflow-hidden shadow-[0_6px_20px_rgba(0,0,0,0.1)] border border-[#e2e8f0] hover:-translate-y-1 hover:shadow-[0_15px_30px_rgba(0,0,0,0.15)] transition">
                        <div class="relative p-6 pb-4 border-b border-[#e2e8f0]">
                            <span class="absolute top-4 right-4 bg-[#ed8936] text-white px-3 py-1 rounded-full text-[0.7rem] font-extrabold uppercase">
                                Featured
                            </span>

                            <div class="flex items-center gap-3 mb-4">
                                <img class="w-8 h-8 rounded-md object-cover border-2 border-[#e2e8f0]"
                                    src="https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&auto=format&fit=crop&w=100&q=80" alt="">
                                <span class="inline-flex px-4 py-1 rounded-full text-[0.85rem] font-semibold bg-[#4299e1]/10 text-[#4299e1]">
                                    Prime College
                                </span>
                            </div>

                            <h3 class="text-[1.4rem] font-bold text-[#2c5aa0] mb-2 leading-snug">
                                Bachelor in Information Management (BIM)
                            </h3>

                            <div class="flex items-center gap-2 text-[#718096] text-[0.95rem]">
                                <i class="fas fa-graduation-cap text-[#4299e1]"></i>
                                <span>4 Years • Bachelor's Degree</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="flex flex-col gap-3 mb-5">
                                <div class="flex items-center gap-3 text-[#718096] text-[0.9rem]">
                                    <i class="fas fa-check-circle text-[#4299e1]"></i>
                                    <span>Industry-focused curriculum</span>
                                </div>
                                <div class="flex items-center gap-3 text-[#718096] text-[0.9rem]">
                                    <i class="fas fa-check-circle text-[#4299e1]"></i>
                                    <span>Internship opportunities</span>
                                </div>
                                <div class="flex items-center gap-3 text-[#718096] text-[0.9rem]">
                                    <i class="fas fa-check-circle text-[#4299e1]"></i>
                                    <span>Modern computer labs</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-5 border-t border-[#e2e8f0] gap-4 max-sm:flex-col max-sm:items-start">
                                <div class="text-[1.5rem] font-extrabold text-[#2c5aa0] whitespace-nowrap">
                                    NPR 4,50,000 <span class="text-[0.85rem] text-[#718096] font-normal">/total</span>
                                </div>

                                <div class="flex items-center gap-3 w-full max-sm:justify-between">
                                    <a href="course-details.html"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-[12px] text-white bg-[#4299e1] hover:bg-[#2c5aa0] transition font-semibold text-[0.9rem] whitespace-nowrap">
                                        View Details <i class="fas fa-arrow-right"></i>
                                    </a>

                                    <button class="inline-flex items-center justify-center w-10 h-10 rounded-[12px] border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 transition">
                                        <i class="fas fa-balance-scale"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Duplicate course card blocks here... -->

                </div>

                <!-- Pagination -->
                <div class="flex items-center justify-center gap-2 mt-10 flex-wrap">
                    <button class="w-10 h-10 rounded-full border border-[#e2e8f0] bg-white text-[#2d3748] opacity-50 cursor-not-allowed flex items-center justify-center">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full border border-[#4299e1] bg-[#4299e1] text-white flex items-center justify-center">1</button>
                    <button class="w-10 h-10 rounded-full border border-[#e2e8f0] bg-white text-[#2d3748] hover:bg-[#4299e1] hover:text-white hover:border-[#4299e1] transition flex items-center justify-center">2</button>
                    <button class="w-10 h-10 rounded-full border border-[#e2e8f0] bg-white text-[#2d3748] hover:bg-[#4299e1] hover:text-white hover:border-[#4299e1] transition flex items-center justify-center">3</button>
                    <button class="w-10 h-10 rounded-full border border-[#e2e8f0] bg-white text-[#2d3748] hover:bg-[#4299e1] hover:text-white hover:border-[#4299e1] transition flex items-center justify-center">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Courses -->
<section class="py-20 bg-[#f7fafc]/80">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="text-center mb-12">
            <h2 class="text-[2.2rem] font-bold text-[#2c5aa0] inline-block relative mb-4">
                Featured Courses
                <span class="absolute left-1/2 -bottom-2 -translate-x-1/2 w-20 h-1 bg-[#4299e1] rounded"></span>
            </h2>
            <p class="text-[#718096] text-[1.1rem]">Top-rated courses recommended for you</p>
        </div>

        <div class="grid grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-7 max-lg:grid-cols-[repeat(auto-fill,minmax(300px,1fr))] max-sm:grid-cols-1">
            <!-- reuse same course card markup -->
            <!-- (copy the course card above, change badge/title/price) -->
        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-20 text-center bg-gradient-to-br from-[#2c5aa0]/10 to-[#1a365d]/5">
    <div class="max-w-[1200px] mx-auto px-5">
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
--}}
<section class="mt-24 pb-8 px-4" id="courses">
    <div class="container max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Popular Courses</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Explore trending courses across various disciplines</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse ($courses as $course)
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200">
                <div class="p-7 pb-4 border-b border-gray-200">
                    <span class="inline-block px-4 py-1.5 bg-orange-500/10 text-orange-500 rounded-full text-xs font-semibold mb-4">{{ $course->affiliation->name }}</span>
                    <h3 class="text-2xl text-[#2c5aa0] mb-2.5 leading-tight font-bold">{{ $course->display_name }}</h3>
                    <div class="flex gap-4 text-gray-600 text-sm">
                        <span><i class="fas fa-clock"></i> {{ $course->duration }}</span>
                        <span><i class="fas fa-university"></i> {{ $course->level->name }}</span>
                    </div>
                </div>
                <div class="p-5 pt-5">
                    <p class="text-gray-600 mb-5 text-sm line-clamp-3">
                        {{ $course->description }}
                    </p>
                    <div class="flex justify-between items-center">
                        <!-- <div class="text-2xl font-bold text-[#2c5aa0] whitespace-nowrap">NPR 1,25,000<small class="text-sm text-gray-600 font-normal">/year</small></div> -->
                        <a href="{{ route('course.show', $course) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold cursor-pointer transition-all bg-[#4299e1] text-white text-sm hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-lg">Details</a>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>
        <div class="mt-8">
            {{ $courses->links() }}
        </div>
    </div>
</section>

@endsection