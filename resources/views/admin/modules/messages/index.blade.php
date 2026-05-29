@extends('admin.layouts.app')

@section('title', 'Messages')

@section('content')
<div class="content-header">
    <div>
        <h1 class="content-title">Messages</h1>
        <p class="content-subtitle">Browse and manage all messages across conversations.</p>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.messages.index') }}" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Conversation #</label>
                <input type="number" name="conversation_id" value="{{ request('conversation_id') }}"
                       class="form-input" placeholder="Conversation ID">
            </div>
            <div>
                <label class="form-label">Sender Type</label>
                <select name="sender_type" class="form-select">
                    <option value="">All Senders</option>
                    @foreach($senderTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('sender_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Read Status</label>
                <select name="read_status" class="form-select">
                    <option value="">All</option>
                    <option value="read" {{ request('read_status') === 'read' ? 'selected' : '' }}>Read</option>
                    <option value="unread" {{ request('read_status') === 'unread' ? 'selected' : '' }}>Unread</option>
                </select>
            </div>
            <div>
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary w-full">Filter</button>
                <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary w-full">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        @if($messages->isEmpty())
            <div class="px-6 py-12 text-center text-slate-400">No messages found.</div>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Conversation</th>
                            <th>Sender</th>
                            <th>Message</th>
                            <th>Attachment</th>
                            <th>Read</th>
                            <th>Sent</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $msg)
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $msg->id }}</td>
                                <td class="text-sm">
                                    <a href="{{ route('admin.conversations.show', $msg->conversation_id) }}" class="text-blue-600 hover:underline">
                                        #{{ $msg->conversation_id }}
                                    </a>
                                </td>
                                <td class="text-sm">
                                    <span class="text-slate-500 text-xs block">{{ \App\Models\Message::SENDER_TYPES[$msg->sender_type] ?? $msg->sender_type }}</span>
                                    <span class="font-medium">{{ $msg->sender?->name ?? '—' }}</span>
                                </td>
                                <td class="text-sm max-w-xs truncate text-slate-600">
                                    {{ $msg->message ? \Illuminate\Support\Str::limit($msg->message, 60) : '—' }}
                                </td>
                                <td class="text-sm">
                                    @if($msg->attachment)
                                        <a href="{{ Storage::url($msg->attachment) }}" target="_blank" class="text-blue-600 hover:underline text-xs">View</a>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($msg->read_at)
                                        <span class="badge badge-success text-xs">Read</span>
                                    @else
                                        <span class="badge badge-warning text-xs">Unread</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">{{ $msg->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.messages.show', $msg) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.messages.destroy', $msg) }}" class="inline"
                                              onsubmit="return confirm('Delete this message?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $messages->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
