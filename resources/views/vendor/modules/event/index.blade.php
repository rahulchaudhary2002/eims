@extends('vendor.layouts.app')
@section('title', 'Events')

@section('content')

{{-- Header Card --}}
<div class="relative overflow-hidden rounded-2xl mb-8 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 text-white shadow-lg">
    <div class="absolute inset-0 bg-black/20"></div>

    <div class="relative p-8 md:p-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-3xl md:text-4xl font-bold flex items-center gap-3">
                <x-lucide-calendar class="w-9 h-9 text-white/90" />
                Events
            </h1>
            <p class="text-white/90 mt-2">
                Manage institution events and schedules
            </p>
        </div>

        <a href="{{ route('vendor.event.create') }}"
            class="bg-white text-purple-700 hover:bg-purple-50 transition px-6 py-3 rounded-xl flex items-center font-semibold shadow">
            <x-lucide-plus class="w-5 h-5 mr-2" />
            Add Event
        </a>
    </div>
</div>

{{-- Event Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

    @forelse($events as $event)
    @php
    $now = now();
    if ($now->lt($event->start_date)) {
    $status = 'Upcoming';
    $badgeClass = 'bg-yellow-100 text-yellow-700 border border-yellow-200';
    } elseif ($now->between($event->start_date, $event->end_date)) {
    $status = 'Ongoing';
    $badgeClass = 'bg-green-100 text-green-700 border border-green-200';
    } else {
    $status = 'Past';
    $badgeClass = 'bg-gray-100 text-gray-600 border border-gray-200';
    }
    @endphp

    <div
        class="group bg-white border border-gray-200 rounded-2xl shadow hover:shadow-xl transition-all duration-300 p-6 flex flex-col justify-between relative overflow-hidden">

        {{-- Hover Glow --}}
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition bg-gradient-to-br from-purple-500/10 via-transparent to-blue-500/10"></div>

        {{-- Content --}}
        <div class="relative z-10">
            <div class="flex items-start justify-between gap-3">
                <h2 class="text-xl font-bold text-gray-800 leading-snug">
                    {{ $event->title }}
                </h2>

                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                    {{ $status }}
                </span>
            </div>

            {{-- Dates --}}
            <div class="flex items-center text-sm text-gray-600 gap-2 mt-3">
                <x-lucide-clock class="w-4 h-4 text-purple-500" />
                <span>
                    {{ \Carbon\Carbon::parse($event->start_date)->format('M d, Y') }}
                    –
                    {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                </span>
            </div>
        </div>

        {{-- Divider --}}
        <div class="border-t my-5"></div>

        {{-- Actions --}}
        <div class="flex items-center justify-between relative z-10">
            <div class="flex gap-4">
                <a href="{{ route('vendor.event.show', $event) }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition">
                    <x-lucide-eye class="w-4 h-4 mr-1" />
                    View
                </a>

                <a href="{{ route('vendor.event.edit', $event) }}"
                    class="inline-flex items-center text-yellow-600 hover:text-yellow-700 font-medium transition">
                    <x-lucide-pencil class="w-4 h-4 mr-1" />
                    Edit
                </a>
            </div>

            <form action="{{ route('vendor.event.destroy', $event) }}" method="POST"
                onsubmit="return confirm('Are you sure you want to delete this event?')">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center text-red-600 hover:text-red-700 font-medium transition">
                    <x-lucide-trash-2 class="w-4 h-4 mr-1" />
                    Delete
                </button>
            </form>
        </div>

    </div>
    @empty
    <div class="col-span-full text-center py-16">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-purple-100 mb-4">
            <x-lucide-calendar-x class="w-10 h-10 text-purple-500" />
        </div>
        <p class="text-gray-700 text-lg font-semibold">No events found</p>
        <p class="text-gray-500 text-sm mt-1">
            Click “Add Event” to create your first event.
        </p>
    </div>
    @endforelse

</div>

{{-- Pagination --}}
<div class="mt-8">
    {{ $events->links() }}
</div>

@endsection