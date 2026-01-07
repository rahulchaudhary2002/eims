@extends('layouts.app')

@section('title', 'Colleges')

@section('content')

<!-- Page Header -->
<div class="mb-10">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white">
        <h1 class="text-3xl font-bold">Colleges</h1>
        <p class="mt-2 text-indigo-100">
            Browse active colleges and institutions
        </p>
    </div>
</div>

<!-- Colleges Grid -->
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
                    <p class="text-sm text-gray-500">
                        Est. {{ $college->established_year ?? 'N/A' }}
                    </p>
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

<!-- Pagination -->
<div class="mt-8">
    {{ $colleges->links() }}
</div>

@endsection