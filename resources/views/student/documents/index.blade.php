@extends('layouts.student')

@section('title', 'My Documents')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">My Documents</h1>
                <p class="text-white/70 text-sm mt-1">Manage your identity and supporting documents</p>
            </div>
            <a href="{{ route('student.documents.create') }}"
               class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm no-underline shrink-0">
                <i class="fas fa-upload"></i> Upload Document
            </a>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-4">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        @forelse($documents as $doc)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 flex items-center justify-between px-5 py-4">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-file-alt text-sky-500"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-bold text-gray-700 truncate">{{ $doc->title }}</p>
                    <p class="text-xs text-gray-400">{{ \App\Models\StudentDocument::DOCUMENT_TYPES[$doc->document_type] ?? $doc->document_type }} · {{ $doc->created_at->format('M d, Y') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0 ml-3">
                @if($doc->file_path)
                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                       class="text-xs text-[#4299e1] px-3 py-1.5 border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">View</a>
                @endif
                <a href="{{ route('student.documents.edit', $doc) }}"
                   class="text-xs text-gray-600 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition no-underline">Edit</a>
                <form method="POST" action="{{ route('student.documents.destroy', $doc) }}" onsubmit="return confirm('Delete this document?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">Delete</button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-file-alt text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No documents yet</p>
            <p class="text-gray-400 text-sm mt-1">Upload your identity documents and certificates</p>
            <a href="{{ route('student.documents.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
                Upload Document
            </a>
        </div>
        @endforelse

    </div>
</section>

@endsection
