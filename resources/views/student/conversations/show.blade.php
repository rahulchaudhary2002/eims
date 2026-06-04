@extends('layouts.student')

@section('title', ($conversation->institution?->name ?? 'Conversation') . ' · Messages')

@push('styles')
<style>
    html, body { overflow: hidden; }
    #messenger { height: calc(100vh - var(--header-height)); margin-top: var(--header-height); }
</style>
@endpush

@section('content')
<div id="messenger" class="flex overflow-hidden bg-white">

    {{-- LEFT: Conversation Sidebar (hidden on mobile) --}}
    <aside class="hidden md:flex md:w-80 xl:w-96 bg-white border-r border-gray-200 flex-col shrink-0 overflow-hidden">

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
                <input type="text" id="conv-search" placeholder="Search..."
                       class="w-full pl-9 pr-3 py-2 text-sm bg-gray-100 rounded-full focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#bee3f8] transition">
            </div>
        </div>

        {{-- Conversation List --}}
        <div class="flex-1 overflow-y-auto min-h-0" id="conv-list">
            @forelse($conversations as $conv)
            <a href="{{ route('student.conversations.show', $conv) }}"
               class="flex items-center gap-3 px-4 py-3.5 hover:bg-gray-50 transition-colors no-underline border-b border-gray-50 conv-item {{ $conv->id === $conversation->id ? 'bg-[#ebf8ff] !border-l-2 border-l-[#2c5aa0]' : '' }}"
               data-name="{{ strtolower($conv->institution?->name ?? '') }}">

                {{-- Avatar --}}
                @if($conv->institution?->logo)
                    <img src="{{ Storage::url($conv->institution->logo) }}" class="w-12 h-12 rounded-full object-cover shrink-0">
                @else
                    <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0" style="background-color:#2c5aa0">
                        <span class="text-white font-bold text-sm">{{ strtoupper(substr($conv->institution?->name ?? 'I', 0, 1)) }}</span>
                    </div>
                @endif

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm truncate {{ $conv->id === $conversation->id ? 'font-bold text-[#2c5aa0]' : ($conv->unread_count > 0 ? 'font-bold text-gray-900' : 'font-medium text-gray-800') }}">
                            {{ $conv->institution?->name }}
                        </p>
                        @if($conv->messages->first())
                        <span class="text-[11px] text-gray-400 shrink-0 whitespace-nowrap">
                            {{ $conv->messages->first()->created_at->shortDiff() }}
                        </span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between gap-2 mt-0.5">
                        <p class="text-xs truncate {{ $conv->unread_count > 0 && $conv->id !== $conversation->id ? 'text-gray-600 font-medium' : 'text-gray-400' }}">
                            {{ Str::limit($conv->messages->first()?->message ?? 'No messages yet', 38) }}
                        </p>
                        @if($conv->unread_count > 0 && $conv->id !== $conversation->id)
                        <span class="min-w-[20px] h-5 px-1 rounded-full bg-[#2c5aa0] text-white text-[10px] font-bold flex items-center justify-center shrink-0">
                            {{ $conv->unread_count > 9 ? '9+' : $conv->unread_count }}
                        </span>
                        @endif
                    </div>
                </div>
            </a>
            @empty
            <div class="px-4 py-12 text-center">
                <p class="text-sm text-gray-400">No conversations</p>
            </div>
            @endforelse
        </div>
    </aside>

    {{-- RIGHT: Chat Panel --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Chat Header --}}
        <div class="bg-white border-b border-gray-200 px-4 py-3 flex items-center gap-3 shrink-0 shadow-sm">

            {{-- Mobile back --}}
            <a href="{{ route('student.conversations.index') }}"
               class="md:hidden w-8 h-8 flex items-center justify-center text-gray-500 hover:text-[#2c5aa0] no-underline shrink-0">
                <i class="fas fa-arrow-left"></i>
            </a>

            {{-- Avatar --}}
            @if($conversation->institution?->logo)
                <img src="{{ Storage::url($conversation->institution->logo) }}" class="w-10 h-10 rounded-full object-cover shrink-0">
            @else
                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0" style="background-color:#2c5aa0">
                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($conversation->institution?->name ?? 'I', 0, 1)) }}</span>
                </div>
            @endif

            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-gray-900 truncate">{{ $conversation->institution?->name }}</p>
                <p class="text-xs text-gray-400">{{ \App\Models\Conversation::TYPES[$conversation->type] ?? $conversation->type }}</p>
            </div>

            @if($conversation->institution?->slug)
            <a href="{{ route('website.institutions.show', $conversation->institution->slug) }}"
               class="inline-flex items-center gap-1.5 text-xs text-[#4299e1] border border-[#bee3f8] px-2.5 sm:px-3 py-1.5 rounded-lg hover:bg-[#ebf8ff] transition no-underline shrink-0">
                <i class="fas fa-external-link-alt text-[10px]"></i>
                <span class="hidden sm:inline">View Profile</span>
                <span class="sm:hidden">Profile</span>
            </a>
            @endif
        </div>

        {{-- Messages Area --}}
        <div class="flex-1 overflow-y-auto min-h-0 px-4 py-4 bg-[#f7fafc] space-y-3" id="msg-container">
            @forelse($conversation->messages as $msg)
            @php $isStudent = $msg->sender_type === \App\Models\Student::class; @endphp

            <div class="flex {{ $isStudent ? 'justify-end' : 'justify-start' }} items-end gap-2">

                {{-- Institution avatar --}}
                @if(!$isStudent)
                @if($conversation->institution?->logo)
                    <img src="{{ Storage::url($conversation->institution->logo) }}" class="w-7 h-7 rounded-full object-cover shrink-0">
                @else
                    <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0" style="background-color:#2c5aa0">
                        <span class="text-[10px] font-bold text-white">{{ strtoupper(substr($conversation->institution?->name ?? 'A', 0, 1)) }}</span>
                    </div>
                @endif
                @endif

                <div class="max-w-[72%] sm:max-w-[60%]">
                    {{-- Bubble --}}
                    <div class="px-4 py-2.5 rounded-2xl {{ $isStudent
                        ? 'bg-[#2c5aa0] text-white rounded-br-none'
                        : 'bg-white text-gray-800 rounded-bl-none shadow-[0_1px_6px_rgba(0,0,0,0.08)]' }}">
                        @if($msg->message)
                        <p class="text-sm leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
                        @endif
                        @if($msg->attachment)
                        @php $attachExt = strtolower(pathinfo($msg->attachment, PATHINFO_EXTENSION)); $attachIsImage = in_array($attachExt, ['jpg','jpeg','png','gif','webp','avif']); @endphp
                        @if($attachIsImage)
                        <a href="{{ Storage::url($msg->attachment) }}" target="_blank" class="block mt-2 no-underline">
                            <img src="{{ Storage::url($msg->attachment) }}" alt="Image"
                                 class="rounded-xl max-w-[220px] max-h-[160px] object-cover border {{ $isStudent ? 'border-white/20' : 'border-gray-200' }}">
                        </a>
                        @else
                        <a href="{{ Storage::url($msg->attachment) }}" target="_blank"
                           class="inline-flex items-center gap-1.5 text-xs {{ $isStudent ? 'text-white/80 hover:text-white' : 'text-[#4299e1]' }} mt-1.5 no-underline hover:underline">
                            <i class="fas fa-paperclip"></i> Attachment
                        </a>
                        @endif
                        @endif
                    </div>
                    {{-- Timestamp --}}
                    <p class="text-[10px] text-gray-400 mt-1 px-1 {{ $isStudent ? 'text-right' : '' }}">
                        {{ $msg->created_at->format('M d · h:i A') }}
                    </p>
                </div>

                {{-- Student avatar --}}
                @if($isStudent)
                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0" style="background-color:#2c5aa0">
                    <span class="text-[10px] font-bold text-white">{{ strtoupper(substr(auth('student')->user()->name, 0, 1)) }}</span>
                </div>
                @endif

            </div>
            @empty
            <div class="flex flex-col items-center justify-center h-full text-center">
                <i class="fas fa-comment-dots text-4xl text-gray-200 mb-3"></i>
                <p class="text-sm text-gray-400 font-medium">No messages yet</p>
                <p class="text-xs text-gray-300 mt-1">Say hello to start the conversation!</p>
            </div>
            @endforelse
        </div>

        {{-- Message Input --}}
        <div class="bg-white border-t border-gray-200 px-4 py-3 shrink-0">
            {{-- Attachment preview --}}
            <div id="attach-preview" class="hidden items-center gap-2 mb-2 px-1">
                <i class="fas fa-paperclip text-[#4299e1] text-xs shrink-0"></i>
                <span id="attach-name" class="text-xs text-[#4299e1] truncate flex-1"></span>
                <button type="button" id="attach-clear" class="text-gray-400 hover:text-gray-600 shrink-0">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('student.conversations.messages.store', $conversation) }}"
                  enctype="multipart/form-data" id="msg-form">
                @csrf
                <div class="flex items-center gap-2">

                    {{-- Attach button --}}
                    <label class="cursor-pointer w-9 h-9 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 hover:text-[#4299e1] hover:border-[#bee3f8] transition shrink-0" title="Attach file">
                        <input type="file" name="attachment" class="hidden" id="attach-input">
                        <i class="fas fa-paperclip text-sm"></i>
                    </label>

                    {{-- Textarea --}}
                    <div class="flex-1">
                        <textarea name="message" id="msg-input" rows="1"
                                  placeholder="Type a message… (Enter to send)"
                                  class="w-full px-4 py-2.5 text-sm border border-gray-200 rounded-2xl focus:outline-none focus:border-[#4299e1] resize-none leading-relaxed"
                                  style="min-height: 42px; max-height: 120px; overflow-y: hidden;"></textarea>
                    </div>

                    {{-- Send button --}}
                    <button type="submit"
                        class="w-9 h-9 rounded-full bg-[#2c5aa0] text-white flex items-center justify-center hover:bg-[#1a365d] active:scale-95 transition shrink-0">
                        <i class="fas fa-paper-plane text-sm"></i>
                    </button>
                </div>
            </form>
        </div>

    </div>{{-- /chat panel --}}

