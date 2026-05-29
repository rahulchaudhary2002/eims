@extends('admin.layouts.app')
@section('title', 'Post Reactions')
@section('page-title', 'Post Reactions')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Post Reactions"
        subtitle="Reactions left by students and users on posts."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Post Reactions'],
        ]">
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.post-reactions.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
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
                <label class="form-label text-xs">Reaction</label>
                <select name="reaction" class="form-control">
                    <option value="">All Reactions</option>
                    @foreach($reactionTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('reaction') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Reactable Type</label>
                <select name="reactable_type" class="form-control">
                    <option value="">All Types</option>
                    @foreach($reactableTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('reactable_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 md:col-span-3 xl:col-span-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.post-reactions.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Post</th>
                        <th>Reaction</th>
                        <th>Reacted By</th>
                        <th>Type</th>
                        <th>Reacted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reactions as $reaction)
                        <tr>
                            <td>
                                @if($reaction->post)
                                    <a href="{{ route('admin.posts.show', $reaction->post) }}" class="font-medium text-blue-600 hover:underline text-sm">
                                        {{ $reaction->post->title }}
                                    </a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td>
                                <span class="text-lg" title="{{ \App\Models\PostReaction::REACTIONS[$reaction->reaction] ?? $reaction->reaction }}">
                                    @switch($reaction->reaction)
                                        @case('like') 👍 @break
                                        @case('love') ❤️ @break
                                        @case('celebrate') 🎉 @break
                                        @case('insightful') 💡 @break
                                        @case('curious') 🤔 @break
                                        @default {{ $reaction->reaction }}
                                    @endswitch
                                </span>
                                <span class="text-sm text-slate-600 ml-1">{{ \App\Models\PostReaction::REACTIONS[$reaction->reaction] ?? $reaction->reaction }}</span>
                            </td>
                            <td class="text-sm">
                                @if($reaction->reactable)
                                    {{ $reaction->reactable->name ?? ($reaction->reactable->email ?? '—') }}
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="text-sm text-slate-500">
                                {{ \App\Models\PostReaction::REACTABLE_TYPES[$reaction->reactable_type] ?? class_basename($reaction->reactable_type) }}
                            </td>
                            <td class="text-xs text-slate-500">{{ $reaction->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.post-reactions.show', $reaction) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.post-reactions.destroy', $reaction) }}" method="POST" onsubmit="return confirm('Remove this reaction?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-delete" title="Remove">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-slate-400 py-10">No reactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($reactions->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $reactions->links() }}</div>
        @endif
    </div>
</div>
@endsection
