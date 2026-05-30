@extends('layouts.student')

@section('title', 'Conversations')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Conversations</h1>
                <p class="text-white/70 text-sm mt-1">Your messages with institutions</p>
            </div>
            <a href="{{ route('student.conversations.create') }}"
               class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm no-underline shrink-0">
                <i class="fas fa-plus"></i> New Conversation
            </a>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-3">

        @forelse($conversations as $conv)
        <a href="{{ route('student.conversations.show', $conv) }}"
           class="flex items-center gap-4 bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border {{ $conv->unread_count > 0 ? 'border-[#bee3f8]' : 'border-gray-200' }} px-5 py-4 hover:shadow-md transition-all no-underline">
            @if($conv->institution?->logo)
                <img src="{{ Storage::url($conv->institution->logo) }}" class="w-11 h-11 rounded-xl object-cover shrink-0">
            @else
                <div class="w-11 h-11 rounded-xl bg-[#ebf8ff] flex items-center justify-center shrink-0">
                    <span class="text-[#2c5aa0] font-bold">{{ strtoupper(substr($conv->institution?->name ?? 'I', 0, 1)) }}</span>
                </div>
            @endif
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2">
                    <p class="text-sm font-bold text-gray-800 truncate">{{ $conv->institution?->name }}</p>
                    @if($conv->messages->first())
                    <span class="text-xs text-gray-400 shrink-0">{{ $conv->messages->first()->created_at->diffForHumans() }}</span>
                    @endif
                </div>
                <div class="flex items-center gap-2 mt-0.5">
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ \App\Models\Conversation::TYPES[$conv->type] ?? $conv->type }}</span>
                    @if($conv->messages->first())
                    <p class="text-xs text-gray-400 truncate">{{ $conv->messages->first()->message ?? 'Attachment' }}</p>
                    @endif
                </div>
            </div>
            @if($conv->unread_count > 0)
            <span class="w-6 h-6 rounded-full bg-[#2c5aa0] text-white text-xs font-bold flex items-center justify-center shrink-0">
                {{ min($conv->unread_count, 9) }}
            </span>
            @endif
        </a>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-comments text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No conversations yet</p>
            <a href="{{ route('student.conversations.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">Start Conversation</a>
        </div>
        @endforelse

        @if($conversations->hasPages())
        <div>{{ $conversations->links() }}</div>
        @endif
    </div>
</section>

@endsection
