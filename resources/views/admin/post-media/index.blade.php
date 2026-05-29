@extends('admin.layouts.app')
@section('title', 'Post Media')
@section('page-title', 'Post Media')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Post Media"
        subtitle="Media files attached to posts."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Post Media'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.post-media.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Upload Media
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.post-media.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
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
                <label class="form-label text-xs">Type</label>
                <select name="type" class="form-control">
                    <option value="">All Types</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2 md:col-span-3 xl:col-span-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.post-media.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Post</th>
                        <th>Type</th>
                        <th>Caption</th>
                        <th>Uploaded</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($media as $item)
                        @php $ext = pathinfo($item->file_path, PATHINFO_EXTENSION); $isImage = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']); @endphp
                        <tr>
                            <td class="w-16">
                                @if($isImage)
                                    <img src="{{ Storage::url($item->file_path) }}" alt="{{ $item->caption }}"
                                        class="h-12 w-16 object-cover rounded-lg border border-slate-200">
                                @else
                                    <div class="h-12 w-16 bg-slate-100 rounded-lg flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                    </div>
                                @endif
                            </td>
                            <td>
                                @if($item->post)
                                    <a href="{{ route('admin.posts.show', $item->post) }}" class="font-medium text-blue-600 hover:underline text-sm">{{ $item->post->title }}</a>
                                @else
                                    <span class="text-slate-400">—</span>
                                @endif
                            </td>
                            <td class="text-sm">{{ $types[$item->type] ?? $item->type }}</td>
                            <td class="text-sm text-slate-600 max-w-xs">
                                <p class="line-clamp-2">{{ $item->caption ?: '—' }}</p>
                            </td>
                            <td class="text-xs text-slate-500">{{ $item->created_at->format('d M Y') }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.post-media.show', $item) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.post-media.edit', $item) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.post-media.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this media file?')">
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
                            <td colspan="6" class="text-center text-slate-400 py-10">No media found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($media->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $media->links() }}</div>
        @endif
    </div>
</div>
@endsection
