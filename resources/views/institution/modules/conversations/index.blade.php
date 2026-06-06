@extends('institution.layouts.app')

@section('title', 'Messages')

@section('page-specific-style')
<style>
    main { padding-bottom: 0 !important; overflow: hidden; }
    #messenger { height: calc(100vh - var(--header-height) - 24px); }
</style>
@endsection

@section('content')
<div id="messenger" class="flex overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- LEFT: Conversation Sidebar --}}
    <aside class="w-full md:w-72 xl:w-80 bg-white border-r border-slate-200 flex flex-col shrink-0 overflow-hidden">

        {{-- Header --}}
        <div class="px-4 pt-4 pb-3 border-b border-slate-100 shrink-0">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-sm font-bold text-slate-800">Conversations</h2>
                <span class="text-xs text-slate-400">{{ $conversations->count() }} total</span>
            </div>
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" id="conv-search" placeholder="Search students..."
                       class="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 transition">
            </div>
        </div>

        {{-- List --}}
        <div class="flex-1 overflow-y-auto min-h-0" id="conv-list">
            @forelse($conversations as $conv)
            <a href="{{ route('institution.conversations.show', $conv) }}"
               class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-50 no-underline conv-item"
               data-name="{{ strtolower($conv->student?->name ?? '') }}">

                @if(storage_exists($conv->student?->avatar))
                    <img src="{{ storage_url($conv->student->avatar) }}" class="w-10 h-10 rounded-full object-cover shrink-0">
                @else
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-white font-semibold text-sm" style="background-color:#2563eb">
                        {{ strtoupper(substr($conv->student?->name ?? 'S', 0, 1)) }}
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs font-semibold text-slate-800 truncate {{ $conv->unread_count > 0 ? 'font-bold' : '' }}">
                            {{ $conv->student?->name ?? 'Unknown Student' }}
                        </p>
                        @if($conv->messages->first())
                        <span class="text-[10px] text-slate-400 shrink-0 whitespace-nowrap">
                            {{ $conv->messages->first()->created_at->shortDiff() }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between gap-2 mt-0.5">
                        <p class="text-[11px] text-slate-400 truncate {{ $conv->unread_count > 0 ? 'text-slate-600 font-medium' : '' }}">
                            {{ Str::limit($conv->messages->first()?->message ?? 'No messages yet', 36) }}
                        </p>
                        @if($conv->unread_count > 0)
                        <span class="min-w-[18px] h-[18px] px-1 rounded-full bg-blue-600 text-white text-[9px] font-bold flex items-center justify-center shrink-0">
                            {{ $conv->unread_count > 9 ? '9+' : $conv->unread_count }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="px-4 py-16 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                <p class="text-xs font-semibold text-slate-400">No conversations yet</p>
                <p class="text-[11px] text-slate-300 mt-1">Students will appear here when they message you</p>
            </div>
            @endforelse
        </div>
    </aside>

    {{-- RIGHT: Empty State --}}
    <div class="hidden md:flex flex-1 flex-col items-center justify-center bg-slate-50">
        <div class="text-center max-w-xs px-4">
            <div class="w-16 h-16 rounded-full bg-white shadow-sm border border-slate-100 flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
            </div>
            <p class="text-sm font-semibold text-slate-600">Select a conversation</p>
            <p class="text-xs text-slate-400 mt-1 leading-relaxed">Choose a student conversation from the list to view and respond to messages.</p>
        </div>
    </div>

</div>
@endsection

@section('page-specific-script')
<script>
document.getElementById('conv-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.conv-item').forEach(el => {
        el.style.display = (el.dataset.name || '').includes(q) ? '' : 'none';
    });
});
</script>
@endsection
