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
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

    @forelse ($admissions as $admission)
    <a
        href="{{ route('admission.show', $admission->slug) }}"
        class="group bg-white border border-gray-200 rounded-3xl shadow-sm hover:shadow-xl transition-all duration-300 p-6 flex flex-col hover:-translate-y-1">

        <!-- Accent Bar -->
        <div class="h-1 w-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-full mb-4"></div>

        <!-- Title -->
        <h2 class="text-xl font-bold text-gray-800 group-hover:text-indigo-600 transition">
            {{ $admission->title }}
        </h2>

        <!-- Institution -->
        @if($admission->institution)
        <p class="text-sm text-gray-500 mt-1 mb-3">
            {{ $admission->institution->name }}
        </p>
        @endif

        <!-- Courses -->
        @if($admission->courses && $admission->courses->count())
        <div class="mb-4">
            <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">
                Courses
            </p>
            <div class="flex flex-wrap gap-2">
                @foreach($admission->courses->take(4) as $course)
                <span class="bg-indigo-50 text-indigo-700 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $course->code }}
                </span>
                @endforeach

                @if($admission->courses->count() > 4)
                <span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full">
                    +{{ $admission->courses->count() - 4 }} more
                </span>
                @endif
            </div>
        </div>
        @endif

        <!-- Grades -->
        @if($admission->grades && $admission->grades->count())
        <div class="mb-5">
            <p class="text-xs uppercase tracking-wide text-gray-400 mb-2">
                Grades
            </p>
            <div class="flex flex-wrap gap-2">
                @foreach($admission->grades->take(4) as $grade)
                <span class="bg-purple-50 text-purple-700 text-xs font-semibold px-3 py-1 rounded-full">
                    {{ $grade->name }}
                </span>
                @endforeach

                @if($admission->grades->count() > 4)
                <span class="bg-gray-100 text-gray-600 text-xs px-3 py-1 rounded-full">
                    +{{ $admission->grades->count() - 4 }} more
                </span>
                @endif
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="mt-auto pt-4 border-t border-gray-100 flex flex-wrap items-center gap-2 text-xs">

            <!-- Date -->
            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full flex items-center gap-1">
                <x-lucide-calendar class="w-4 h-4 text-gray-400" />
                {{ \Carbon\Carbon::parse($admission->start_date)->format('M d') }}
                –
                {{ \Carbon\Carbon::parse($admission->end_date)->format('M d, Y') }}
            </span>

            <!-- Status -->
            @if($admission->is_open)
            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full flex items-center gap-1 font-semibold">
                <x-lucide-check-circle class="w-4 h-4" />
                Open
            </span>
            @else
            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full flex items-center gap-1 font-semibold">
                <x-lucide-x-circle class="w-4 h-4" />
                Closed
            </span>
            @endif
        </div>

    </a>
    @empty
    <div class="col-span-full bg-white rounded-2xl p-10 text-center text-gray-500 shadow">
        No admissions available.
    </div>
    @endforelse

</div>

<!-- Pagination -->
<div class="mt-10">
    {{ $admissions->links() }}
</div>

@endsection