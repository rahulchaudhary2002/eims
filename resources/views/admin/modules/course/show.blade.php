@extends('admin.layouts.app')
@section('title', $course->name)

@section('content')

{{-- Header --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                {{ $course->name }}
            </h1>
            <p class="text-gray-500 mt-1">
                Course Code: <span class="font-medium">{{ $course->code }}</span>
            </p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.course.edit', $course) }}"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                <x-lucide-pencil class="w-4 h-4 mr-2" />
                Edit
            </a>

            <a href="{{ route('admin.course.index') }}"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition">
                <x-lucide-arrow-left class="w-4 h-4 mr-2" />
                Back
            </a>
        </div>
    </div>
</div>

{{-- Course Info --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Level</p>
        <p class="text-lg font-semibold text-gray-800">
            {{ $course->level?->name ?? '—' }}
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Affiliation</p>
        <p class="text-lg font-semibold text-gray-800">
            {{ $course->affiliation?->name ?? '—' }}
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-5">
        <p class="text-sm text-gray-500">Duration</p>
        <p class="text-lg font-semibold text-gray-800">
            {{ $course->duration ?? '—' }}
        </p>
    </div>
</div>

{{-- Description --}}
@if($course->description)
<div class="bg-white rounded-2xl border border-gray-200 p-6 mb-8">
    <h2 class="text-xl font-semibold text-gray-900 mb-3">
        About this Course
    </h2>
    <p class="text-gray-700 leading-relaxed">
        {{ $course->description }}
    </p>
</div>
@endif

{{-- Course Content --}}
<div class="bg-white rounded-2xl border border-gray-200 p-6">

    <h2 class="text-2xl font-bold text-gray-900 mb-4">
        Course Content
    </h2>

    {{-- Jump to Section --}}
    @if($course->descriptions->count())
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">
        <h4 class="text-sm font-semibold text-gray-700 mb-3">
            Jump to Section
        </h4>

        <ul class="space-y-2">
            @foreach($course->descriptions as $key => $section)
            <li>
                <a href="#section-{{ ++$key }}"
                    class="text-blue-600 hover:text-blue-800 hover:underline text-sm">
                    {{ $loop->iteration }}. {{ $section->title }}
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Sections --}}
    @forelse($course->descriptions as $key => $section)
    @php
    $sectionId = 'section-' . ++$key;
    @endphp

    <div id="{{ $sectionId }}" class="mb-10 scroll-mt-28">

        <a href="#{{ $sectionId }}" class="group flex items-center gap-3 mb-3">
            <span
                class="flex items-center justify-center w-9 h-9 rounded-full
                               bg-blue-100 text-blue-700 font-semibold
                               group-hover:bg-blue-200 transition">
                {{ $loop->iteration }}
            </span>

            <h3 class="text-xl font-semibold text-gray-800 group-hover:text-blue-600 transition">
                {{ $section->title }}
            </h3>
        </a>

        <div class="prose max-w-none prose-blue no-tailwind">
            {!! $section->content !!}
        </div>

        @if(!$loop->last)
        <hr class="mt-8 border-gray-200">
        @endif
    </div>

    @empty
    <p class="text-gray-500 text-center py-6">
        No course sections added yet.
    </p>
    @endforelse

</div>

@endsection