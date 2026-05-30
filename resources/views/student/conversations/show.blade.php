@extends('layouts.student')

@section('title', 'Conversation')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-16 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.conversations.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            @if($conversation->institution?->logo)
                <img src="{{ Storage::url($conversation->institution->logo) }}" class="w-10 h-10 rounded-xl object-cover">
            @else
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <span class="font-bold">{{ strtoupper(substr($conversation->institution?->name ?? 'I', 0, 1)) }}</span>
                </div>
            @endif
            <div>
                <h1 class="text-xl font-bold">{{ $conversation->institution?->name }}</h1>
                <p class="text-white/70 text-xs">{{ \App\Models\Conversation::TYPES[$conversation->type] ?? $conversation->type }}</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-6 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-3xl space-y-4">

            {{-- Messages --}}
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-5 space-y-4 max-h-[500px] overflow-y-auto" id="msg-container">
                @forelse($conversation->messages as $msg)
                @php $isStudent = $msg->sender_type === \App\Models\Student::class; @endphp
                <div class="flex {{ $isStudent ? 'justify-end' : 'justify-start' }} gap-2">
                    @if(!$isStudent)
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center shrink-0 self-end">
                        <span class="text-xs font-bold text-gray-600">{{ strtoupper(substr($msg->sender?->name ?? 'A', 0, 1)) }}</span>
                    </div>
                    @endif
                    <div class="max-w-[70%]">
                        <div class="px-4 py-2.5 rounded-2xl {{ $isStudent ? 'bg-[#2c5aa0] text-white rounded-br-sm' : 'bg-gray-100 text-gray-700 rounded-bl-sm' }}">
                            @if($msg->message)<p class="text-sm">{{ $msg->message }}</p>@endif
                            @if($msg->attachment)
                            <a href="{{ Storage::url($msg->attachment) }}" target="_blank"
                               class="flex items-center gap-1.5 text-xs {{ $isStudent ? 'text-white/80' : 'text-[#4299e1]' }} hover:underline mt-1 no-underline">
                                <i class="fas fa-paperclip"></i> Attachment
                            </a>
                            @endif
                        </div>
                        <p class="text-xs text-gray-400 mt-1 {{ $isStudent ? 'text-right' : '' }}">{{ $msg->created_at->format('H:i') }}</p>
                    </div>
                    @if($isStudent)
                    <div class="w-8 h-8 rounded-full bg-[#ebf8ff] flex items-center justify-center shrink-0 self-end">
                        <span class="text-xs font-bold text-[#2c5aa0]">{{ strtoupper(substr(auth('student')->user()->name, 0, 1)) }}</span>
                    </div>
                    @endif
                </div>
                @empty
                <p class="text-center text-sm text-gray-400 py-8">No messages yet. Start the conversation!</p>
                @endforelse
            </div>

            {{-- Message Input --}}
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-4">
                <form method="POST" action="{{ route('student.conversations.messages.store', $conversation) }}"
                      enctype="multipart/form-data" class="flex items-end gap-3">
                    @csrf
                    <div class="flex-1">
                        <textarea name="message" rows="2" placeholder="Type a message... (Ctrl+Enter to send)"
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1] resize-none"
                                  onkeydown="if(event.ctrlKey&&event.key==='Enter'){this.closest('form').submit();}"></textarea>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <label class="cursor-pointer w-10 h-10 rounded-xl border border-gray-200 flex items-center justify-center text-gray-400 hover:text-[#4299e1] hover:border-[#bee3f8] transition">
                            <input type="file" name="attachment" class="hidden">
                            <i class="fas fa-paperclip text-sm"></i>
                        </label>
                        <button type="submit"
                            class="w-10 h-10 rounded-xl bg-[#2c5aa0] text-white flex items-center justify-center hover:bg-[#1a365d] transition">
                            <i class="fas fa-paper-plane text-sm"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    const c = document.getElementById('msg-container');
    if (c) c.scrollTop = c.scrollHeight;
</script>
@endpush
