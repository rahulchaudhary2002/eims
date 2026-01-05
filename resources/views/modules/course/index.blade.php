@extends('layouts.app')

@section('title', 'Courses')

@section('content')

<!-- Page Header -->
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800">Courses</h1>
        <p class="text-sm text-gray-500 mt-1">
            Browse available courses offered by colleges
        </p>
    </div>
</div>

<!-- Courses Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($courses as $course)
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition p-6 flex flex-col">

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
            <span class="bg-indigo-100 text-indigo-700 px-2 py-1 rounded">
                {{ $course->level->name }}
            </span>
            @endif

            @if($course->affiliation)
            <span class="bg-green-100 text-green-700 px-2 py-1 rounded">
                {{ $course->affiliation->name }}
            </span>
            @endif

            @if($course->duration)
            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded">
                {{ $course->duration }}
            </span>
            @endif
        </div>

        <!-- Action -->
        <div class="mt-4">
            <a
                href="#"
                class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">
                View Details →
            </a>
        </div>
    </div>
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