@extends('layouts.app')

@section('title', 'Programs - Sikuna.com')

@section('content')
<section class="relative overflow-hidden text-white pt-[160px] pb-[60px] bg-gradient-to-br from-[#2c5aa0] to-[#1a365d]">
    <div class="absolute top-0 right-0 w-[40%] h-full bg-gradient-to-br from-white/10 to-white/5"
        style="clip-path: polygon(100% 0, 0 0, 100% 100%);"></div>

    <div class="max-w-[1200px] mx-auto px-5 relative z-10">
        <div class="max-w-[800px] mx-auto text-center">
            <h1 class="text-[3.2rem] leading-[1.2] font-bold mb-5 max-md:text-[2.8rem] max-sm:text-[2.3rem]">
                Explore Programs
            </h1>
            <p class="text-[1.2rem] text-white/90 mb-8">
                Browse all academic programs by level and category to find the right path.
            </p>
        </div>
    </div>
</section>

<section class="py-[50px] bg-[#f8f9fa]">
    <div class="max-w-7xl mx-auto px-5">
        <form method="get" action="{{ route('program.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4 bg-white p-6 rounded-xl border border-[#e2e8f0] shadow-sm mb-8">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search programs"
                class="px-4 py-3 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-2 focus:ring-[#4299e1]">

            <select name="category" class="px-4 py-3 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-2 focus:ring-[#4299e1]">
                <option value="">All Program Categories</option>
                @foreach($categories as $category)
                <option value="{{ $category->slug }}" {{ request('category') === $category->slug ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>

            <select name="level" class="px-4 py-3 rounded-lg border border-[#e2e8f0] focus:outline-none focus:ring-2 focus:ring-[#4299e1]">
                <option value="">All Levels</option>
                @foreach($levels as $level)
                <option value="{{ $level->slug }}" {{ request('level') === $level->slug ? 'selected' : '' }}>{{ $level->name }}</option>
                @endforeach
            </select>

            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-[#4299e1] text-white font-semibold hover:bg-[#2c5aa0] transition">Apply Filters</button>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse($programs as $program)
            <a href="{{ route('program.show', $program->slug) }}" class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200">
                <div class="p-6">
                    <span class="inline-block px-3 py-1 bg-[#4299e1]/10 text-[#4299e1] rounded-full text-xs font-semibold mb-3">{{ $program->category?->name ?? 'Uncategorized' }}</span>
                    <h3 class="text-2xl text-[#2c5aa0] mb-2.5 leading-tight font-bold">{{ $program->name }}</h3>
                    <div class="flex flex-wrap gap-3 text-gray-600 text-sm mb-4">
                        <span><i class="fas fa-graduation-cap"></i> {{ $program->level?->name ?? '-' }}</span>
                        <span><i class="fas fa-clock"></i> {{ $program->duration ?? '-' }}</span>
                        <span><i class="fas fa-book"></i> {{ $program->active_courses_count }} Courses</span>
                    </div>
                    <p class="text-gray-600 text-sm line-clamp-3">{{ strip_tags($program->description ?? '') ?: 'No description available.' }}</p>
                </div>
            </a>
            @empty
            <div class="col-span-full text-center bg-white border border-dashed border-gray-300 rounded-xl p-10 text-gray-500">
                No programs found.
            </div>
            @endforelse
        </div>

        <div class="mt-8">
            @include('includes.pagination', ['collections' => $programs])
        </div>
    </div>
</section>
@endsection