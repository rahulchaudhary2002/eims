@extends('admin.layouts.app')
@section('title', 'Student Documents')
@section('page-title', 'Student Documents')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Student Documents" subtitle="Manage uploaded student documents"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Student Documents']]">
        <x-slot:actions>
            <a href="{{ route('admin.student-documents.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Upload Document
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    {{-- Filters --}}
    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.student-documents.index') }}" class="flex flex-wrap gap-3 items-end">

            <div class="w-56">
                <label class="form-label text-xs">Student</label>
                <select name="student_id" class="form-control">
                    <option value="">All Students</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-48">
                <label class="form-label text-xs">Document Type</label>
                <select name="document_type" class="form-control">
                    <option value="">All Types</option>
                    @foreach($documentTypes as $val => $label)
                        <option value="{{ $val }}" {{ request('document_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-36">
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $val => $label)
                        <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.student-documents.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Type</th>
                        <th>Title</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Uploaded</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($documents as $doc)
                    <tr>
                        <td class="text-slate-400 text-sm">{{ $documents->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="font-medium text-slate-800">{{ $doc->student->name }}</div>
                            <div class="text-xs text-slate-400">{{ $doc->student->email }}</div>
                        </td>
                        <td>
                            <span class="badge badge-blue">
                                {{ $documentTypes[$doc->document_type] ?? $doc->document_type }}
                            </span>
                        </td>
                        <td class="text-sm font-medium text-slate-800">{{ $doc->title }}</td>
                        <td>
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                               class="inline-flex items-center gap-1 text-sm text-blue-600 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                Download
                            </a>
                        </td>
                        <td>
                            @if($doc->status === 'active')
                                <span class="badge badge-green">Active</span>
                            @elseif($doc->status === 'expired')
                                <span class="badge badge-red">Expired</span>
                            @else
                                <span class="badge badge-yellow">Inactive</span>
                            @endif
                        </td>
                        <td class="text-sm text-slate-500">{{ $doc->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.student-documents.show', $doc) }}" class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.student-documents.edit', $doc) }}" class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                </a>
                                <form action="{{ route('admin.student-documents.destroy', $doc) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this document?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mx-auto mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                            No documents found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($documents->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $documents->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
