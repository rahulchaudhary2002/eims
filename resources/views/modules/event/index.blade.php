@extends('layouts.app')

@section('title', 'Events')

@section('content')

<div class="flex flex-wrap -mx-2">
    @foreach($events as $event)
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
                            @if($event->status == 'Past') bg-gray-300 text-gray-700
                            @elseif($event->status == 'Upcoming') bg-green-200 text-green-800
                            @else bg-yellow-200 text-yellow-800
                            @endif
                        ">
                    {{ $event->status }}
                </span>
            </div>
        </div>
    </a>
    @endforeach
</div>

@endsection