</div>{{-- /messenger --}}
@endsection

@push('scripts')
<script>
// Scroll to latest message
const msgContainer = document.getElementById('msg-container');
if (msgContainer) msgContainer.scrollTop = msgContainer.scrollHeight;

// Auto-grow textarea
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

// Attachment preview
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
    attachName.textContent = '';
});

// Sidebar conversation search
document.getElementById('conv-search')?.addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('.conv-item').forEach(el => {
        el.style.display = (el.dataset.name || '').includes(q) ? '' : 'none';
    });
});

// ── Laravel Echo / Reverb live chat ────────────────────────────
(function () {
    const STUDENT_CLASS   = 'App\\Models\\Student';
    const institutionName = @json($conversation->institution?->name ?? 'I');
    const institutionLogo = @json($conversation->institution?->logo ? Storage::url($conversation->institution->logo) : null);
    const conversationId  = {{ $conversation->id }};
    const studentInitial  = @json(strtoupper(substr(auth('student')->user()->name, 0, 1)));
    const msgAction       = @json(route('student.conversations.messages.store', $conversation));

    function institutionAvatar() {
        if (institutionLogo) {
            return `<img src="${institutionLogo}" class="w-7 h-7 rounded-full object-cover shrink-0">`;
        }
        return `<div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0" style="background-color:#2c5aa0">
                    <span class="text-[10px] font-bold text-white">${institutionName.charAt(0).toUpperCase()}</span>
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

        const isStudent = data.sender_type === STUDENT_CLASS;
        const bubble = `
        <div class="flex ${isStudent ? 'justify-end' : 'justify-start'} items-end gap-2" data-message-id="${data.id}">
            ${!isStudent ? institutionAvatar() : ''}
            <div class="max-w-[72%] sm:max-w-[60%]">
                <div class="px-4 py-2.5 rounded-2xl ${isStudent
                    ? 'bg-[#2c5aa0] text-white rounded-br-none'
                    : 'bg-white text-gray-800 rounded-bl-none shadow-[0_1px_6px_rgba(0,0,0,0.08)]'}">
                    ${data.message ? `<p class="text-sm leading-relaxed whitespace-pre-line">${escapeHtml(data.message)}</p>` : ''}
                    ${data.attachment ? (() => {
                        const ext = data.attachment.split('.').pop().toLowerCase();
                        const isImg = ['jpg','jpeg','png','gif','webp','avif'].includes(ext);
                        return isImg
                            ? `<a href="/storage/${data.attachment}" target="_blank" class="block mt-2 no-underline">
                                <img src="/storage/${data.attachment}" alt="Image"
                                     class="rounded-xl max-w-[220px] max-h-[160px] object-cover border ${isStudent ? 'border-white/20' : 'border-gray-200'}">
                               </a>`
                            : `<a href="/storage/${data.attachment}" target="_blank"
                                class="inline-flex items-center gap-1.5 text-xs ${isStudent ? 'text-white/80 hover:text-white' : 'text-[#4299e1]'} mt-1.5 no-underline hover:underline">
                                <i class="fas fa-paperclip"></i> Attachment</a>`;
                    })() : ''}
                </div>
                <p class="text-[10px] text-gray-400 mt-1 px-1 ${isStudent ? 'text-right' : ''}">
                    ${data.created_at}
                </p>
            </div>
            ${isStudent ? `<div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0" style="background-color:#2c5aa0">
                <span class="text-[10px] font-bold text-white">${studentInitial}</span>
            </div>` : ''}
        </div>`;

        const empty = container.querySelector('.flex.flex-col.items-center');
        if (empty) empty.remove();

        container.insertAdjacentHTML('beforeend', bubble);
        container.scrollTop = container.scrollHeight;
    }

    // AJAX form submission - no page reload, keeps Echo subscription alive
    const form = document.getElementById('msg-form');
    const sendBtn = form?.querySelector('button[type="submit"]');
    if (form) {
        const submitForm = async () => {
            const msgInput  = document.getElementById('msg-input');
            const attachInput = document.getElementById('attach-input');
            const hasContent = msgInput?.value.trim() || attachInput?.files.length > 0;
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
                if (msgInput) { msgInput.value = ''; msgInput.style.height = '42px'; }
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

        // Override textarea keydown to use AJAX submit
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
            .listen('.message.sent', (data) => { appendMessage(data); });
    })();
})();
</script>
@endpush
