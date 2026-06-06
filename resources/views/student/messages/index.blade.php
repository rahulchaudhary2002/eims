@extends('layouts.student')

@section('title', 'Messages')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <h1 class="text-2xl md:text-3xl font-bold">Messages</h1>
        <p class="text-white/70 text-sm mt-1">All your conversation threads with institutions</p>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-3">

        @forelse($conversations as $conv)
        <a href="{{ route('student.conversations.show', $conv) }}"
           class="flex items-center gap-4 bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border {{ $conv->unread_count > 0 ? 'border-[#bee3f8]' : 'border-gray-200' }} px-5 py-4 hover:shadow-md transition-all no-underline">
            @if(storage_exists($conv->institution?->logo))
                <img src="{{ storage_url($conv->institution->logo) }}" class="w-11 h-11 rounded-xl object-cover shrink-0">
            @else
                <div class="w-11 h-11 rounded-xl bg-[#ebf8ff] flex items-center justify-center shrink-0">
                    <span class="text-[#2c5aa0] font-bold">{{ strtoupper(substr($conv->institution?->name ?? 'I', 0, 1)) }}</span>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-bold text-gray-800">{{ $conv->institution?->name }}</p>
                    @if($conv->messages->first())
                    <span class="text-xs text-gray-400">{{ $conv->messages->first()->created_at->diffForHumans() }}</span>
                    @endif
                </div>
                @if($conv->messages->first())
                <p class="text-xs text-gray-500 truncate mt-0.5">{{ $conv->messages->first()->message ?? 'Attachment' }}</p>
                @endif
            </div>
            @if($conv->unread_count > 0)
            <span class="w-6 h-6 rounded-full bg-[#2c5aa0] text-white text-xs font-bold flex items-center justify-center shrink-0">{{ min($conv->unread_count, 9) }}</span>
            @endif
        </a>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-comments text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No messages yet</p>
            <a href="{{ route('student.conversations.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">Start a Conversation</a>
        </div>
        @endforelse

    </div>
</section>

@endsection
