@extends('website.layouts.app')

@section('meta-title', 'Courses - ' . config('app.name'))
@section('meta-description', 'Browse skill-based and professional courses offered by top institutions. Filter by institution, name, and fee.')

@section('content')
<section class="relative overflow-hidden text-white pt-[160px] pb-[60px] bg-gradient-to-br from-[#2c5aa0] to-[#1a365d]">
    <div class="absolute top-0 right-0 w-[40%] h-full bg-gradient-to-br from-white/10 to-white/5" style="clip-path: polygon(100% 0, 0 0, 100% 100%);"></div>
    <div class="max-w-[1200px] mx-auto px-5 relative z-10">
        <div class="max-w-[800px] mx-auto text-center">
            <h1 class="text-[3.2rem] leading-[1.2] font-bold mb-5 max-md:text-[2.8rem] max-sm:text-[2.3rem]">Explore Courses</h1>
            <p class="text-[1.2rem] text-white/90 mb-8">Discover skill-based and professional courses offered by leading institutions across Nepal.</p>
            <a href="#course-list" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white text-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm no-underline">
                <i class="fas fa-search"></i> Browse Courses
            </a>
        </div>
    </div>
</section>

{{-- Top search bar --}}
<section id="course-list" class="relative z-10 -mt-10 bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.05)] max-w-[1160px] mx-auto px-5 py-10">
    <form method="get" action="{{ route('website.courses.index') }}">
        <div class="mb-8">
            <h2 class="relative inline-block text-[2.2rem] font-bold text-[#2c5aa0] mb-2">Find a Course<span class="absolute left-0 -bottom-2 w-20 h-1 bg-[#4299e1] rounded"></span></h2>
            <p class="text-gray-600 text-[1.1rem] max-w-[600px]">Filter by name, institution, or maximum fee.</p>
        </div>
        <div class="grid grid-cols-4 gap-5 mb-6 max-lg:grid-cols-2 max-sm:grid-cols-1">
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Course Name</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by course name"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
            </div>
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Institution</label>
                <select name="institution" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    <option value="">All Institutions</option>
                    @foreach ($institutions as $inst)
                        <option value="{{ $inst->id }}" {{ request('institution') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Max Fee (NPR)</label>
                <input type="number" name="fee_max" value="{{ request('fee_max') }}" min="0" step="1000" placeholder="e.g. 50000"
                       class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
            </div>
            <div class="flex flex-col justify-end">
                <button class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm">
                    <i class="fas fa-filter"></i> Apply Filters
                </button>
            </div>
        </div>
    </form>
</section>

{{-- Main content: sidebar + grid --}}
<section class="bg-[#f7fafc] py-20">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="grid grid-cols-[300px_1fr] gap-10 max-lg:grid-cols-1">

            {{-- Sidebar filter --}}
            <form method="get" action="{{ route('website.courses.index') }}"
                  class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-200 h-fit sticky top-[120px] max-h-[calc(100vh-140px)] overflow-y-auto max-lg:static max-lg:max-h-none">
                <div class="p-6">

                    {{-- Institution --}}
                    <div class="pb-5 mb-6 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[1.1rem] font-bold text-[#2c5aa0]">Institution</h3>
                            <a href="{{ route('website.courses.index', request()->except(['institution', 'page'])) }}" class="text-[0.8rem] text-[#4299e1] no-underline">Clear</a>
                        </div>
                        <div class="flex flex-col gap-3 max-h-48 overflow-y-auto">
                            @foreach ($institutions as $inst)
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input class="w-[18px] h-[18px] accent-[#4299e1]" type="radio" name="institution" value="{{ $inst->id }}" {{ request('institution') == $inst->id ? 'checked' : '' }}>
                                <span class="flex-1 text-[#2d3748] text-sm">{{ $inst->name }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Max Fee --}}
                    <div class="pb-5 mb-6 border-b border-gray-200">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[1.1rem] font-bold text-[#2c5aa0]">Maximum Fee</h3>
                            <a href="{{ route('website.courses.index', request()->except(['fee_max', 'page'])) }}" class="text-[0.8rem] text-[#4299e1] no-underline">Clear</a>
                        </div>
                        <input type="number" name="fee_max" value="{{ request('fee_max') }}" min="0" step="1000" placeholder="e.g. 50000"
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    </div>

                    {{-- Search --}}
                    <div>
                        <h3 class="text-[1.1rem] font-bold text-[#2c5aa0] mb-3">Course Name</h3>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses..."
                               class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    </div>

                </div>
                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-5 rounded-b-xl">
                    <button class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition">
                        <i class="fas fa-redo"></i> Apply Filters
                    </button>
                </div>
            </form>

            {{-- Results --}}
            <div>
                <div class="flex items-center justify-between mb-8 max-md:flex-col max-md:items-start max-md:gap-4">
                    <div class="text-[1.1rem] text-[#2d3748]">
                        Showing <strong class="text-[#2c5aa0]">{{ $courses->count() }}</strong> of <strong class="text-[#2c5aa0]">{{ $courses->total() }}</strong> courses
                    </div>
                    @if(request()->hasAny(['search', 'institution', 'fee_max']))
                        <a href="{{ route('website.courses.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-red-500 transition no-underline">
                            <i class="fas fa-times-circle"></i> Clear all filters
                        </a>
                    @endif
                </div>

                @if ($courses->isEmpty())
                    <div class="text-center py-20">
                        <div class="w-20 h-20 rounded-full bg-[#4299e1]/10 flex items-center justify-center mx-auto mb-5">
                            <i class="fas fa-book-open text-3xl text-[#4299e1]"></i>
                        </div>
                        <h3 class="text-[1.5rem] font-bold text-gray-600 mb-3">No courses found</h3>
                        <p class="text-gray-500 mb-6">Try adjusting your filters or search term.</p>
                        <a href="{{ route('website.courses.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] transition no-underline">
                            <i class="fas fa-redo"></i> Clear Filters
                        </a>
                    </div>
                @else
                    <div class="grid grid-cols-[repeat(auto-fill,minmax(300px,1fr))] gap-7 mb-12 max-sm:grid-cols-1">
                        @foreach ($courses as $course)
                            @include('website.partials.course-card', ['course' => $course])
                        @endforeach
                    </div>
                    @include('website.partials.pagination', ['paginator' => $courses])
                @endif
            </div>

        </div>
    </div>
</section>
@endsection
