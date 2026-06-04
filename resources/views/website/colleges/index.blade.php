@extends('website.layouts.app')

@section('meta-title', 'Colleges - ' . config('app.name'))
@section('meta-description', 'Browse and filter top colleges across Nepal. Find the right college by location, programs, and more.')

@section('content')

<section class="relative overflow-hidden text-white pt-[160px] pb-[60px] bg-gradient-to-br from-[#2c5aa0] to-[#1a365d]">
    <div class="absolute top-0 right-0 w-[40%] h-full bg-gradient-to-br from-white/10 to-white/5"
        style="clip-path: polygon(100% 0, 0 0, 100% 100%);"></div>

    <div class="max-w-[1200px] mx-auto px-5 relative z-10">
        <div class="max-w-[800px] mx-auto text-center">
            <h1 class="text-[3.2rem] leading-[1.2] font-bold mb-5 max-md:text-[2.8rem] max-sm:text-[2.3rem]">
                Find Your Perfect College
            </h1>
            <p class="text-[1.2rem] text-white/90 mb-8">
                Browse and compare colleges across Nepal. Filter by location and programs to find the right fit for your educational journey.
            </p>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="#college-list"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white text-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm no-underline">
                    <i class="fas fa-search"></i>
                    Explore Colleges
                </a>

                <a href="{{ route('website.compare.index') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-white text-white hover:bg-white/10 hover:-translate-y-0.5 transition no-underline">
                    <i class="fas fa-balance-scale"></i>
                    Compare Colleges
                </a>
            </div>
        </div>
    </div>
</section>

<section id="college-list"
    class="relative z-10 -mt-10 bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.05)] max-w-[1160px] mx-auto px-5 py-10">
    <form method="get" action="{{ route('website.colleges.index') }}" class="max-w-[1200px] mx-auto">
        <div class="mb-8">
            <h2 class="relative inline-block text-[2.2rem] font-bold text-[#2c5aa0] mb-2">
                Find Your College
                <span class="absolute left-0 -bottom-2 w-20 h-1 bg-[#4299e1] rounded"></span>
            </h2>
            <p class="text-gray-600 text-[1.1rem] max-w-[600px]">
                Use filters to narrow down your search and find colleges that match your criteria.
            </p>
        </div>

        <div class="grid grid-cols-3 gap-5 mb-8 max-lg:grid-cols-2 max-sm:grid-cols-1">
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]" for="collegeName">College Name</label>
                <input id="collegeName" type="text" placeholder="Search by college name"
                    name="search"
                    value="{{ request('search') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition" />
            </div>

            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]" for="collegeLocation">Location</label>
                <select id="collegeLocation" name="province"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    <option value="">All Locations</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province }}" {{ request('province') === $province ? 'selected' : '' }}>{{ $province }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]" for="faculty">Faculty / Stream</label>
                <select id="faculty" name="faculty"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    <option value="">All Faculties</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->slug }}" {{ request('faculty') === $faculty->slug ? 'selected' : '' }}>{{ $faculty->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-5">
            <div class="flex flex-wrap gap-2 flex-1">
                @foreach (['is_verified' => 'Verified', 'is_featured' => 'Featured'] as $name => $label)
                    <label class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] text-[0.9rem] font-semibold cursor-pointer">
                        <input type="checkbox" name="{{ $name }}" value="1" {{ request($name) ? 'checked' : '' }} class="accent-[#4299e1]">
                        {{ $label }}
                    </label>
                @endforeach
            </div>

            <button id="applyFilters"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm">
                <i class="fas fa-filter"></i>
                Apply Filters
            </button>
        </div>
    </form>
</section>

