@extends('layouts.student')

@section('title', \App\Models\StudentAcademicRecord::LEVELS[$academicRecord->level] ?? $academicRecord->level)

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('student.academic-records.index') }}"
                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-white/10 hover:bg-white/20 transition text-white no-underline shrink-0">
                    <i class="fas fa-arrow-left text-sm"></i>
                </a>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">{{ \App\Models\StudentAcademicRecord::LEVELS[$academicRecord->level] ?? $academicRecord->level }}</h1>
                    <p class="text-white/70 text-sm mt-1">{{ $academicRecord->institution_name }}</p>
                </div>
            </div>
            <a href="{{ route('student.academic-records.edit', $academicRecord) }}"
               class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm no-underline shrink-0">
                <i class="fas fa-pen"></i> Edit Record
            </a>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-4xl mx-auto px-4 space-y-6">

        {{-- Main details card --}}
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-[#ebf8ff] flex items-center justify-center shrink-0">
                        <i class="fas fa-graduation-cap text-[#4299e1]"></i>
                    </div>
                    <span class="font-semibold text-gray-800">Academic Details</span>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold
                    {{ $academicRecord->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $academicRecord->is_verified ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                    {{ $academicRecord->is_verified ? 'Verified' : 'Pending Verification' }}
                </span>
            </div>

            <dl class="divide-y divide-gray-50">
                <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-gray-50">
                    <div class="px-6 py-4">
                        <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Board / University</dt>
                        <dd class="text-sm font-semibold text-gray-800">
                            {{ \App\Models\StudentAcademicRecord::BOARDS[$academicRecord->board] ?? $academicRecord->board ?? '-' }}
                        </dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Faculty / Stream</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $academicRecord->faculty ?? '-' }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Passed Year</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $academicRecord->passed_year ?? '-' }}</dd>
                    </div>
                    <div class="px-6 py-4">
                        <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1">Symbol Number</dt>
                        <dd class="text-sm font-semibold text-gray-800">{{ $academicRecord->symbol_number ?? '-' }}</dd>
                    </div>
                </div>

                <div class="px-6 py-4 flex items-center gap-4">
                    <dt class="text-sm text-gray-500 w-32 shrink-0">Grade</dt>
                    <dd>
                        @if($academicRecord->gpa)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg font-bold text-sm">
                                <i class="fas fa-star text-xs"></i> GPA {{ number_format($academicRecord->gpa, 2) }} / 4.0
                            </span>
                        @elseif($academicRecord->percentage)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 rounded-lg font-bold text-sm">
                                <i class="fas fa-percent text-xs"></i> {{ number_format($academicRecord->percentage, 2) }}%
                            </span>
                        @else
                            <span class="text-gray-400 text-sm">-</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        {{-- Documents card --}}
        @php
            $hasMainDocs = $academicRecord->transcript_file || $academicRecord->character_certificate_file;
            $hasAdditional = $academicRecord->additionalDocuments->count() > 0;
        @endphp
        @if($hasMainDocs || $hasAdditional)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="flex items-center gap-3 px-6 py-4 border-b border-gray-100">
                <div class="w-10 h-10 rounded-lg bg-[#ebf8ff] flex items-center justify-center shrink-0">
                    <i class="fas fa-folder-open text-[#4299e1]"></i>
                </div>
                <span class="font-semibold text-gray-800">Documents</span>
            </div>

            <div class="divide-y divide-gray-50">
                @if($academicRecord->transcript_file)
                <div class="flex items-center justify-between px-6 py-3.5">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-pdf text-red-400 text-base w-5 text-center"></i>
                        <span class="text-sm text-gray-700 font-medium">Transcript</span>
                    </div>
                    <a href="{{ Storage::url($academicRecord->transcript_file) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#4299e1] border border-[#bee3f8] px-3 py-1.5 rounded-lg hover:bg-[#ebf8ff] transition no-underline">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
                @endif

                @if($academicRecord->character_certificate_file)
                <div class="flex items-center justify-between px-6 py-3.5">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-pdf text-red-400 text-base w-5 text-center"></i>
                        <span class="text-sm text-gray-700 font-medium">Character Certificate</span>
                    </div>
                    <a href="{{ Storage::url($academicRecord->character_certificate_file) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#4299e1] border border-[#bee3f8] px-3 py-1.5 rounded-lg hover:bg-[#ebf8ff] transition no-underline">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
                @endif

                @foreach($academicRecord->additionalDocuments as $doc)
                @php
                    $typeLabel = \App\Models\StudentDocument::DOCUMENT_TYPES[$doc->document_type] ?? $doc->document_type;
                    $docName   = $doc->title ? $typeLabel . ' - ' . $doc->title : $typeLabel;
                @endphp
                <div class="flex items-center justify-between px-6 py-3.5">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-alt text-sky-400 text-base w-5 text-center"></i>
                        <span class="text-sm text-gray-700 font-medium">{{ $docName }}</span>
                    </div>
                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#4299e1] border border-[#bee3f8] px-3 py-1.5 rounded-lg hover:bg-[#ebf8ff] transition no-underline">
                        <i class="fas fa-eye"></i> View
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</section>

@endsection
