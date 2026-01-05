@extends('layouts.app')

@section('title', 'Colleges')

@section('content')

<!-- Page Header -->
<div class="mb-8">
    <div class="bg-white rounded-2xl shadow p-6">
        <h1 class="text-2xl font-bold text-gray-800">Colleges</h1>
        <p class="text-sm text-gray-500 mt-1">
            Browse active colleges and institutions
        </p>
    </div>
</div>

<!-- Colleges Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($colleges as $college)
    <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

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

            <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                {{ $college->address ?? 'Address not available.' }}
            </p>

            <!-- Contact -->
            <div class="text-sm text-gray-500 space-y-1">
                @if($college->phone)
                <p>📞 {{ $college->phone }}</p>
                @endif
                @if($college->email)
                <p>✉️ {{ $college->email }}</p>
                @endif
            </div>

            <!-- Actions -->
            <div class="mt-4">
                <a
                    href="#"
                    class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-700">
                    View College →
                </a>
            </div>
        </div>
    </div>
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