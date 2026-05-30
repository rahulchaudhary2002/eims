{{--
    Messages thread partial — included in conversations/show.blade.php
    Expects: $conversation (with messages.sender loaded)
--}}

<div class="eims-card overflow-hidden">
    <div class="card-header flex items-center justify-between">
        <h2 class="eims-card-title !mb-0 !pb-0 !border-0">Messages</h2>
        <span class="text-sm text-slate-500">{{ $conversation->messages->count() }} message{{ $conversation->messages->count() !== 1 ? 's' : '' }}</span>
    </div>

    {{-- Thread --}}
    @if($conversation->messages->isEmpty())
        <div class="px-6 py-10 text-center text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z"/></svg>
            <p class="text-sm">No messages yet. Send the first one below.</p>
        </div>
    @else
        <div class="divide-y divide-slate-100 max-h-[480px] overflow-y-auto">
            @foreach($conversation->messages as $msg)
                <div class="px-6 py-4 flex gap-3 {{ $msg->sender_type === 'App\\Models\\User' ? 'bg-blue-50/40' : '' }}">
                    {{-- Avatar --}}
                    <div class="shrink-0 w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-semibold
                        {{ $msg->sender_type === 'App\\Models\\User' ? 'bg-blue-500' : 'bg-indigo-500' }}">
                        {{ strtoupper(substr($msg->sender?->name ?? '?', 0, 1)) }}
                    </div>

                    {{-- Body --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-baseline gap-2 mb-1">
                            <span class="text-sm font-semibold text-slate-800">{{ $msg->sender?->name ?? 'Deleted' }}</span>
                            <span class="text-xs text-slate-400">{{ \App\Models\Message::SENDER_TYPES[$msg->sender_type] ?? $msg->sender_type }}</span>
                            <span class="text-xs text-slate-400 ml-auto shrink-0">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                        </div>

                        @if($msg->message)
                            <p class="text-sm text-slate-700 whitespace-pre-wrap">{{ $msg->message }}</p>
                        @endif

                        @if($msg->attachment)
                            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-200 rounded-lg text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                                <a href="{{ Storage::url($msg->attachment) }}" target="_blank" class="text-blue-600 hover:underline truncate max-w-xs">
                                    {{ basename($msg->attachment) }}
                                </a>
                            </div>
                        @endif

                        <div class="flex items-center gap-3 mt-1.5">
                            @if($msg->read_at)
                                <span class="text-xs text-slate-400">Read {{ $msg->read_at->format('d M Y, H:i') }}</span>
                            @else
                                <span class="text-xs text-amber-500">Unread</span>
                            @endif
                            <a href="{{ route('admin.messages.show', $msg) }}" class="text-xs text-slate-400 hover:text-slate-600">View</a>
                            <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" class="inline"
                                  onsubmit="return confirm('Delete this message?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    {{-- Send form --}}
    <div class="border-t border-slate-100 px-6 py-4">
        <h3 class="text-sm font-semibold text-slate-700 mb-3">Send Message</h3>
        <form method="POST" action="{{ route('admin.conversations.messages.store', $conversation) }}"
              enctype="multipart/form-data">
            @csrf

            @if($errors->any())
                <div class="alert alert-danger mb-3 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Sender --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="form-label required">Send As (Type)</label>
                    <select name="sender_type" class="form-control @error('sender_type') is-invalid @enderror" required>
                        <option value="">— Select Sender Type —</option>
                        @foreach(\App\Models\Message::SENDER_TYPES as $value => $label)
                            <option value="{{ $value }}" {{ old('sender_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('sender_type') <p class="form-error">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="form-label required">Sender ID</label>
                    <input type="number" name="sender_id" value="{{ old('sender_id', auth('web')->id()) }}"
                           class="form-control @error('sender_id') is-invalid @enderror"
                           placeholder="User / Student ID" min="1" required>
                    <p class="text-xs text-slate-400 mt-0.5">Your admin ID: {{ auth('web')->id() }}</p>
                    @error('sender_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Message</label>
                <textarea name="message" rows="3"
                          class="form-control @error('message') is-invalid @enderror"
                          placeholder="Type your message…">{{ old('message') }}</textarea>
                @error('message') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="form-label">Attachment <span class="text-slate-400 font-normal">(optional, max 10 MB)</span></label>
                <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror">
                @error('attachment') <p class="form-error">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                Send
            </button>
        </form>
    </div>
</div>
