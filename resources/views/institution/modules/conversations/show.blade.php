@extends('institution.layouts.app')

@section('title', ($conversation->student?->name ?? 'Conversation') . ' · Messages')

@section('page-specific-style')
<style>
    main { padding-bottom: 0 !important; overflow: hidden; }
    #messenger { height: calc(100vh - var(--header-height) - 24px); }
</style>
@endsection

@section('content')
<div id="messenger" class="flex overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

    {{-- LEFT: Conversation Sidebar (hidden on mobile) --}}
    <aside class="hidden md:flex md:w-72 xl:w-80 bg-white border-r border-slate-200 flex-col shrink-0 overflow-hidden">

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
               class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors border-b border-slate-50 no-underline conv-item {{ $conv->id === $conversation->id ? 'bg-blue-50 border-l-2 border-l-blue-500' : '' }}"
               data-name="{{ strtolower($conv->student?->name ?? '') }}">

                @if($conv->student?->avatar)
                    <img src="{{ Storage::url($conv->student->avatar) }}" class="w-10 h-10 rounded-full object-cover shrink-0">
                @else
                    <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-white font-semibold text-sm" style="background-color:#2563eb">
                        {{ strtoupper(substr($conv->student?->name ?? 'S', 0, 1)) }}
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-xs truncate {{ $conv->id === $conversation->id ? 'font-bold text-blue-600' : ($conv->unread_count > 0 ? 'font-bold text-slate-800' : 'font-semibold text-slate-700') }}">
                            {{ $conv->student?->name ?? 'Unknown Student' }}
                        </p>
                        @if($conv->messages->first())
                        <span class="text-[10px] text-slate-400 shrink-0 whitespace-nowrap">
                            {{ $conv->messages->first()->created_at->shortDiff() }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between gap-2 mt-0.5">
                        <p class="text-[11px] truncate {{ $conv->unread_count > 0 && $conv->id !== $conversation->id ? 'text-slate-600 font-medium' : 'text-slate-400' }}">
                            {{ Str::limit($conv->messages->first()?->message ?? 'No messages yet', 36) }}
                        </p>
                        @if($conv->unread_count > 0 && $conv->id !== $conversation->id)
                        <span class="min-w-[18px] h-[18px] px-1 rounded-full bg-blue-600 text-white text-[9px] font-bold flex items-center justify-center shrink-0">
                            {{ $conv->unread_count > 9 ? '9+' : $conv->unread_count }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="px-4 py-12 text-center">
                <p class="text-xs text-slate-400">No conversations</p>
            </div>
            @endforelse
        </div>
    </aside>

    {{-- RIGHT: Chat Panel --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Chat Header --}}
        <div class="bg-white border-b border-slate-200 px-4 py-3 flex items-center gap-3 shrink-0">

            {{-- Mobile back --}}
            <a href="{{ route('institution.conversations.index') }}"
               class="md:hidden w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-600 no-underline shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            </a>

            {{-- Student avatar --}}
            @if($conversation->student?->avatar)
                <img src="{{ Storage::url($conversation->student->avatar) }}" class="w-9 h-9 rounded-full object-cover shrink-0">
            @else
                <div class="w-9 h-9 rounded-full flex items-center justify-center shrink-0" style="background-color:#2563eb">
                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($conversation->student?->name ?? 'S', 0, 1)) }}</span>
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-slate-800 truncate">{{ $conversation->student?->name ?? 'Unknown Student' }}</p>
                <p class="text-[11px] text-slate-400">{{ \App\Models\Conversation::TYPES[$conversation->type] ?? $conversation->type }}</p>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                @if($conversation->student?->email)
                <span class="hidden sm:block text-xs text-slate-400 truncate max-w-[160px]">
                    {{ $conversation->student->email }}
                </span>
                @endif
                <span class="badge">{{ \App\Models\Conversation::TYPES[$conversation->type] ?? $conversation->type }}</span>
            </div>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto min-h-0 px-4 py-4 space-y-3 bg-slate-50" id="msg-container">
            @forelse($conversation->messages as $msg)
            @php $isInstitution = $msg->sender_type === \App\Models\User::class; @endphp

            <div class="flex {{ $isInstitution ? 'justify-end' : 'justify-start' }} items-end gap-2">

                @if(!$isInstitution)
                @if($msg->sender?->avatar)
                    <img src="{{ Storage::url($msg->sender->avatar) }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                @else
                    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0" style="background-color:#2563eb">
                        <span class="text-[10px] font-bold text-white">{{ strtoupper(substr($msg->sender?->name ?? 'S', 0, 1)) }}</span>
                    </div>
                @endif
                @endif

                <div class="max-w-[70%] sm:max-w-[60%]">
                    <div class="px-4 py-2.5 rounded-2xl {{ $isInstitution
                        ? 'bg-blue-600 text-white rounded-br-none'
                        : 'bg-white text-slate-800 rounded-bl-none shadow-sm border border-slate-100' }}">
                        @if($msg->message)
                        <p class="text-sm leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
                        @endif
                        @if($msg->attachment)
                        <a href="{{ Storage::url($msg->attachment) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 text-xs {{ $isInstitution ? 'text-white/80 hover:text-white' : 'text-blue-600' }} mt-1.5 no-underline hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                            Attachment
                        </a>
                        @endif
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1 px-1 {{ $isInstitution ? 'text-right' : '' }}">
                        {{ $msg->created_at->format('M d · h:i A') }}
                        @if(!$isInstitution)
                        · {{ $msg->sender?->name ?? 'Student' }}
                        @endif
                    </p>
                </div>

                @if($isInstitution)
                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0" style="background-color:#64748b">
                    <span class="text-[10px] font-bold text-white">{{ strtoupper(substr($msg->sender?->name ?? 'A', 0, 1)) }}</span>
                </div>
                @endif
            </div>

            @empty
            <div class="flex flex-col items-center justify-center h-full text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-200 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
                <p class="text-sm text-slate-400 font-medium">No messages yet</p>
            </div>
            @endforelse
        </div>

        {{-- Input --}}
        <div class="bg-white border-t border-slate-200 px-4 py-3 shrink-0">
            <div id="attach-preview" class="hidden items-center gap-2 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                <span id="attach-name" class="text-xs text-blue-600 truncate flex-1"></span>
                <button type="button" id="attach-clear" class="text-slate-400 hover:text-slate-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form method="POST" action="{{ route('institution.conversations.messages.store', $conversation) }}"
                  enctype="multipart/form-data" id="msg-form">
                @csrf
                <div class="flex items-center gap-2">

                    <label class="cursor-pointer w-8 h-8 rounded-lg border border-slate-200 flex items-center justify-center text-slate-400 hover:text-blue-500 hover:border-blue-200 transition shrink-0" title="Attach file">
                        <input type="file" name="attachment" class="hidden" id="attach-input">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                    </label>

                    <div class="flex-1">
                        <textarea name="message" id="msg-input" rows="1"
                                  placeholder="Type a message… (Enter to send)"
                                  class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-400 resize-none transition"
                                  style="min-height: 38px; max-height: 120px; overflow-y: hidden;"></textarea>
                    </div>

                    <button type="submit"
                        class="w-8 h-8 rounded-lg bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 active:scale-95 transition shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection

