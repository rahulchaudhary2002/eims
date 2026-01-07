@extends('layouts.app')

@section('title', 'Schools')

@section('content')

<!-- Page Header -->
<div class="mb-10">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white">
        <h1 class="text-3xl font-bold">Schools</h1>
        <p class="mt-2 text-indigo-100">
            Browse active schools and institutions
        </p>
    </div>
</div>

<!-- Schools Grid -->
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
                    <p class="text-sm text-gray-500">
                        Est. {{ $school->established_year ?? 'N/A' }}
                    </p>
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

<!-- Pagination -->
<div class="mt-8">
    {{ $schools->links() }}
</div>

@endsection