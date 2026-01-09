@extends('layouts.app')

@section('title', 'Admissions')

@section('content')

<!-- Page Header -->
<div class="mb-10">
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white">
        <h1 class="text-3xl font-bold">Admissions</h1>
        <p class="mt-2 text-indigo-100">
            Browse currently available admissions
        </p>
    </div>
</div>

<!-- Admissions Grid -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse ($admissions as $admission)
    <a
        href="{{ route('admission.show', $admission->slug) }}"
        class="bg-white border rounded-2xl shadow hover:shadow-lg transition p-6 flex flex-col">

        <!-- Admission Title -->
        <h2 class="text-lg font-semibold text-gray-800 mb-1">
            {{ $admission->title }}
        </h2>

        <!-- Institution -->
        @if($admission->institution)
        <p class="text-sm text-gray-500 mb-2">
            {{ $admission->institution->name }}
        </p>
        @endif

        <!-- Meta Info -->
        <div class="flex flex-wrap gap-2 text-xs mt-auto">

            <!-- Date Range -->
            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded flex items-center gap-1">
                <x-lucide-calendar class="w-4 h-4 text-gray-400" />
                {{ \Carbon\Carbon::parse($admission->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($admission->end_date)->format('M d, Y') }}
            </span>

            <!-- Status -->
            @if($admission->is_open)
            <span class="bg-green-100 text-green-700 px-2 py-1 rounded flex items-center gap-1">
                <x-lucide-check-circle class="w-4 h-4 text-green-400" />
                Open
            </span>
            @else
            <span class="bg-red-100 text-red-700 px-2 py-1 rounded flex items-center gap-1">
                <x-lucide-x-circle class="w-4 h-4 text-red-400" />
                Closed
            </span>
            @endif
        </div>
    </a>
    @empty
    <div class="col-span-full bg-white rounded-xl p-6 text-center text-gray-500">
        No admissions available.
    </div>
    @endforelse
</div>

<!-- Pagination -->
<div class="mt-8">
    {{ $admissions->links() }}
</div>

@endsection