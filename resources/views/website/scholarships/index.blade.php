@extends('website.layouts.app')

@section('meta-title', 'Scholarships - ' . config('app.name'))
@section('meta-description', 'Discover active scholarships across top institutions. Apply before the deadline.')

@section('content')
<section class="relative overflow-hidden text-white pt-[160px] pb-[60px] bg-gradient-to-br from-[#2c5aa0] to-[#1a365d]">
    <div class="absolute top-0 right-0 w-[40%] h-full bg-gradient-to-br from-white/10 to-white/5" style="clip-path: polygon(100% 0, 0 0, 100% 100%);"></div>
    <div class="max-w-[1200px] mx-auto px-5 relative z-10">
        <div class="max-w-[800px] mx-auto text-center">
            <h1 class="text-[3.2rem] leading-[1.2] font-bold mb-5 max-md:text-[2.8rem] max-sm:text-[2.3rem]">Find Scholarships</h1>
            <p class="text-[1.2rem] text-white/90 mb-8">Discover financial support opportunities from colleges and institutions across Nepal.</p>
            <a href="#scholarship-list" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white text-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm no-underline">
                <i class="fas fa-award"></i> Explore Scholarships
            </a>
        </div>
    </div>
</section>

<section id="scholarship-list" class="relative z-10 -mt-10 bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.05)] max-w-[1160px] mx-auto px-5 py-10">
    <form method="get" action="{{ route('website.scholarships.index') }}">
        <div class="mb-8">
            <h2 class="relative inline-block text-[2.2rem] font-bold text-[#2c5aa0] mb-2">Find Your Scholarship<span class="absolute left-0 -bottom-2 w-20 h-1 bg-[#4299e1] rounded"></span></h2>
            <p class="text-gray-600 text-[1.1rem] max-w-[600px]">Filter scholarships by institution, type, benefit, and eligibility.</p>
        </div>
        <div class="grid grid-cols-4 gap-5 mb-8 max-lg:grid-cols-2 max-sm:grid-cols-1">
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Scholarship Title</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search scholarships" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
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
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Type</label>
                <select name="type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    <option value="">All Types</option>
                    @foreach ($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Benefit</label>
                <select name="benefit_type" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    <option value="">All Benefits</option>
                    @foreach ($benefitTypes as $key => $label)
                        <option value="{{ $key }}" {{ request('benefit_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex justify-end">
            <button class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm"><i class="fas fa-filter"></i> Apply Filters</button>
        </div>
    </form>
</section>

<section class="bg-[#f7fafc] py-20">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="grid grid-cols-[300px_1fr] gap-10 max-lg:grid-cols-1">
            <form method="get" action="{{ route('website.scholarships.index') }}" class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-200 h-fit sticky top-[120px]">
                <div class="p-6">
                    <h3 class="text-[1.2rem] font-bold text-[#2c5aa0] mb-4">Eligibility</h3>
                    <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem] block">My GPA</label>
                    <input type="number" name="min_gpa" value="{{ request('min_gpa') }}" step="0.1" min="0" max="4" placeholder="e.g. 3.5" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                </div>
                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-5 rounded-b-xl">
                    <button class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition"><i class="fas fa-redo"></i> Apply Filters</button>
                </div>
            </form>
            <div>
                <div class="text-[1.1rem] text-[#2d3748] mb-8">Showing <strong class="text-[#2c5aa0]">{{ $scholarships->count() }}</strong> of <strong class="text-[#2c5aa0]">{{ $scholarships->total() }}</strong> scholarships</div>
                @if ($scholarships->isEmpty())
                    <div class="text-center py-20"><h3 class="text-[1.5rem] font-bold text-gray-600 mb-3">No scholarships found.</h3></div>
                @else
                    <div class="grid grid-cols-[repeat(auto-fill,minmax(300px,1fr))] gap-7 mb-12 max-sm:grid-cols-1">
                        @foreach ($scholarships as $scholarship)
                            @include('website.partials.scholarship-card', ['scholarship' => $scholarship])
                        @endforeach
                    </div>
                    @include('website.partials.pagination', ['paginator' => $scholarships])
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
