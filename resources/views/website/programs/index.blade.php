@extends('website.layouts.app')

@section('meta-title', 'Programs - ' . config('app.name'))
@section('meta-description', 'Browse open programs across top institutions. Filter by faculty, level, fee, and more.')

@section('content')
<section class="relative overflow-hidden text-white pt-[160px] pb-[60px] bg-gradient-to-br from-[#2c5aa0] to-[#1a365d]">
    <div class="absolute top-0 right-0 w-[40%] h-full bg-gradient-to-br from-white/10 to-white/5" style="clip-path: polygon(100% 0, 0 0, 100% 100%);"></div>
    <div class="max-w-[1200px] mx-auto px-5 relative z-10">
        <div class="max-w-[800px] mx-auto text-center">
            <h1 class="text-[3.2rem] leading-[1.2] font-bold mb-5 max-md:text-[2.8rem] max-sm:text-[2.3rem]">Find Your Perfect Program</h1>
            <p class="text-[1.2rem] text-white/90 mb-8">Browse programs across Nepal and filter by faculty, level, institution, fees, and admission status.</p>
            <a href="#program-list" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-white text-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm no-underline">
                <i class="fas fa-search"></i> Explore Programs
            </a>
        </div>
    </div>
</section>

<section id="program-list" class="relative z-10 -mt-10 bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.05)] max-w-[1160px] mx-auto px-5 py-10">
    <form method="get" action="{{ route('website.programs.index') }}" class="max-w-[1200px] mx-auto">
        <div class="mb-8">
            <h2 class="relative inline-block text-[2.2rem] font-bold text-[#2c5aa0] mb-2">Find Your Program<span class="absolute left-0 -bottom-2 w-20 h-1 bg-[#4299e1] rounded"></span></h2>
            <p class="text-gray-600 text-[1.1rem] max-w-[600px]">Use filters to narrow down programs that match your goals.</p>
        </div>
        <div class="grid grid-cols-4 gap-5 mb-8 max-lg:grid-cols-2 max-sm:grid-cols-1">
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Program Name</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by program name" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
            </div>
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Faculty</label>
                <select name="faculty" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    <option value="">All Faculties</option>
                    @foreach ($faculties as $faculty)
                        <option value="{{ $faculty->slug }}" {{ request('faculty') === $faculty->slug ? 'selected' : '' }}>{{ $faculty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Level</label>
                <select name="level" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    <option value="">All Levels</option>
                    @foreach ($levels as $level)
                        <option value="{{ $level }}" {{ request('level') === $level ? 'selected' : '' }}>{{ Str::headline($level) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label class="mb-2 font-semibold text-[#2d3748] text-[0.95rem]">Status</label>
                <select name="status" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    <option value="">All Statuses</option>
                    @foreach ($statuses as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-5">
            <label class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] text-[0.9rem] font-semibold cursor-pointer">
                <input type="checkbox" name="admission_open" value="1" {{ request('admission_open') ? 'checked' : '' }} class="accent-[#4299e1]">
                Admission Open Only
            </label>
            <button class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-0.5 transition shadow-sm">
                <i class="fas fa-filter"></i> Apply Filters
            </button>
        </div>
    </form>
</section>

<section class="bg-[#f7fafc] py-20">
    <div class="max-w-[1200px] mx-auto px-5">
        <div class="grid grid-cols-[300px_1fr] gap-10 max-lg:grid-cols-1">
            <form method="get" action="{{ route('website.programs.index') }}" class="bg-white rounded-xl shadow-[0_4px_12px_rgba(0,0,0,0.08)] border border-gray-200 h-fit sticky top-[120px]">
                <div class="p-6 space-y-7">
                    <div class="pb-5 border-b border-gray-200">
                        <h3 class="text-[1.2rem] font-bold text-[#2c5aa0] mb-4">Institution</h3>
                        <select name="institution" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                            <option value="">All Institutions</option>
                            @foreach ($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ request('institution') == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="pb-5 border-b border-gray-200">
                        <h3 class="text-[1.2rem] font-bold text-[#2c5aa0] mb-4">Maximum Fee</h3>
                        <input type="number" name="fee_max" value="{{ request('fee_max') }}" min="0" step="10000" placeholder="e.g. 500000" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    </div>
                </div>
                <div class="sticky bottom-0 bg-white border-t border-gray-200 p-5 rounded-b-xl">
                    <button class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-0.5 transition">
                        <i class="fas fa-redo"></i> Apply Filters
                    </button>
                </div>
            </form>
            <div>
                <div class="flex items-center justify-between mb-8 max-md:flex-col max-md:items-start max-md:gap-4">
                    <div class="text-[1.1rem] text-[#2d3748]">Showing <strong class="text-[#2c5aa0]">{{ $programs->count() }}</strong> of <strong class="text-[#2c5aa0]">{{ $programs->total() }}</strong> programs</div>
                </div>
                @if ($programs->isEmpty())
                    <div class="text-center py-20"><h3 class="text-[1.5rem] font-bold text-gray-600 mb-3">No programs found matching your criteria.</h3></div>
                @else
                    <div class="grid grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-7 mb-12 max-lg:grid-cols-[repeat(auto-fill,minmax(300px,1fr))] max-sm:grid-cols-1">
                        @foreach ($programs as $program)
                            @include('website.partials.program-card', ['program' => $program])
                        @endforeach
                    </div>
                    @include('website.partials.pagination', ['paginator' => $programs])
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