@section('page-specific-script')
<script>
const msgContainer = document.getElementById('msg-container');
if (msgContainer) msgContainer.scrollTop = msgContainer.scrollHeight;

const textarea = document.getElementById('msg-input');
if (textarea) {
    const MAX_H = 120;
    const grow = () => {
        textarea.style.height = 'auto';
        const next = Math.min(textarea.scrollHeight, MAX_H);
        textarea.style.height = next + 'px';
        textarea.style.overflowY = textarea.scrollHeight > MAX_H ? 'auto' : 'hidden';
    };
    textarea.addEventListener('input', grow);
    textarea.focus();
}

const attachInput = document.getElementById('attach-input');
const attachPreview = document.getElementById('attach-preview');
const attachName = document.getElementById('attach-name');
attachInput?.addEventListener('change', function () {
    if (this.files[0]) {
        attachName.textContent = this.files[0].name;
        attachPreview.classList.remove('hidden');
        attachPreview.classList.add('flex');
    }
});
document.getElementById('attach-clear')?.addEventListener('click', function () {
    attachInput.value = '';
    attachPreview.classList.add('hidden');
    attachPreview.classList.remove('flex');
});

document.getElementById('conv-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.conv-item').forEach(el => {
        el.style.display = (el.dataset.name || '').includes(q) ? '' : 'none';
    });
});

