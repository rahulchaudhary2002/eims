@extends('layouts.app')

@section('title', 'Courses')

@section('content')

<section class="py-8 px-4" id="courses">
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