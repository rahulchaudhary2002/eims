@extends('admin.layouts.app')
@section('title', 'Comment #' . $postComment->id)
@section('page-title', 'Post Comment Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Comment #{{ $postComment->id }}"
        subtitle="{{ $postComment->post->title ?? 'Post Comment' }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Post Comments', 'route' => 'admin.post-comments.index'],
            ['label' => 'Comment #' . $postComment->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.post-comments.edit', $postComment) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.post-comments.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6 {{ $postComment->is_hidden ? 'border-l-4 border-red-400' : '' }}">
                @if($postComment->is_hidden)
                    <div class="flex items-center gap-2 mb-3 text-red-600 text-sm font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                        This comment is hidden from public view.
                    </div>
                @endif

                <div class="text-sm text-slate-700 whitespace-pre-line leading-relaxed">{{ $postComment->comment }}</div>

                <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide">Post</dt>
                        <dd class="mt-0.5">
                            @if($postComment->post)
                                <a href="{{ route('admin.posts.show', $postComment->post) }}" class="text-blue-600 hover:underline">{{ $postComment->post->title }}</a>
                            @else —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide">Author</dt>
                        <dd class="mt-0.5 text-slate-800">
                            {{ $postComment->commentable?->name ?? ($postComment->commentable?->email ?? '—') }}
                            <span class="text-xs text-slate-400 ml-1">
                                ({{ \App\Models\PostComment::COMMENTABLE_TYPES[$postComment->commentable_type] ?? class_basename($postComment->commentable_type) }})
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide">Reply To</dt>
                        <dd class="mt-0.5 text-slate-700">
                            @if($postComment->parent)
                                <a href="{{ route('admin.post-comments.show', $postComment->parent) }}" class="text-blue-600 hover:underline">
                                    Comment #{{ $postComment->parent_id }}
                                </a>
                                <p class="text-xs text-slate-400 line-clamp-1">{{ Str::limit($postComment->parent->comment, 60) }}</p>
                            @else
                                <span class="text-slate-400">Top-level comment</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wide">Posted At</dt>
                        <dd class="mt-0.5 text-slate-700">{{ $postComment->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </div>
            </div>

            {{-- Replies --}}
            @if($postComment->replies->isNotEmpty())
                <div class="eims-card overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700">Replies ({{ $postComment->replies->count() }})</h3>
                    </div>
                    <div class="divide-y divide-slate-100">
                        @foreach($postComment->replies as $reply)
                            <div class="px-6 py-3 {{ $reply->is_hidden ? 'opacity-60 bg-red-50' : '' }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs text-slate-400 mb-1">
                                            {{ $reply->commentable?->name ?? '—' }}
                                            <span class="ml-1">· {{ $reply->created_at->format('d M Y, H:i') }}</span>
                                            @if($reply->is_hidden) <span class="text-red-500 ml-1">· Hidden</span> @endif
                                        </p>
                                        <p class="text-sm text-slate-700 line-clamp-3">{{ $reply->comment }}</p>
                                    </div>
                                    <a href="{{ route('admin.post-comments.show', $reply) }}" class="btn-icon btn-icon-view shrink-0" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Moderation</h3>
                <div class="space-y-2">
                    <form action="{{ route('admin.post-comments.toggle-hidden', $postComment) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn {{ $postComment->is_hidden ? 'btn-primary' : 'btn-secondary' }} w-full text-sm">
                            {{ $postComment->is_hidden ? 'Make Visible' : 'Hide Comment' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.post-comments.edit', $postComment) }}" class="btn btn-secondary w-full text-sm">Edit Comment</a>
                    @if($postComment->post)
                        <a href="{{ route('admin.posts.show', $postComment->post) }}" class="btn btn-secondary w-full text-sm">View Post</a>
                    @endif
                    <form action="{{ route('admin.post-comments.destroy', $postComment) }}" method="POST" onsubmit="return confirm('Delete this comment and all its replies? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Comment
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
