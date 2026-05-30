@extends('layouts.student')

@section('title', 'Messages')

@push('styles')
<style>
    html, body { overflow: hidden; }
    #messenger { height: calc(100vh - var(--header-height)); margin-top: var(--header-height); }
</style>
@endpush

@section('content')
<div id="messenger" class="flex overflow-hidden bg-white">

    {{-- LEFT: Conversation Sidebar --}}
    <aside class="w-full md:w-80 xl:w-96 bg-white border-r border-gray-200 flex flex-col shrink-0 overflow-hidden">

        {{-- Sidebar Header --}}
        <div class="px-4 pt-4 pb-3 border-b border-gray-100 shrink-0">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-base font-bold text-gray-900">Messages</h2>
                <a href="{{ route('student.conversations.create') }}"
                   class="w-9 h-9 rounded-full bg-[#2c5aa0] text-white flex items-center justify-center hover:bg-[#1a365d] transition no-underline"
                   title="New conversation">
                    <i class="fas fa-edit text-sm"></i>
                </a>
            </div>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                <input type="text" id="conv-search" placeholder="Search conversations..."
                       class="w-full pl-9 pr-3 py-2 text-sm bg-gray-100 rounded-full focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#bee3f8] transition">
            </div>
        </div>

        {{-- Conversation List --}}
        <div class="flex-1 overflow-y-auto min-h-0" id="conv-list">
            @forelse($conversations as $conv)
            <a href="{{ route('student.conversations.show', $conv) }}"
               class="flex items-center gap-3 px-4 py-3.5 hover:bg-gray-50 transition-colors no-underline border-b border-gray-50 conv-item"
               data-name="{{ strtolower($conv->institution?->name ?? '') }}">

                {{-- Avatar --}}
                @if($conv->institution?->logo)
                    <img src="{{ Storage::url($conv->institution->logo) }}" class="w-12 h-12 rounded-full object-cover shrink-0">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#4299e1] to-[#2c5aa0] flex items-center justify-center shrink-0">
                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($conv->institution?->name ?? 'I', 0, 1)) }}</span>
                    </div>
                @endif

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm truncate {{ $conv->unread_count > 0 ? 'font-bold text-gray-900' : 'font-medium text-gray-800' }}">
                            {{ $conv->institution?->name }}
                        </p>
                        @if($conv->messages->first())
                        <span class="text-[11px] text-gray-400 shrink-0 whitespace-nowrap">
                            {{ $conv->messages->first()->created_at->shortDiff() }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between gap-2 mt-0.5">
                        <p class="text-xs truncate {{ $conv->unread_count > 0 ? 'text-gray-600 font-medium' : 'text-gray-400' }}">
                            {{ Str::limit($conv->messages->first()?->message ?? 'No messages yet', 38) }}
                        </p>
                        @if($conv->unread_count > 0)
                        <span class="min-w-[20px] h-5 px-1 rounded-full bg-[#2c5aa0] text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                            {{ $conv->unread_count > 9 ? '9+' : $conv->unread_count }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="px-4 py-20 text-center">
                <i class="fas fa-comment-dots text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-sm font-semibold text-gray-500">No conversations yet</p>
                <p class="text-xs text-gray-400 mt-1">Start a conversation with an institution</p>
                <a href="{{ route('student.conversations.create') }}"
                   class="mt-4 inline-flex items-center gap-1.5 text-sm text-[#2c5aa0] font-semibold hover:underline no-underline">
                    <i class="fas fa-plus"></i> Start a conversation
                </a>
            </div>
            @endforelse
        </div>
    </aside>

    {{-- RIGHT: Empty State (hidden on mobile) --}}
    <div class="hidden md:flex flex-1 flex-col items-center justify-center bg-[#f7fafc]">
        <div class="text-center max-w-sm px-4">
            <div class="w-24 h-24 rounded-full bg-white shadow-[0_4px_24px_rgba(44,90,160,0.12)] flex items-center justify-center mx-auto mb-5">
                <i class="fas fa-comments text-4xl text-[#bee3f8]"></i>
            </div>
            <h3 class="text-gray-800 font-bold text-lg mb-2">Your Messages</h3>
            <p class="text-gray-400 text-sm leading-relaxed">Select a conversation to read messages, or start a new conversation with an institution.</p>
            <a href="{{ route('student.conversations.create') }}"
               class="mt-5 inline-flex items-center gap-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:opacity-90 transition no-underline shadow-md">
                <i class="fas fa-plus"></i> New Conversation
            </a>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.getElementById('conv-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.conv-item').forEach(el => {
        el.style.display = (el.dataset.name || '').includes(q) ? '' : 'none';
    });
});
</script>
@endpush
