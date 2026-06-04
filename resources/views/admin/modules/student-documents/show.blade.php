@extends('admin.layouts.app')
@section('title', $studentDocument->title . ' - ' . $studentDocument->student->name)
@section('page-title', 'Student Document')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="Student Document"
        :subtitle="$studentDocument->title . ' - ' . $studentDocument->student->name"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Student Documents','route' => 'admin.student-documents.index'],
            ['label'=>$studentDocument->title],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.student-documents.edit', $studentDocument) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ Storage::url($studentDocument->file_path) }}" target="_blank" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Download
            </a>
            <a href="{{ route('admin.students.show', $studentDocument->student) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                View Student
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left sidebar --}}
        <div class="space-y-5">

            {{-- Student card --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Student</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-base shrink-0">
                        {{ mb_strtoupper(mb_substr($studentDocument->student->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $studentDocument->student->name }}</p>
                        <p class="text-xs text-slate-500">{{ $studentDocument->student->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.students.show', $studentDocument->student) }}"
                    class="block text-center text-sm text-blue-600 hover:text-blue-800 hover:underline pt-1">
                    View full student record →
                </a>
            </div>

            {{-- Status card --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Status</h3>
                <div>
                    @if($studentDocument->status === 'active')
                        <span class="badge badge-green">Active</span>
                    @elseif($studentDocument->status === 'expired')
                        <span class="badge badge-red">Expired</span>
                    @else
                        <span class="badge badge-yellow">Inactive</span>
                    @endif
                </div>
                {{-- Quick status update --}}
                <form action="{{ route('admin.student-documents.update-status', $studentDocument) }}" method="POST" class="space-y-2">
                    @csrf @method('PATCH')
                    <label class="form-label text-xs">Change Status</label>
                    <div class="flex gap-2">
                        <select name="status" class="form-control text-sm">
                            @foreach($statuses as $val => $label)
                                <option value="{{ $val }}" {{ $studentDocument->status === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary text-xs px-3 whitespace-nowrap">Update</button>
                    </div>
                </form>
            </div>

            {{-- Timestamps --}}
            <div class="eims-card p-5 text-sm space-y-3">
                <div>
                    <p class="text-slate-400 text-xs mb-1">Uploaded</p>
                    <p class="text-slate-700">{{ $studentDocument->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs mb-1">Last Updated</p>
                    <p class="text-slate-700">{{ $studentDocument->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

        </div>

        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Document details --}}
            <div class="eims-card p-6 space-y-5">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">Document Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Document Type</dt>
                        <dd>
                            <span class="badge badge-blue">
                                {{ $documentTypes[$studentDocument->document_type] ?? $studentDocument->document_type }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Title</dt>
                        <dd class="text-slate-800 font-medium">{{ $studentDocument->title }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-slate-400 text-xs mb-1">File</dt>
                        <dd>
                            <a href="{{ Storage::url($studentDocument->file_path) }}" target="_blank"
                               class="inline-flex items-center gap-2 text-blue-600 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                {{ basename($studentDocument->file_path) }}
                            </a>
                        </dd>
                    </div>
                    @if($studentDocument->remarks)
                    <div class="sm:col-span-2">
                        <dt class="text-slate-400 text-xs mb-1">Remarks</dt>
                        <dd class="text-slate-700 whitespace-pre-wrap">{{ $studentDocument->remarks }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Danger zone --}}
            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <p class="text-sm text-slate-600 mb-4">Permanently delete this document and its file. This action cannot be undone.</p>
                <form action="{{ route('admin.student-documents.destroy', $studentDocument) }}" method="POST"
                      onsubmit="return confirm('Delete this document? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete Document
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
