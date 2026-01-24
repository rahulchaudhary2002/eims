@extends('layouts.app')

@section('title', 'Events')

@section('content')

@php
$now = now();
@endphp

{{-- Tabs --}}
<div class="mb-6 border-b border-gray-200">
    <nav class="-mb-px flex space-x-6" aria-label="Tabs">
        @php
        $tabs = ['All', 'Upcoming', 'Ongoing', 'Past'];
        @endphp

        @foreach ($tabs as $tab)
        <a href="{{ route('event.index', ['status' => $tab]) }}"
            class="tab-btn px-3 py-2 font-semibold text-sm border-b-2
                      @if($status === $tab)
                          text-blue-500 border-blue-500
                      @else
                          text-gray-600 border-transparent hover:text-blue-500 hover:border-blue-500
                      @endif
               ">
            {{ $tab }}
        </a>
        @endforeach
    </nav>
</div>

{{-- Events Grid --}}
<div class="flex flex-wrap -mx-2">
    @foreach($events as $event)
    @php
    if ($now->lt($event->start_date)) {
    $eventStatus = 'Upcoming';
    } elseif ($now->between($event->start_date, $event->end_date)) {
    $eventStatus = 'Ongoing';
    } else {
    $eventStatus = 'Past';
    }
    @endphp

    <a href="{{ route('event.show', $event) }}" class="w-full md:w-1/2 px-2 mb-4 group">
        <div class="flex items-center bg-white border shadow rounded overflow-hidden transition transform duration-200 group-hover:shadow-lg group-hover:-translate-y-1">
            <div class="bg-blue-600 text-white text-center p-4 flex flex-col items-center justify-center" style="min-width: 80px;">
                <div class="text-2xl font-bold">{{ \Carbon\Carbon::parse($event->start_date)->format('d') }}</div>
                <div class="uppercase">{{ \Carbon\Carbon::parse($event->start_date)->format('M') }}</div>
            </div>
            <div class="flex-1 p-4">
                <h5 class="text-lg font-semibold mb-1">{{ $event->title }}</h5>
                <p class="text-gray-500 mb-0">{{ $event->institution->name }}</p>
                <span class="inline-block mt-2 px-2 py-1 text-xs rounded 
                                @if($eventStatus == 'Past') bg-gray-300 text-gray-700
                                @elseif($eventStatus == 'Upcoming') bg-green-200 text-green-800
                                @else bg-yellow-200 text-yellow-800
                                @endif
                            ">
                    {{ $eventStatus }}
                </span>
            </div>
        </div>
    </a>
    @endforeach
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $events->links() }}
</div>

@endsection
