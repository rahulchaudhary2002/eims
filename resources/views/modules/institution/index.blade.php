@extends('layouts.app')

@section('title', 'Sikuna.com - Browse Colleges & Universities')

@section('content')

<!-- PAGE HERO -->
<section class="relative overflow-hidden text-white pt-[160px] pb-[60px] bg-gradient-to-br from-[#2c5aa0] to-[#1a365d]">
    <!-- right clipped glass overlay -->
    <div class="absolute top-0 right-0 w-[40%] h-full bg-gradient-to-br from-white/10 to-white/5"
        style="clip-path: polygon(100% 0, 0 0, 100% 100%);"></div>

    <div class="max-w-[1200px] mx-auto px-5 relative z-10">
        <div class="max-w-[800px] mx-auto text-center">
            <h1 class="text-[3.2rem] leading-[1.2] font-bold mb-5 max-md:text-[2.8rem] max-sm:text-[2.3rem]">
                Find Your Perfect College
            </h1>
            <p class="text-[1.2rem] text-white/90 mb-8">
                Browse and compare colleges across Nepal. Filter by location, programs, affiliation, and institution type to find the right fit for your educational journey.
            </p>

            <div class="flex flex-wrap justify-center gap-4">
                <a href="#college-list"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white text-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm">
                    <i class="fas fa-search"></i>
                    Explore Colleges
                </a>

                <a href="#compare"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-white text-white hover:bg-white/10 hover:-translate-y-0.5 transition">
                    <i class="fas fa-balance-scale"></i>
                    Compare Colleges
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ADVANCED SEARCH -->
<section id="college-list"
    class="relative z-10 -mt-10 bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.05)] max-w-[1160px] mx-auto px-5 py-10">
    <form method="get" action="{{ url()->current() }}" class="max-w-[1200px] mx-auto">
        <div class="mb-8">
            <h2 class="relative inline-block text-[2.2rem] font-bold text-[#2c5aa0] mb-2">
                Find Your College
                <span class="absolute left-0 -bottom-2 w-20 h-1 bg-[#4299e1] rounded"></span>
            </h2>
            <p class="text-grayx text-[1.1rem] max-w-[600px]">
                Use filters to narrow down your search and find colleges that match your criteria
            </p>
        </div>

        <div class="grid grid-cols-4 gap-5 mb-8 max-lg:grid-cols-2 max-sm:grid-cols-1">
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-dark text-[0.95rem]" for="collegeName">College Name</label>
                <input id="collegeName" type="text" placeholder="Search by college name"
                    name="search"
                    value="{{ request('search') }}"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition" />
            </div>

            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-dark text-[0.95rem]" for="collegeLocation">Location</label>
                <select id="collegeLocation"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition appearance-none bg-no-repeat bg-right-4 bg-[length:16px]">
                    <option value="">All Locations</option>
                    <option value="kathmandu">Kathmandu</option>
                    <option value="lalitpur">Lalitpur</option>
                    <option value="bhaktapur">Bhaktapur</option>
                    <option value="pokhara">Pokhara</option>
                    <option value="biratnagar">Biratnagar</option>
                    <option value="chitwan">Chitwan</option>
                    <option value="butwal">Butwal</option>
                </select>
            </div>

            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-dark text-[0.95rem]" for="affiliatedUniversity">Affiliated University</label>
                <select id="affiliatedUniversity"
                    name="affiliated_university"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition appearance-none bg-no-repeat bg-right-4 bg-[length:16px]">
                    <option value="">All Universities</option>
                    @foreach($affiliations as $affiliation)
                    <option value="{{ $affiliation->slug }}" {{ request('affiliated_university') === $affiliation->slug ? 'selected' : '' }}>{{ $affiliation->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-dark text-[0.95rem]" for="institutionType">Institution Type</label>
                <select id="institutionType"
                    name="institution_type"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition appearance-none bg-no-repeat bg-right-4 bg-[length:16px]">
                    <option value="">All Types</option>
                    @foreach($institutionTypes as $type)
                    <option value="{{ $type->slug }}" {{ request('institution_type') === $type->slug ? 'selected' : '' }}>{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex flex-wrap items-center justify-between gap-5">
            <div id="activeFilters" class="flex flex-wrap gap-2 flex-1"></div>

            <button id="applyFilters"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm">
                <i class="fas fa-filter"></i>
                Apply Filters
            </button>
        </div>
    </form>
</section>

<!-- COLLEGE LIST CONTAINER -->
<section class="bg-light py-20">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="grid grid-cols-[300px_1fr] gap-10 max-lg:grid-cols-1">
            <!-- SIDEBAR FILTERS -->
            <form method="get" action="{{ url()->current() }}"
                class="bg-white rounded-xl shadow-card border border-lightgray h-fit sticky top-[120px] max-h-[calc(100vh-140px)] overflow-y-auto max-lg:static max-lg:max-h-none">
                <div class="p-6">
                    <!-- FILTER: Institution Type -->
                    <div class="pb-5 mb-7 border-b border-lightgray">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[1.2rem] font-bold text-[#2c5aa0]">Institution Type</h3>
                            <span class="text-[0.8rem] text-[#4299e1] cursor-pointer" data-filter="institutionType">Clear</span>
                        </div>

                        <div class="flex flex-col gap-3">
                            @foreach($institutionTypes as $type)
                            <div class="flex items-center gap-3">
                                <input class="w-[18px] h-[18px] accent-[#4299e1]" type="checkbox" id="type-{{ $type->slug }}" name="institutionTypes[]" value="{{ $type->slug }}" {{ in_array($type->slug, request()->get('institutionTypes', [])) ? 'checked' : '' }}>
                                <label class="flex-1 cursor-pointer text-dark" for="type-{{ $type->slug }}">{{ $type->name }}</label>
                                <span class="text-[0.85rem] text-grayx">({{ $type->institutions_count }})</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- FILTER: Affiliated University -->
                    <div class="pb-5 mb-7 border-b border-lightgray">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[1.2rem] font-bold text-[#2c5aa0]">Affiliated University</h3>
                            <span class="text-[0.8rem] text-[#4299e1] cursor-pointer" data-filter="affiliatedUniversity">Clear</span>
                        </div>

                        <div class="flex flex-col gap-3">
                            @foreach($affiliations as $affiliation)
                            <div class="flex items-center gap-3">
                                <input class="w-[18px] h-[18px] accent-[#4299e1]" type="checkbox" id="aff-{{ $affiliation->slug }}" name="affiliatedUniversities[]" value="{{ $affiliation->slug }}" {{ in_array($affiliation->slug, request()->get('affiliatedUniversities', [])) ? 'checked' : '' }}>
                                <label class="flex-1 cursor-pointer text-dark" for="aff-{{ $affiliation->slug }}">{{ $affiliation->name }}</label>
                                <span class="text-[0.85rem] text-grayx">({{ $affiliation->institutions_count }})</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- FILTER: College Category -->
                    <div class="pb-5 mb-7 border-b border-lightgray">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[1.2rem] font-bold text-[#2c5aa0]">College Category</h3>
                            <span class="text-[0.8rem] text-[#4299e1] cursor-pointer" data-filter="category">Clear</span>
                        </div>
                        <div class="flex flex-col gap-3">
                            @foreach($institutionCategories as $category)
                            <div class="flex items-center gap-3">
                                <input class="w-[18px] h-[18px] accent-[#4299e1]" type="checkbox" id="cat-{{ $category->slug }}" name="categories[]" value="{{ $category->slug }}" {{ in_array($category->slug, request()->get('categories', [])) ? 'checked' : '' }}>
                                <label class="flex-1 cursor-pointer text-dark" for="cat-{{ $category->slug }}">{{ $category->name }}</label>
                                <span class="text-[0.85rem] text-grayx">({{ $category->institutions_count }})</span>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- FILTER: Location -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[1.2rem] font-bold text-[#2c5aa0]">Location</h3>
                            <span class="text-[0.8rem] text-[#4299e1] cursor-pointer" data-filter="location">Clear</span>
                        </div>

                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-3">
                                <input class="w-[18px] h-[18px] accent-[#4299e1]" type="checkbox" id="loc-kathmandu" name="location" value="kathmandu">
                                <label class="flex-1 cursor-pointer text-dark" for="loc-kathmandu">Kathmandu</label>
                                <span class="text-[0.85rem] text-grayx">(58)</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <input class="w-[18px] h-[18px] accent-[#4299e1]" type="checkbox" id="loc-lalitpur" name="location" value="lalitpur">
                                <label class="flex-1 cursor-pointer text-dark" for="loc-lalitpur">Lalitpur</label>
                                <span class="text-[0.85rem] text-grayx">(32)</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <input class="w-[18px] h-[18px] accent-[#4299e1]" type="checkbox" id="loc-pokhara" name="location" value="pokhara">
                                <label class="flex-1 cursor-pointer text-dark" for="loc-pokhara">Pokhara</label>
                                <span class="text-[0.85rem] text-grayx">(24)</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <input class="w-[18px] h-[18px] accent-[#4299e1]" type="checkbox" id="loc-biratnagar" name="location" value="biratnagar">
                                <label class="flex-1 cursor-pointer text-dark" for="loc-biratnagar">Biratnagar</label>
                                <span class="text-[0.85rem] text-grayx">(18)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- sticky footer button -->
                <div class="sticky bottom-0 bg-white border-t border-lightgray p-5 rounded-b-xl">
                    <button
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition">
                        <i class="fas fa-redo"></i>
                        Apply Filters
                    </button>
                </div>
            </form>

            <!-- GRID CONTENT -->
            <div>
                <div class="flex items-center justify-between mb-8 max-md:flex-col max-md:items-start max-md:gap-4">
                    <div class="text-[1.1rem] text-dark">
                        Showing <strong class="text-[#2c5aa0]">{{ $institutions->count() }}</strong> of <strong class="text-[#2c5aa0]">{{ $institutions->total() }}</strong> colleges
                    </div>

                    <form method="get" action="{{ url()->current() }}">
                        {{-- keep all existing query params --}}
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
                            class="px-4 py-2.5 rounded-xl border border-lightgray bg-white text-[0.95rem] cursor-pointer">
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>
                                Sort by: Name (A-Z)
                            </option>
                            <option value="established_year" {{ request('sort') === 'established_year' ? 'selected' : '' }}>
                                Sort by: Established Year
                            </option>
                        </select>
                    </form>
                </div>

                <div class="grid grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-7 mb-12 max-lg:grid-cols-[repeat(auto-fill,minmax(300px,1fr))] max-sm:grid-cols-1">
                    @forelse($institutions as $institution)
                    <div class="group bg-white rounded-xl overflow-hidden shadow-card border border-lightgray flex flex-col h-full hover:-translate-y-1 hover:shadow-hover transition"
                        data-college-id="{{ $institution->id }}">
                        <div class="relative h-[180px] overflow-hidden">
                            <a href="{{ route('institution.show', ['institution_slug' => $institution->slug]) }}" class="block w-full h-full">
                                <img class="w-full h-full object-cover transition group-hover:scale-[1.05]"
                                    src="{{ Storage::url($institution->cover_image) }}"
                                    alt="{{ $institution->name }}">
                            </a>

                            <!-- <span class="absolute top-4 left-4 bg-white/90 text-[#2c5aa0] px-4 py-1 rounded-full text-[0.75rem] font-extrabold shadow">
                                Featured
                            </span>

                            <div class="college-bookmark absolute top-4 right-4 w-9 h-9 rounded-full bg-white/90 flex items-center justify-center cursor-pointer text-grayx hover:bg-white hover:text-warning transition z-10"
                                data-college-id="{{ $institution->id }}">
                                <i class="far fa-bookmark"></i>
                            </div> -->
                        </div>

                        <div class="p-6 flex flex-col flex-1">
                            <span class="inline-flex self-start px-4 py-1 rounded-full text-[0.85rem] font-semibold bg-success/10 text-success mb-4">
                                {{ $institution->category->name }}
                            </span>

                            <h3 class="text-[1.4rem] font-bold text-[#2c5aa0] mb-2 leading-snug">
                                <a class="hover:text-[#4299e1] transition" href="{{ route('institution.show', ['institution_slug' => $institution->slug]) }}">{{ $institution->name }}</a>
                            </h3>

                            <div class="flex items-center gap-2 text-[0.95rem] text-grayx mb-4">
                                <i class="fas fa-map-marker-alt text-[#4299e1]"></i>
                                <span>{{ $institution->address }}</span>
                            </div>

                            <div class="flex items-center justify-between gap-4 mb-5 pb-5 border-b border-lightgray max-md:flex-col max-md:items-start">
                                <div class="flex items-center gap-2 text-[0.9rem] text-grayx">
                                    <i class="fas fa-university text-[#4299e1]"></i>
                                    <span>{{ $institution->affiliations->pluck('name')->join(', ') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-[0.9rem] text-grayx">
                                    <i class="fas fa-building text-[#4299e1]"></i>
                                    <span>{{ $institution->institutionType->name }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3 mb-5 max-sm:grid-cols-1">
                                <div class="flex items-center gap-2 text-[0.9rem] text-grayx">
                                    <i class="fas fa-graduation-cap text-[#4299e1] w-4"></i>
                                    <span>{{ $institution->courses_count }}+ Programs</span>
                                </div>
                                <!-- <div class="flex items-center gap-2 text-[0.9rem] text-grayx">
                                    <i class="fas fa-users text-[#4299e1] w-4"></i>
                                    <span>{{ $institution->students_count }} Students</span>
                                </div> -->
                                <div class="flex items-center gap-2 text-[0.9rem] text-grayx">
                                    <i class="fas fa-calendar-alt text-[#4299e1] w-4"></i>
                                    <span>Est. {{ $institution->established_year }}</span>
                                </div>
                            </div>

                            <div class="mt-auto pt-5 border-t border-lightgray flex items-center gap-3 max-sm:flex-col">
                                <a href="{{ route('institution.show', ['institution_slug' => $institution->slug]) }}"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-semibold text-white bg-[#4299e1] hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </a>
                                <button class="compare-btn flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-semibold text-white bg-[#4299e1] hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition"
                                    data-college-id="{{ $institution->id }}">
                                    <i class="fas fa-plus"></i>
                                    Compare
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-20">
                        <h3 class="text-[1.5rem] font-bold text-grayx mb-3">No colleges found matching your criteria.</h3>
                        <p class="text-grayx text-[1rem]">Try adjusting your filters or search terms to find what you're looking for.</p>
                    </div>
                    @endforelse
                </div>

                @include('includes.pagination', ['collections' => $institutions])
            </div>
        </div>
    </div>
</section>

<!-- COMPARISON BAR -->
<div id="comparisonBar"
    class="fixed bottom-0 left-0 w-full bg-white shadow-[0_-5px_20px_rgba(0,0,0,0.1)] py-4 z-[999] translate-y-full transition-transform duration-300">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="flex items-center justify-between gap-4 max-md:flex-col max-md:items-start">
            <div id="comparisonItems" class="flex gap-4 overflow-x-auto py-1 flex-1">
                <!-- items injected by JS -->
            </div>

            <button id="compareNowBtn"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition">
                <i class="fas fa-balance-scale"></i>
                Compare Now (0)
            </button>
        </div>
    </div>
</div>

<!-- CTA -->
<section class="py-20 text-center bg-gradient-to-br from-[#2c5aa0]/10 to-[#1a365d]/5">
    <div class="max-w-[1200px] mx-auto px-5">
        <h2 class="text-[2.5rem] font-bold text-[#2c5aa0] mb-5">Need Help Choosing a College?</h2>
        <p class="text-[1.2rem] text-grayx max-w-[600px] mx-auto mb-10">
            Our education experts can help you find the perfect college based on your interests, budget, and career goals.
        </p>

        <div class="flex flex-wrap justify-center gap-4">
            <a href="consultation.html"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm">
                <i class="fas fa-user-graduate"></i>
                Book Free Consultation
            </a>

            <a href="contact.html"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition">
                <i class="fas fa-headset"></i>
                Contact Our Advisors
            </a>
        </div>
    </div>
</section>

@endsection