@extends('layouts.app')

@section('title', $admission->title)

@section('content')

<!-- Hero Header -->
<div class="relative overflow-hidden rounded-3xl mb-10 bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
    <div class="absolute inset-0 bg-black/20"></div>

    <div class="relative p-8 md:p-12 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold">
                {{ $admission->title }}
            </h1>

            @if($admission->institution)
            <p class="mt-2 text-indigo-100 text-lg">
                {{ $admission->institution->name }}
            </p>
            @endif

            <div class="mt-4 flex items-center gap-4 flex-wrap">
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold
                    {{ $admission->is_open ? 'bg-green-500/90' : 'bg-red-500/90' }}">
                    {{ $admission->is_open ? 'Admissions Open' : 'Admissions Closed' }}
                </span>

                <span class="text-sm text-indigo-100">
                    {{ \Carbon\Carbon::parse($admission->start_date)->format('M d, Y') }}
                    –
                    {{ \Carbon\Carbon::parse($admission->end_date)->format('M d, Y') }}
                </span>
            </div>
        </div>

        <!-- CTA -->
        <div>
            @if($admission->is_open)
            <a href="{{ route('admission.apply', $admission->slug) }}"
                class="inline-flex items-center gap-2 px-8 py-4 bg-white text-indigo-700 font-bold rounded-xl shadow-lg hover:scale-105 transition">
                Apply Now
                <x-lucide-arrow-right class="w-4 h-4" />
            </a>
            @else
            <button disabled
                class="px-8 py-4 bg-white/40 text-white font-semibold rounded-xl cursor-not-allowed">
                Admission Closed
            </button>
            @endif
        </div>
    </div>
</div>

<!-- Main Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Left Content -->
    <div class="lg:col-span-2 space-y-8">

        <!-- Description -->
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Admission Overview
            </h2>
            <div class="prose max-w-none text-gray-700 no-tailwind">
                {!! $admission->description ?? 'No description available.' !!}
            </div>
        </div>

        <!-- Programs -->
        @if($admission->programs->count())
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">
                Available Programs
            </h2>
            <ul class="grid sm:grid-cols-2 gap-4">
                @foreach($admission->programs as $program)
                <li>
                    <a href="#"
                        class="block p-4 border rounded-xl hover:border-indigo-500 hover:bg-indigo-50 transition">
                        <span class="font-medium text-indigo-700">
                            {{ $program->display_name }}
                        </span>
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>

    <!-- Right Sidebar -->
    <div class="space-y-6 lg:sticky lg:top-6 h-fit">

        <!-- Institution Card -->
        <div class="bg-white rounded-2xl border shadow-sm p-6 text-center">
            <p class="text-sm font-semibold text-gray-500 mb-3">
                Admissions Opened By
            </p>

            @if($admission->institution && $admission->institution->logo)
            <img src="{{ asset('storage/' . $admission->institution->logo) }}"
                class="h-12 mx-auto mb-3 object-contain"
                alt="Institution Logo">
            @endif

            <p class="font-semibold text-gray-800">
                {{ $admission->institution->name ?? '' }}
            </p>
        </div>

        <!-- Admission Info -->
        <div class="bg-white rounded-2xl border shadow-sm p-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">
                Admission Information
            </h3>

            <div class="space-y-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Start Date</span>
                    <span class="font-medium">
                        {{ \Carbon\Carbon::parse($admission->start_date)->format('M d, Y') }}
                    </span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">End Date</span>
                    <span class="font-medium">
                        {{ \Carbon\Carbon::parse($admission->end_date)->format('M d, Y') }}
                    </span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-500">Status</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        {{ $admission->is_open ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $admission->is_open ? 'Open' : 'Closed' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection