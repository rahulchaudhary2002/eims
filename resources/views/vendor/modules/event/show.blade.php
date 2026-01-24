@extends('vendor.layouts.app')
@section('title', $event->title)

@section('content')

{{-- Header Card --}}
<div class="relative overflow-hidden rounded-2xl mb-8 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white shadow-lg">
    <div class="absolute inset-0 bg-black/20"></div>

    <div class="relative p-8 md:p-10">
        <div class="flex items-center gap-3 mb-3">
            <x-lucide-calendar class="w-8 h-8 text-white/90" />
            <h1 class="text-3xl md:text-4xl font-bold">
                {{ $event->title }}
            </h1>
        </div>

        <div class="flex flex-wrap items-center gap-6 text-sm text-white/90 mt-4">
            <div class="flex items-center gap-2">
                <x-lucide-clock class="w-4 h-4" />
                <span>
                    {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                    –
                    {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                </span>
            </div>

            {{-- Status Badge --}}
            @php
            $now = now();
            if ($now->lt($event->start_date)) {
            $status = 'Upcoming';
            $badgeClass = 'bg-yellow-400/20 text-yellow-100 border border-yellow-300/30';
            } elseif ($now->between($event->start_date, $event->end_date)) {
            $status = 'Ongoing';
            $badgeClass = 'bg-green-400/20 text-green-100 border border-green-300/30';
            } else {
            $status = 'Past';
            $badgeClass = 'bg-gray-400/20 text-gray-100 border border-gray-300/30';
            }
            @endphp

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                {{ $status }}
            </span>
        </div>
    </div>
</div>

{{-- Content Card --}}
<div class="bg-white border border-gray-200 rounded-2xl shadow p-8">
    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
        <x-lucide-file-text class="w-5 h-5 text-purple-500" />
        Event Description
    </h2>

    <div class="prose max-w-none prose-indigo">
        {!! $event->description ?: '<p class="text-gray-500 italic">No description provided.</p>' !!}
    </div>
</div>

{{-- Actions --}}
<div class="flex justify-end gap-4 mt-8">
    <a href="{{ route('vendor.event.index') }}"
        class="bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center hover:bg-gray-700 transition">
        <x-lucide-arrow-left class="w-5 h-5 mr-2" />
        Back to Events
    </a>

    <a href="{{ route('vendor.event.edit', $event) }}"
        class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white px-6 py-3 rounded-lg flex items-center hover:opacity-90 transition">
        <x-lucide-pencil class="w-5 h-5 mr-2" />
        Edit Event
    </a>
</div>

@endsection