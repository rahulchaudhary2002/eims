@extends('layouts.app')

@section('title', $institution->name)

@section('content')

<div class="bg-white rounded-3xl shadow-md border border-gray-200 overflow-hidden">

    {{-- Cover --}}
    <div class="relative h-32 sm:h-40 md:h-48">
        @if($institution->cover_image)
        <img
            src="{{ Storage::url($institution->cover_image) }}"
            class="h-full w-full object-cover"
            alt="Cover">
        @else
        <div class="h-full w-full bg-gradient-to-r from-orange-100 to-orange-200"></div>
        @endif
    </div>

    {{-- Content --}}
    <div class="relative flex items-center gap-4 px-5 sm:px-8 p-6">

        {{-- Logo --}}
        <div class="h-20 w-20 sm:h-24 sm:w-24 rounded-2xl bg-white shadow-lg border flex items-center justify-center overflow-hidden">
            @if($institution->logo)
            <img
                src="{{ Storage::url($institution->logo) }}"
                class="h-full w-full object-contain p-2"
                alt="Logo">
            @else
            <x-lucide-school class="w-10 h-10 text-gray-400" />
            @endif
        </div>

        <div class="space-y-2">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900 flex items-center gap-2">
                {{ $institution->name }}
                <x-lucide-badge-check class="w-5 h-5 text-blue-600" />
            </h1>

            <p class="text-sm sm:text-base text-gray-600 mt-1 line-clamp-2 flex items-center gap-2">
                <x-lucide-map-pin class="w-4 h-4 text-gray-400" />
                {{ $institution->address }}
            </p>
            <div class="flex flex-wrap items-center gap-4">
                <a
                    href="{{ route('institution.query', ['institution_type' => $institution->type, 'institution_slug' => $institution->slug]) }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-blue-600 text-white font-medium shadow hover:bg-blue-700 transition">
                    Ask a Question
                </a>
                @if($institution->admissions->count() > 0)
                <a
                    href="{{ route('institution.admissions', ['institution_type' => $institution->type, 'institution_slug' => $institution->slug]) }}"
                    class="inline-flex items-center justify-center px-6 py-2.5 rounded-xl bg-indigo-600 text-white font-medium shadow hover:bg-indigo-700 transition">
                    Apply for Admissions
                </a>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection