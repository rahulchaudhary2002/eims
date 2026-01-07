@extends('layouts.app')

@section('title', 'Home')

@section('content')

<!-- Hero Section -->
<div class="mb-10">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white">
        <h1 class="text-3xl font-bold">Welcome to {{ config('app.name') }}</h1>
        <p class="mt-2 text-indigo-100">
            Discover colleges, schools, and courses in one place
        </p>
    </div>
</div>

<!-- Colleges Section -->
<section class="mb-12">
    <div class="mb-5">
        <h2 class="text-2xl font-semibold text-gray-800">Colleges</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($colleges as $college)
        <a href="#" class="bg-white border rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

            <!-- Cover Image -->
            <div class="h-32 bg-gray-100">
                <img
                    src="{{ Storage::url($college->cover_image) }}"
                    alt="{{ $college->name }}"
                    class="h-full w-full object-cover">
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <img
                        src="{{ Storage::url($college->logo) }}"
                        alt="{{ $college->name }}"
                        class="h-14 w-14 rounded-lg object-cover border">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $college->name }}
                        </h2>
                        @if($college->established_year)
                        <p class="text-sm text-gray-500">
                            Est. {{ $college->established_year }}
                        </p>
                        @endif
                    </div>
                </div>

                @if($college->affiliations)
                <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex items-center gap-2">
                    <x-lucide-award class="w-4 h-4 text-gray-400" />
                    <span>
                        @foreach($college->affiliations as $affiliation)
                        {{ $affiliation->name }}@if(!$loop->last), @endif
                        @endforeach
                    </span>
                </p>
                @endif

                @if($college->address)
                <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex items-center gap-2">
                    <x-lucide-map-pin class="w-4 h-4 text-gray-400" />
                    <span>{{ $college->address }}</span>
                </p>
                @endif
            </div>
        </a>
        @empty
        <div class="col-span-full bg-white rounded-xl p-6 text-center text-gray-500">
            No colleges found.
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $colleges->links() }}
    </div>
</section>

<!-- Schools Section -->
<section class="mb-12">
    <div class="mb-5">
        <h2 class="text-2xl font-semibold text-gray-800">Schools</h2>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($schools as $school)
        <a href="#" class="bg-white border rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

            <!-- Cover Image -->
            <div class="h-32 bg-gray-100">
                <img
                    src="{{ Storage::url($school->cover_image) }}"
                    alt="{{ $school->name }}"
                    class="h-full w-full object-cover">
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <img
                        src="{{ Storage::url($school->logo) }}"
                        alt="{{ $school->name }}"
                        class="h-14 w-14 rounded-lg object-cover border">

                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">
                            {{ $school->name }}
                        </h2>
                        @if($school->established_year)
                        <p class="text-sm text-gray-500">
                            Est. {{ $school->established_year }}
                        </p>
                        @endif
                    </div>
                </div>

                @if($school->affiliations)
                <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex items-center gap-2">
                    <x-lucide-award class="w-4 h-4 text-gray-400" />
                    <span>
                        @foreach($school->affiliations as $affiliation)
                        {{ $affiliation->name }}@if(!$loop->last), @endif
                        @endforeach
                    </span>
                </p>
                @endif

                @if($school->address)
                <p class="text-sm text-gray-600 mb-3 line-clamp-2 flex items-center gap-2">
                    <x-lucide-map-pin class="w-4 h-4 text-gray-400" />
                    <span>{{ $school->address }}</span>
                </p>
                @endif
            </div>
        </a>
        @empty
        <div class="col-span-full bg-white rounded-xl p-6 text-center text-gray-500">
            No schools found.
        </div>
        @endforelse
    </div>

    <div class="mt-6">
        {{ $schools->links() }}
    </div>
</section>

<!-- Courses Section -->
<section>
    <div class="mb-5">
        <h2 class="text-2xl font-semibold text-gray-800">Courses</h2>
    </div>

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
            @if($course->description)
            <p class="text-sm text-gray-600 mb-4 line-clamp-3">
                {{ $course->description }}
            </p>
            @endif

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

    <div class="mt-6">
        {{ $courses->links() }}
    </div>
</section>

@endsection