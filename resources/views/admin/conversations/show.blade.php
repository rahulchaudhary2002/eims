@extends('admin.layouts.app')

@section('title', 'Conversation #' . $conversation->id)

@section('content')
<div class="content-header">
    <div>
        <h1 class="content-title">Conversation #{{ $conversation->id }}</h1>
        <p class="content-subtitle">
            {{ \App\Models\Conversation::TYPES[$conversation->type] ?? $conversation->type }}
            @if($conversation->student) · {{ $conversation->student->name }} @endif
            @if($conversation->institution) · {{ $conversation->institution->name }} @endif
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.conversations.edit', $conversation) }}" class="btn btn-secondary">Edit</a>
        <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">← Back to Conversations</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-6">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Participants --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Participants</h2>
            </div>
            <div class="card-body space-y-4">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Student</p>
                        @if($conversation->student)
                            <a href="{{ route('admin.students.show', $conversation->student) }}" class="text-blue-600 hover:underline font-medium">
                                {{ $conversation->student->name }}
                            </a>
                            <p class="text-xs text-slate-500">{{ $conversation->student->email }}</p>
                        @else
                            <span class="text-slate-400 text-sm">No student linked</span>
                        @endif
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="shrink-0 w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Institution</p>
                        @if($conversation->institution)
                            <a href="{{ route('admin.institutions.show', $conversation->institution) }}" class="text-blue-600 hover:underline font-medium">
                                {{ $conversation->institution->name }}
                            </a>
                        @else
                            <span class="text-slate-400 text-sm">No institution linked</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('admin.conversations.partials.messages', ['conversation' => $conversation])
    </div>

    {{-- Details sidebar --}}
    <div class="space-y-6">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Details</h2>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Type</p>
                    <span class="badge">{{ \App\Models\Conversation::TYPES[$conversation->type] ?? $conversation->type }}</span>
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Started</p>
                    <p class="text-sm text-slate-700">{{ $conversation->created_at->format('d M Y, H:i') }}</p>
                </div>
                @if($conversation->updated_at->ne($conversation->created_at))
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Last Updated</p>
                        <p class="text-sm text-slate-700">{{ $conversation->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Actions</h2>
            </div>
            <div class="card-body space-y-2">
                <a href="{{ route('admin.conversations.edit', $conversation) }}" class="btn btn-secondary w-full">Edit Conversation</a>
                <form method="POST" action="{{ route('admin.conversations.destroy', $conversation) }}"
                      onsubmit="return confirm('Delete this conversation permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full">Delete Conversation</button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection
