@extends('layouts.app')

@section('title', 'Courses')

@section('content')

<!-- Page Header -->
<div class="mb-10">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white">
        <h1 class="text-3xl font-bold">Courses</h1>
        <p class="mt-2 text-indigo-100">
            Browse available courses offered by colleges
        </p>
    </div>
</div>

<!-- Courses Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($courses as $course)
    <a
        href="{{ route('course.show', $course) }}"
        class="bg-white border rounded-2xl shadow hover:shadow-lg transition p-6 flex flex-col">

        <!-- Course Title -->
        <h2 class="text-lg font-semibold text-gray-800 mb-1">
            {{ $course->display_name }}
        </h2>

        <!-- Description -->
        <p class="text-sm text-gray-600 mb-4 line-clamp-3">
            {{ $course->description ?? 'No description available.' }}
        </p>

        <!-- Meta Info -->
        <div class="flex flex-wrap gap-2 text-xs mt-auto">
            @if($course->level)
            <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded flex items-center gap-1">
                <x-lucide-bar-chart-3 class="w-4 h-4 text-indigo-400" />
                {{ $course->level->name }}
            </span>
            @endif

            @if($course->affiliation)
            <span class="bg-green-100 text-green-700 px-2 py-1 rounded flex items-center gap-1">
                <x-lucide-award class="w-4 h-4 text-green-400" />
                {{ $course->affiliation->name }}
            </span>
            @endif

            @if($course->duration)
            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded flex items-center gap-1">
                <x-lucide-clock class="w-4 h-4 text-gray-400" />
                {{ $course->duration }}
            </span>
            @endif
        </div>
    </a>
    @empty
    <div class="col-span-full bg-white rounded-xl p-6 text-center text-gray-500">
        No courses available.
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $courses->links() }}
</div>

@endsection