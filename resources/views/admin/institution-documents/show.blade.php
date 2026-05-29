@extends('admin.layouts.app')
@section('title', $document->title)
@section('page-title', 'Document Details')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        :title="$document->title"
        subtitle="Institution document details"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Institution Documents','route'=>'admin.institution-documents.index'],
            ['label'=>$document->title],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-documents.edit', $document) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.institution-documents.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left: Main Info --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Details card --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">Document Information</h3>
                </div>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Institution</dt>
                        <dd class="mt-1">
                            <a href="{{ route('admin.institutions.show', $document->institution) }}"
                               class="text-sm font-medium text-blue-600 hover:underline">
                                {{ $document->institution->name }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Document Type</dt>
                        <dd class="mt-1"><span class="badge badge-blue">{{ $documentTypes[$document->document_type] ?? $document->document_type }}</span></dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Title</dt>
                        <dd class="mt-1 text-sm text-slate-700">{{ $document->title }}</dd>
                    </div>
                    @if($document->remarks)
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Remarks</dt>
                        <dd class="mt-1 text-sm text-slate-600 whitespace-pre-line">{{ $document->remarks }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- File preview / download --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-violet-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">File</h3>
                </div>

                <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-lg border border-slate-200">
                    @php $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION)); @endphp
                    <div class="w-12 h-12 bg-white rounded-lg border border-slate-200 flex items-center justify-center shrink-0">
                        @if(in_array($ext, ['jpg','jpeg','png','webp']))
                            <img src="{{ Storage::url($document->file_path) }}" alt=""
                                 class="w-full h-full object-cover rounded-lg">
                        @else
                            <span class="text-xs font-bold text-slate-500 uppercase">{{ $ext }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ basename($document->file_path) }}</p>
                    </div>
                    <a href="{{ Storage::url($document->file_path) }}" target="_blank" rel="noopener"
                       class="btn btn-secondary shrink-0 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                        Download
                    </a>
                </div>
            </div>

        </div>

        {{-- Right: Status + Meta + Danger --}}
        <div class="space-y-5">

            {{-- Status card --}}
            <div class="eims-card p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4 pb-3 border-b border-slate-100">Status</h3>
                <div class="mb-4">
                    @if($document->status === 'active')
                        <span class="badge badge-green">Active</span>
                    @elseif($document->status === 'expired')
                        <span class="badge badge-red">Expired</span>
                    @else
                        <span class="badge badge-yellow">Inactive</span>
                    @endif
                </div>
                <form action="{{ route('admin.institution-documents.update-status', $document) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control text-sm">
                        @foreach($statuses as $val => $label)
                            <option value="{{ $val }}" {{ $document->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-full text-sm">Update Status</button>
                </form>
            </div>

            {{-- Meta --}}
            <div class="eims-card p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4 pb-3 border-b border-slate-100">Meta</h3>
                <dl class="space-y-2">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500">Uploaded</dt>
                        <dd class="text-sm text-slate-600">{{ $document->created_at->format('d M Y') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500">Updated</dt>
                        <dd class="text-sm text-slate-600">{{ $document->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Danger Zone --}}
            <div class="eims-card p-6 border border-red-100">
                <h3 class="text-sm font-semibold text-red-700 mb-3">Danger Zone</h3>
                <form action="{{ route('admin.institution-documents.destroy', $document) }}" method="POST"
                      onsubmit="return confirm('Delete this document? The file will also be permanently removed.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete Document
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
