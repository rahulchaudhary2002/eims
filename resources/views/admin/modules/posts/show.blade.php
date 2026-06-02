@extends('admin.layouts.app')
@section('title', $post->title)
@section('page-title', 'Post Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="{{ $post->title }}"
        subtitle="{{ \App\Models\Post::TYPES[$post->type] ?? $post->type }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Posts', 'route' => 'admin.posts.index'],
            ['label' => $post->title],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Thumbnail --}}
            @if($post->thumbnail)
                <div class="eims-card overflow-hidden">
                    <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}"
                        class="w-full h-56 object-cover">
                </div>
            @endif

            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Post Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Institution</dt>
                        <dd class="mt-1">
                            @if($post->institution)
                                <a href="{{ route('admin.institutions.show', $post->institution) }}" class="text-blue-600 hover:underline">{{ $post->institution->name }}</a>
                            @else <span class="text-slate-400">-</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Author</dt>
                        <dd class="mt-1 text-slate-800">{{ $post->creator->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Type</dt>
                        <dd class="mt-1 text-slate-800">{{ \App\Models\Post::TYPES[$post->type] ?? $post->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Published At</dt>
                        <dd class="mt-1 text-slate-800">{{ $post->published_at?->format('d M Y, H:i') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $post->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Last Updated</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $post->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>

                @if($post->content)
                    <div class="mt-5 pt-5 border-t border-slate-100">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Content</h4>
                        <div class="ck-content">{!! $post->content !!}</div>
                    </div>
                @endif
            </div>

        </div>

        {{-- Status + Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Status</h3>
                <div class="space-y-2">
                    @if($post->is_published)
                        <span class="badge badge-green">Published</span>
                        <p class="text-xs text-slate-400">{{ $post->published_at?->format('d M Y, H:i') }}</p>
                    @else
                        <span class="badge">Draft</span>
                    @endif
                    @if($post->is_featured)
                        <div class="flex items-center gap-1 text-amber-500 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                            Featured
                        </div>
                    @endif
                </div>

                <div class="space-y-2 mt-4">
                    <form action="{{ route('admin.posts.publish', $post) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn {{ $post->is_published ? 'btn-secondary' : 'btn-primary' }} w-full text-sm">
                            {{ $post->is_published ? 'Unpublish' : 'Publish' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.posts.feature', $post) }}" method="POST">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-secondary w-full text-sm">
                            {{ $post->is_featured ? 'Unfeature' : 'Mark as Featured' }}
                        </button>
                    </form>
                </div>
            </div>

            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Post
                    </a>
                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Post
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- Comments Thread --}}
    <div class="eims-card overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-semibold text-slate-800">Comments</h3>
                <span class="text-xs text-slate-400">{{ $post->comments->count() }} top-level</span>
            </div>
            <div class="flex items-center gap-2">
                @if($post->comments->count() > 0)
                    <a href="{{ route('admin.post-comments.index', ['post_id' => $post->id]) }}" class="text-sm text-blue-600 hover:underline">View all →</a>
                @endif
                <a href="{{ route('admin.post-comments.create', ['post_id' => $post->id]) }}" class="btn btn-primary text-xs py-1.5">Add Comment</a>
            </div>
        </div>

        @if($post->comments->isEmpty())
            <div class="px-6 py-6 text-center text-slate-400 text-sm">No comments yet.</div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($post->comments->take(10) as $comment)
                    <div class="px-6 py-4 {{ $comment->is_hidden ? 'opacity-60 bg-red-50' : '' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-medium text-slate-700">
                                        {{ $comment->commentable?->name ?? ($comment->commentable?->email ?? 'Unknown') }}
                                    </span>
                                    <span class="text-xs text-slate-400">· {{ $comment->created_at->format('d M Y, H:i') }}</span>
                                    @if($comment->is_hidden)
                                        <span class="text-xs text-red-500 font-medium">· Hidden</span>
                                    @endif
                                    @if($comment->replies->count() > 0)
                                        <span class="text-xs text-slate-400">· {{ $comment->replies->count() }} repl{{ $comment->replies->count() === 1 ? 'y' : 'ies' }}</span>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-700">{{ $comment->comment }}</p>

                                {{-- Replies (first 2) --}}
                                @if($comment->replies->isNotEmpty())
                                    <div class="mt-2 ml-4 space-y-2 border-l-2 border-slate-200 pl-3">
                                        @foreach($comment->replies->take(2) as $reply)
                                            <div class="{{ $reply->is_hidden ? 'opacity-60' : '' }}">
                                                <p class="text-xs text-slate-400">
                                                    {{ $reply->commentable?->name ?? '-' }} · {{ $reply->created_at->format('d M Y') }}
                                                    @if($reply->is_hidden) · <span class="text-red-500">Hidden</span> @endif
                                                </p>
                                                <p class="text-xs text-slate-600 line-clamp-2">{{ $reply->comment }}</p>
                                            </div>
                                        @endforeach
                                        @if($comment->replies->count() > 2)
                                            <a href="{{ route('admin.post-comments.show', $comment) }}" class="text-xs text-blue-600 hover:underline">
                                                +{{ $comment->replies->count() - 2 }} more replies
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 shrink-0">
                                <a href="{{ route('admin.post-comments.show', $comment) }}" class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <form action="{{ route('admin.post-comments.toggle-hidden', $comment) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-icon {{ $comment->is_hidden ? 'text-green-600' : 'text-amber-600' }}" title="{{ $comment->is_hidden ? 'Show' : 'Hide' }}">
                                        @if($comment->is_hidden)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Media Gallery --}}
    {{-- Reaction Counts --}}
    @php
        $reactionCounts = $post->reactions->groupBy('reaction')
            ->map(fn ($group) => $group->count());
        $totalReactions = $post->reactions->count();
    @endphp
    @if($totalReactions > 0 || true)
        <div class="eims-card p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base font-semibold text-slate-800">
                    Reactions
                    <span class="text-sm font-normal text-slate-400 ml-1">({{ $totalReactions }} total)</span>
                </h3>
                @if($totalReactions > 0)
                    <a href="{{ route('admin.post-reactions.index', ['post_id' => $post->id]) }}" class="text-sm text-blue-600 hover:underline">
                        View all →
                    </a>
                @endif
            </div>
            @if($totalReactions > 0)
                <div class="flex flex-wrap gap-3">
                    @foreach(\App\Models\PostReaction::REACTIONS as $key => $label)
                        @php $count = $reactionCounts->get($key, 0); @endphp
                        <div class="flex items-center gap-2 px-3 py-2 rounded-lg border {{ $count > 0 ? 'bg-indigo-50 border-indigo-200' : 'bg-slate-50 border-slate-200' }}">
                            <span class="text-xl">
                                @switch($key)
                                    @case('like') 👍 @break
                                    @case('love') ❤️ @break
                                    @case('celebrate') 🎉 @break
                                    @case('insightful') 💡 @break
                                    @case('curious') 🤔 @break
                                    @default {{ $key }}
                                @endswitch
                            </span>
                            <div>
                                <p class="text-sm font-semibold {{ $count > 0 ? 'text-indigo-700' : 'text-slate-400' }}">{{ $count }}</p>
                                <p class="text-xs text-slate-400">{{ $label }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-slate-400">No reactions yet.</p>
            @endif
        </div>
    @endif

    <div class="eims-card overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <div class="flex items-center gap-2">
                <h3 class="text-base font-semibold text-slate-800">Media</h3>
                <span class="text-xs text-slate-400">{{ $post->media->count() }} file(s)</span>
            </div>
            <a href="{{ route('admin.post-media.create', ['post_id' => $post->id]) }}" class="btn btn-primary text-xs py-1.5">
                Upload Media
            </a>
        </div>

        @if($post->media->isEmpty())
            <div class="px-6 py-8 text-center text-slate-400 text-sm">
                No media uploaded yet.
                <a href="{{ route('admin.post-media.create', ['post_id' => $post->id]) }}" class="text-blue-600 hover:underline ml-1">Upload now</a>
            </div>
        @else
            <div class="p-4 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($post->media as $item)
                    @php $ext = pathinfo($item->file_path, PATHINFO_EXTENSION); $isImage = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']); @endphp
                    <div class="group relative rounded-lg overflow-hidden border border-slate-200 bg-slate-50">
                        @if($isImage)
                            <img src="{{ Storage::url($item->file_path) }}" alt="{{ $item->caption ?? 'Media' }}"
                                class="w-full h-28 object-cover">
                        @else
                            <div class="w-full h-28 flex flex-col items-center justify-center gap-1 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                <span class="text-xs uppercase">{{ $ext }}</span>
                            </div>
                        @endif
                        <div class="px-2 py-1.5 bg-white border-t border-slate-100">
                            <p class="text-xs text-slate-500 truncate">{{ \App\Models\PostMedia::TYPES[$item->type] ?? $item->type }}</p>
                            @if($item->caption)
                                <p class="text-xs text-slate-400 truncate">{{ $item->caption }}</p>
                            @endif
                        </div>
                        {{-- Hover overlay --}}
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <a href="{{ route('admin.post-media.show', $item) }}" class="btn-icon btn-icon-view bg-white/90" title="View">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </a>
                            <form action="{{ route('admin.post-media.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this file?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-delete bg-white/90" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="px-6 py-3 border-t border-slate-100 text-right">
                <a href="{{ route('admin.post-media.index', ['post_id' => $post->id]) }}" class="text-sm text-blue-600 hover:underline">
                    View all media for this post →
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
