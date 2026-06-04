@extends('admin.layouts.app')
@section('title', 'Post Comments')
@section('page-title', 'Post Comments')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Post Comments"
        subtitle="Moderate comments on posts."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Post Comments'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.post-comments.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Comment
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.post-comments.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
            <div>
                <label class="form-label text-xs">Post</label>
                <select name="post_id" class="form-control">
                    <option value="">All Posts</option>
                    @foreach($posts as $post)
                        <option value="{{ $post->id }}" {{ request('post_id') == $post->id ? 'selected' : '' }}>{{ $post->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Author Type</label>
                <select name="commentable_type" class="form-control">
                    <option value="">All Types</option>
                    @foreach($commentableTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('commentable_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Visibility</label>
                <select name="is_hidden" class="form-control">
                    <option value="">All</option>
                    <option value="0" {{ request('is_hidden') === '0' ? 'selected' : '' }}>Visible</option>
                    <option value="1" {{ request('is_hidden') === '1' ? 'selected' : '' }}>Hidden</option>
                </select>
            </div>
            <div class="flex gap-2 md:col-span-3 xl:col-span-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.post-comments.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Post</th>
                        <th>Comment</th>
                        <th>Author</th>
                        <th>Reply</th>
                        <th>Visibility</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($comments as $comment)
                        <tr class="{{ $comment->is_hidden ? 'opacity-60' : '' }}">
                            <td>
                                @if($comment->post)
                                    <a href="{{ route('admin.posts.show', $comment->post) }}" class="font-medium text-blue-600 hover:underline text-sm">
                                        {{ $comment->post->title }}
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="text-sm text-slate-700 max-w-xs">
                                <p class="line-clamp-2">{{ $comment->comment }}</p>
                            </td>
                            <td class="text-sm">
                                <div>{{ $comment->commentable?->name ?? ($comment->commentable?->email ?? '-') }}</div>
                                <div class="text-xs text-slate-400">{{ \App\Models\PostComment::COMMENTABLE_TYPES[$comment->commentable_type] ?? class_basename($comment->commentable_type) }}</div>
                            </td>
                            <td class="text-xs text-slate-500">
                                {{ $comment->parent_id ? 'Reply #' . $comment->parent_id : 'Top-level' }}
                            </td>
                            <td>
                                @if($comment->is_hidden)
                                    <span class="badge badge-red text-xs">Hidden</span>
                                @else
                                    <span class="badge badge-green text-xs">Visible</span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-500">{{ $comment->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.post-comments.show', $comment) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.post-comments.edit', $comment) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.post-comments.toggle-hidden', $comment) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-icon {{ $comment->is_hidden ? 'text-green-600 hover:bg-green-50' : 'text-amber-600 hover:bg-amber-50' }}" title="{{ $comment->is_hidden ? 'Show' : 'Hide' }}">
                                            @if($comment->is_hidden)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/></svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.post-comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Delete this comment?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-400 py-10">No comments found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($comments->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $comments->links() }}</div>
        @endif
    </div>
</div>
@endsection
