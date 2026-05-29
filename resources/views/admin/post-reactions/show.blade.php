@extends('admin.layouts.app')
@section('title', 'Reaction #' . $postReaction->id)
@section('page-title', 'Post Reaction Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Reaction #{{ $postReaction->id }}"
        subtitle="{{ \App\Models\PostReaction::REACTIONS[$postReaction->reaction] ?? $postReaction->reaction }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Post Reactions', 'route' => 'admin.post-reactions.index'],
            ['label' => 'Reaction #' . $postReaction->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.post-reactions.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Details --}}
        <div class="lg:col-span-2">
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Reaction Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Post</dt>
                        <dd class="mt-1">
                            @if($postReaction->post)
                                <a href="{{ route('admin.posts.show', $postReaction->post) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $postReaction->post->title }}
                                </a>
                                @if($postReaction->post->institution)
                                    <div class="text-xs text-slate-400 mt-0.5">{{ $postReaction->post->institution->name }}</div>
                                @endif
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Reaction</dt>
                        <dd class="mt-1 text-2xl">
                            @switch($postReaction->reaction)
                                @case('like') 👍 @break
                                @case('love') ❤️ @break
                                @case('celebrate') 🎉 @break
                                @case('insightful') 💡 @break
                                @case('curious') 🤔 @break
                                @default {{ $postReaction->reaction }}
                            @endswitch
                            <span class="text-sm text-slate-700 ml-1">{{ \App\Models\PostReaction::REACTIONS[$postReaction->reaction] ?? $postReaction->reaction }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Reacted By</dt>
                        <dd class="mt-1 font-medium text-slate-800">
                            {{ $postReaction->reactable?->name ?? ($postReaction->reactable?->email ?? '—') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Reactor Type</dt>
                        <dd class="mt-1 text-slate-700">
                            {{ \App\Models\PostReaction::REACTABLE_TYPES[$postReaction->reactable_type] ?? class_basename($postReaction->reactable_type) }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Reacted At</dt>
                        <dd class="mt-1 text-slate-700">{{ $postReaction->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    @if($postReaction->post)
                        <a href="{{ route('admin.posts.show', $postReaction->post) }}" class="btn btn-secondary w-full text-sm">View Post</a>
                    @endif
                    <form action="{{ route('admin.post-reactions.destroy', $postReaction) }}" method="POST" onsubmit="return confirm('Remove this reaction? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Remove Reaction
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
