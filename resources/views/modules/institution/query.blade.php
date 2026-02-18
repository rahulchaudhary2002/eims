@extends('layouts.app')

@section('title', "Ask a Question-".$institution->name)

@section('content')

<div class="bg-white overflow-hidden">

    {{-- Cover --}}
    <div class="relative h-32 sm:h-40 md:h-48">
        @php
            $coverImage = ($institution->cover_image && Storage::disk('public')->exists($institution->cover_image))
                ? Storage::url($institution->cover_image)
                : asset('assets/images/logo.png');
        @endphp
        <img
            src="{{ $coverImage }}"
            class="h-full w-full object-cover rounded-2xl"
            alt="Cover">
    </div>

    {{-- Content --}}
    <div class="relative flex items-center gap-4 py-6">

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

        <div class="space-y-1">
            <h1 class="text-xl sm:text-2xl font-semibold text-gray-900">
                Ask a Question
            </h1>

            <div>
                <a href="{{ route('institution.show', ['institution_type' => $institution->type, 'institution_slug' => $institution->slug]) }}" class="text-xl sm:text-lg font-semibold text-gray-500 hover:text-blue-600 transition">
                    {{ $institution->name }}
                </a>
            </div>

            <p class="text-sm sm:text-base text-gray-600 mt-1 line-clamp-2 flex items-center gap-2">
                <x-lucide-map-pin class="w-4 h-4 text-gray-400" />
                {{ $institution->address }}
            </p>
        </div>
    </div>
</div>

{{-- Enquiry Form --}}
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">

        <h2 class="text-lg font-semibold text-gray-900 mb-6">
            Submit your question
        </h2>

        <form method="POST" action="{{ route('institution.query.store', ['institution_type' => $institution->type, 'institution_slug' => $institution->slug]) }}" class="space-y-5">
            @csrf

            {{-- Hidden institution --}}
            <input type="hidden" name="institution_id" value="{{ $institution->id }}">

            {{-- Full Name --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name *
                </label>
                <input
                    type="text"
                    name="full_name"
                    value="{{ old('full_name', auth()->user()->name ?? '') }}"
                    required
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('full_name')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email Address *
                </label>
                <input
                    type="email"
                    name="email"
                    value="{{ old('email', auth()->user()->email ?? '') }}"
                    required
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('email')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone Number *
                </label>
                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    required
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                @error('phone')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Enquiry Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Enquiry Type *
                </label>
                <select
                    name="type"
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                    <option value="general">General</option>
                    <option value="admission">Admission</option>
                    <option value="course">Course</option>
                    <option value="fee">Fee</option>
                    <option value="scholarship">Scholarship</option>
                    <option value="other">Other</option>
                </select>
                @error('type')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Message --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Your Question *
                </label>
                <textarea
                    name="message"
                    rows="5"
                    required
                    class="w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500">{{ old('message') }}</textarea>
                @error('message')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit --}}
            <button
                type="submit"
                class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition">
                <x-lucide-send class="w-4 h-4" />
                Submit Question
            </button>
        </form>
    </div>
</div>

@endsection