<section class="bg-[#f7fafc] py-20">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="grid grid-cols-[300px_1fr] gap-10 max-lg:grid-cols-1">
            <form method="get" action="{{ route('website.colleges.index') }}"
                class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-200 h-fit sticky top-[120px] max-h-[calc(100vh-140px)] overflow-y-auto max-lg:static max-lg:max-h-none">
                <div class="p-6">
                    <div class="pb-5 mb-7 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[1.2rem] font-bold text-[#2c5aa0]">Faculty / Stream</h3>
                            <a href="{{ route('website.colleges.index', request()->except(['faculty', 'page'])) }}" class="text-[0.8rem] text-[#4299e1] no-underline">Clear</a>
                        </div>

                        <div class="flex flex-col gap-3">
                            @foreach($faculties->take(8) as $faculty)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input class="w-[18px] h-[18px] accent-[#4299e1]" type="radio" name="faculty" value="{{ $faculty->slug }}" {{ request('faculty') === $faculty->slug ? 'checked' : '' }}>
                                    <span class="flex-1 text-[#2d3748]">{{ $faculty->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[1.2rem] font-bold text-[#2c5aa0]">Location</h3>
                            <a href="{{ route('website.colleges.index', request()->except(['province', 'page'])) }}" class="text-[0.8rem] text-[#4299e1] no-underline">Clear</a>
                        </div>

                        <div class="flex flex-col gap-3">
                            @foreach($provinces->take(8) as $province)
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input class="w-[18px] h-[18px] accent-[#4299e1]" type="radio" name="province" value="{{ $province }}" {{ request('province') === $province ? 'checked' : '' }}>
                                    <span class="flex-1 text-[#2d3748]">{{ $province }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-5 rounded-b-xl">
                    <button
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition">
                        <i class="fas fa-redo"></i>
                        Apply Filters
                    </button>
                </div>
            </form>

            <div>
                <div class="flex items-center justify-between mb-8 max-md:flex-col max-md:items-start max-md:gap-4">
                    <div class="text-[1.1rem] text-[#2d3748]">
                        Showing <strong class="text-[#2c5aa0]">{{ $colleges->count() }}</strong> of <strong class="text-[#2c5aa0]">{{ $colleges->total() }}</strong> colleges
                    </div>

                    <form method="get" action="{{ route('website.colleges.index') }}">
                        @foreach(request()->query() as $key => $value)
                            @if(!in_array($key, ['sort','page']))
                                @if(is_array($value))
                                    @foreach($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endif
                        @endforeach

                        <select name="sort" onchange="this.form.submit()"
                            class="px-4 py-2.5 rounded-xl border border-gray-200 bg-white text-[0.95rem] cursor-pointer">
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Sort by: Name (A-Z)</option>
                            <option value="established_year" {{ request('sort') === 'established_year' ? 'selected' : '' }}>Sort by: Established Year</option>
                        </select>
                    </form>
                </div>

                <div class="grid grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-7 mb-12 max-lg:grid-cols-[repeat(auto-fill,minmax(300px,1fr))] max-sm:grid-cols-1">
                    @forelse($colleges as $institution)
                        @include('website.partials.institution-card', ['institution' => $institution])
                    @empty
                        <div class="col-span-full text-center py-20">
                            <h3 class="text-[1.5rem] font-bold text-gray-600 mb-3">No colleges found matching your criteria.</h3>
                            <p class="text-gray-600 text-[1rem]">Try adjusting your filters or search terms to find what you're looking for.</p>
                        </div>
                    @endforelse
                </div>

                @include('website.partials.pagination', ['paginator' => $colleges])
            </div>
        </div>
    </div>
</section>

<div id="comparisonBar"
    class="fixed bottom-0 left-0 w-full bg-white shadow-[0_-5px_20px_rgba(0,0,0,0.1)] py-4 z-[999] translate-y-full transition-transform duration-300">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="flex items-center justify-between gap-4 max-md:flex-col max-md:items-start">
            <div class="flex gap-4 overflow-x-auto py-1 flex-1"></div>

            <a href="{{ route('website.compare.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition no-underline">
                <i class="fas fa-balance-scale"></i>
                Compare Now
            </a>
        </div>
    </div>
</div>

<section class="py-20 text-center bg-gradient-to-br from-[#2c5aa0]/10 to-[#1a365d]/5">
    <div class="max-w-[1200px] mx-auto px-5">
        <h2 class="text-[2.5rem] font-bold text-[#2c5aa0] mb-5">Need Help Choosing a College?</h2>
        <p class="text-[1.2rem] text-gray-600 max-w-[600px] mx-auto mb-10">
            Our education experts can help you find the perfect college based on your interests, budget, and career goals.
        </p>

        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('website.inquiry.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm no-underline">
                <i class="fas fa-user-graduate"></i>
                Submit Inquiry
            </a>

            <a href="{{ route('website.contact.index') }}"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition no-underline">
                <i class="fas fa-headset"></i>
                Contact Our Advisors
            </a>
        </div>
    </div>
</section>

@endsection
