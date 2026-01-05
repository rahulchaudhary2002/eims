@extends('layouts.app')

@section('title', 'Schools')

@section('content')

<!-- Page Header -->
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800">Schools</h1>
        <p class="text-sm text-gray-500 mt-1">
            Browse active schools and institutions
        </p>
    </div>
</div>

<!-- Schools Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($schools as $school)
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

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

            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                {{ $school->address ?? 'Address not available.' }}
            </p>

            <!-- Contact -->
            <div class="text-sm text-gray-500 space-y-1">
                @if($school->phone)
                <p>📞 {{ $school->phone }}</p>
                @endif
                @if($school->email)
                <p>✉️ {{ $school->email }}</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="mt-4">
                <a
                    href="#"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">
                    View School →
                </a>
            </div>
        </div>
    </div>
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