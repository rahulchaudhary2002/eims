@extends('admin.layouts.app')
@section('title', 'Media #' . $postMedium->id)
@section('page-title', 'Post Media Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Media #{{ $postMedium->id }}"
        subtitle="{{ \App\Models\PostMedia::TYPES[$postMedium->type] ?? $postMedium->type }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Post Media', 'route' => 'admin.post-media.index'],
            ['label' => 'Media #' . $postMedium->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.post-media.edit', $postMedium) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.post-media.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Preview + Details --}}
        <div class="lg:col-span-2 space-y-5">
            @php $ext = pathinfo($postMedium->file_path, PATHINFO_EXTENSION); $isImage = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']); @endphp

            @if($isImage)
                <div class="eims-card overflow-hidden">
                    <img src="{{ Storage::url($postMedium->file_path) }}"
                        alt="{{ $postMedium->caption ?? 'Media' }}"
                        class="w-full max-h-96 object-contain bg-slate-50">
                </div>
            @endif

            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Media Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Post</dt>
                        <dd class="mt-1">
                            @if($postMedium->post)
                                <a href="{{ route('admin.posts.show', $postMedium->post) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $postMedium->post->title }}
                                </a>
                            @else <span class="text-slate-400">-</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Type</dt>
                        <dd class="mt-1 text-slate-800">{{ \App\Models\PostMedia::TYPES[$postMedium->type] ?? $postMedium->type }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">File</dt>
                        <dd class="mt-1">
                            <a href="{{ Storage::url($postMedium->file_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm">
                                {{ basename($postMedium->file_path) }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Caption</dt>
                        <dd class="mt-1 text-slate-700">{{ $postMedium->caption ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Institution</dt>
                        <dd class="mt-1 text-slate-700">{{ $postMedium->post?->institution?->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Uploaded At</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $postMedium->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.post-media.edit', $postMedium) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Media
                    </a>
                    @if($postMedium->post)
                        <a href="{{ route('admin.posts.show', $postMedium->post) }}" class="btn btn-secondary w-full text-sm">
                            View Post
                        </a>
                    @endif
                    <a href="{{ Storage::url($postMedium->file_path) }}" target="_blank" class="btn btn-secondary w-full text-sm">
                        Download File
                    </a>
                    <form action="{{ route('admin.post-media.destroy', $postMedium) }}" method="POST" onsubmit="return confirm('Delete this media file? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Media
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
