@extends('admin.layouts.app')

@section('title', 'Message #' . $message->id)

@section('content')
<div class="space-y-5">
<x-admin.page-header title="Message #{{ $message->id }}"
    subtitle="Conversation #{{ $message->conversation_id }}{{ $message->conversation?->student ? ' / '.$message->conversation->student->name : '' }}"
    :breadcrumbs="[
        ['label'=>'Dashboard','route'=>'admin.dashboard'],
        ['label'=>'Messages','route'=>'admin.messages.index'],
        ['label'=>'Message #'.$message->id],
    ]">
    <x-slot:actions>
        <a href="{{ route('admin.conversations.show', $message->conversation_id) }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Conversation
        </a>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">All Messages</a>
    </x-slot:actions>
</x-admin.page-header>

@if(session('success'))
    <div class="alert alert-success mb-6">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Message body --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="eims-card overflow-hidden">
            <div class="card-header flex items-center justify-between">
                <h2 class="eims-card-title !mb-0 !pb-0 !border-0">Message Content</h2>
                @if($message->read_at)
                    <span class="badge badge-success">Read {{ $message->read_at->format('d M Y, H:i') }}</span>
                @else
                    <span class="badge badge-warning">Unread</span>
                @endif
            </div>
            <div class="card-body">
                @if($message->message)
                    <div class="bg-slate-50 border border-slate-100 rounded-lg p-4 text-slate-700 text-sm whitespace-pre-wrap">{{ $message->message }}</div>
                @else
                    <p class="text-slate-400 italic text-sm">No text content.</p>
                @endif

                @if(storage_exists($message->attachment))
                    <div class="mt-4 flex items-center gap-3 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-700 truncate">{{ basename($message->attachment) }}</p>
                        </div>
                        <a href="{{ storage_url($message->attachment) }}" target="_blank" class="btn btn-secondary btn-sm shrink-0">Download</a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">
        <div class="eims-card overflow-hidden">
            <div class="card-header">
                <h2 class="eims-card-title !mb-0 !pb-0 !border-0">Sender</h2>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Type</p>
                    <p class="text-sm font-medium text-slate-700">{{ \App\Models\Message::SENDER_TYPES[$message->sender_type] ?? $message->sender_type }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Name</p>
                    @if($message->sender)
                        @if($message->sender_type === 'App\\Models\\Student')
                            <a href="{{ route('admin.students.show', $message->sender) }}" class="text-blue-600 hover:underline text-sm font-medium">{{ $message->sender->name }}</a>
                        @else
                            <p class="text-sm font-medium text-slate-700">{{ $message->sender->name }}</p>
                        @endif
                    @else
                        <span class="text-slate-400 text-sm">Deleted</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="eims-card overflow-hidden">
            <div class="card-header">
                <h2 class="eims-card-title !mb-0 !pb-0 !border-0">Conversation</h2>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Conversation</p>
                    <a href="{{ route('admin.conversations.show', $message->conversation_id) }}" class="text-blue-600 hover:underline text-sm">#{{ $message->conversation_id }}</a>
                </div>
                @if($message->conversation?->student)
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Student</p>
                        <a href="{{ route('admin.students.show', $message->conversation->student) }}" class="text-blue-600 hover:underline text-sm">{{ $message->conversation->student->name }}</a>
                    </div>
                @endif
                @if($message->conversation?->institution)
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Institution</p>
                        <a href="{{ route('admin.institutions.show', $message->conversation->institution) }}" class="text-blue-600 hover:underline text-sm">{{ $message->conversation->institution->name }}</a>
                    </div>
                @endif
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Sent At</p>
                    <p class="text-sm text-slate-700">{{ $message->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="eims-card overflow-hidden">
            <div class="card-header">
                <h2 class="eims-card-title !mb-0 !pb-0 !border-0">Actions</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.messages.destroy', $message) }}"
                      onsubmit="return confirm('Delete this message permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full">Delete Message</button>
                </form>
            </div>
        </div>
    </div>

</div>
</div>
@endsection