// ── Laravel Echo / Reverb live chat ────────────────────────────
(function () {
    const USER_CLASS      = 'App\\Models\\User';
    const studentName     = @json($conversation->student?->name ?? 'Student');
    const studentAvatar   = @json($conversation->student?->avatar ? Storage::url($conversation->student->avatar) : null);
    const conversationId  = {{ $conversation->id }};
    const msgAction       = @json(route('institution.conversations.messages.store', $conversation));

    function studentAvatarHtml() {
        if (studentAvatar) {
            return `<img src="${studentAvatar}" class="w-7 h-7 rounded-full object-cover shrink-0">`;
        }
        return `<div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0" style="background-color:#2563eb">
                    <span class="text-[10px] font-bold text-white">${studentName.charAt(0).toUpperCase()}</span>
                </div>`;
    }

    function staffAvatarHtml(name) {
        const initial = (name || 'A').charAt(0).toUpperCase();
        return `<div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0" style="background-color:#64748b">
                    <span class="text-[10px] font-bold text-white">${initial}</span>
                </div>`;
    }

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.appendChild(document.createTextNode(str));
        return d.innerHTML;
    }

    function appendMessage(data) {
        const container = document.getElementById('msg-container');
        if (!container) return;

        const isInstitution = data.sender_type === USER_CLASS;
        const bubble = `
        <div class="flex ${isInstitution ? 'justify-end' : 'justify-start'} items-end gap-2" data-message-id="${data.id}">
            ${!isInstitution ? studentAvatarHtml() : ''}
            <div class="max-w-[70%] sm:max-w-[60%]">
                <div class="px-4 py-2.5 rounded-2xl ${isInstitution
                    ? 'bg-blue-600 text-white rounded-br-none'
                    : 'bg-white text-slate-800 rounded-bl-none shadow-sm border border-slate-100'}">
                    ${data.message ? `<p class="text-sm leading-relaxed whitespace-pre-line">${escapeHtml(data.message)}</p>` : ''}
                    ${data.attachment ? `<a href="/storage/${data.attachment}" target="_blank"
                        class="inline-flex items-center gap-1.5 text-xs ${isInstitution ? 'text-white/80 hover:text-white' : 'text-blue-600'} mt-1.5 no-underline hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                        Attachment</a>` : ''}
                </div>
                <p class="text-[10px] text-slate-400 mt-1 px-1 ${isInstitution ? 'text-right' : ''}">
                    ${data.created_at}${!isInstitution ? ' · ' + escapeHtml(data.sender_name || 'Student') : ''}
                </p>
            </div>
            ${isInstitution ? staffAvatarHtml(data.sender_name) : ''}
        </div>`;

        const empty = container.querySelector('.flex.flex-col.items-center');
        if (empty) empty.remove();

        container.insertAdjacentHTML('beforeend', bubble);
        container.scrollTop = container.scrollHeight;
    }

    // AJAX form submission — no page reload, keeps Echo subscription alive
    const form = document.getElementById('msg-form');
    const sendBtn = form?.querySelector('button[type="submit"]');
    if (form) {
        const submitForm = async () => {
            const msgInput    = document.getElementById('msg-input');
            const attachInput = document.getElementById('attach-input');
            const hasContent  = msgInput?.value.trim() || attachInput?.files.length > 0;
            if (!hasContent) return;

            if (sendBtn) sendBtn.disabled = true;

            const formData = new FormData(form);
            try {
                const headers = {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                };
                if (window.Echo?.socketId()) {
                    headers['X-Socket-Id'] = window.Echo.socketId();
                }
                const res = await fetch(msgAction, { method: 'POST', headers, body: formData });
                if (!res.ok) throw new Error('Send failed');
                const data = await res.json();
                appendMessage(data);
                if (msgInput) { msgInput.value = ''; msgInput.style.height = '38px'; }
                if (attachInput) attachInput.value = '';
                const preview = document.getElementById('attach-preview');
                if (preview) { preview.classList.add('hidden'); preview.classList.remove('flex'); }
            } catch (err) {
                console.error('Message send error:', err);
            } finally {
                if (sendBtn) sendBtn.disabled = false;
                document.getElementById('msg-input')?.focus();
            }
        };

        form.addEventListener('submit', (e) => { e.preventDefault(); submitForm(); });

        document.getElementById('msg-input')?.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                submitForm();
            }
        });
    }

    (function waitForEcho() {
        if (!window.Echo) { setTimeout(waitForEcho, 100); return; }
        window.Echo.private(`conversation.${conversationId}`)
            .listen('.message.sent', (data) => {
                appendMessage(data);
                if (data.sender_type !== USER_CLASS) {
                    fetch(`/institution/conversations/${conversationId}/messages/read`, {
                        method: 'POST',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '', 'X-Requested-With': 'XMLHttpRequest' }
                    }).catch(() => {});
                }
            });
    })();
})();
</script>
@endsection
