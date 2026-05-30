@extends('layouts.student')

@section('title', 'Academic Record')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.academic-records.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">{{ \App\Models\StudentAcademicRecord::LEVELS[$academicRecord->level] ?? $academicRecord->level }}</h1>
                <p class="text-white/70 text-sm mt-1">{{ $academicRecord->institution_name }}</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <span class="text-sm font-semibold text-gray-700">{{ $academicRecord->institution_name }}</span>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $academicRecord->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $academicRecord->is_verified ? 'Verified' : 'Pending Verification' }}
                    </span>
                </div>
                <dl class="divide-y divide-gray-50">
                    @foreach([
                        ['Board', \App\Models\StudentAcademicRecord::BOARDS[$academicRecord->board] ?? $academicRecord->board],
                        ['Faculty', $academicRecord->faculty],
                        ['Passed Year', $academicRecord->passed_year],
                        ['Symbol Number', $academicRecord->symbol_number],
                        ['GPA', $academicRecord->gpa],
                        ['Percentage', $academicRecord->percentage ? $academicRecord->percentage . '%' : null],
                    ] as [$label, $value])
                    @if($value)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-40 shrink-0">{{ $label }}</dt>
                        <dd class="text-sm font-semibold text-gray-700">{{ $value }}</dd>
                    </div>
                    @endif
                    @endforeach
                </dl>
                @if($academicRecord->transcript_file || $academicRecord->character_certificate_file)
                <div class="flex gap-4 px-6 py-4 bg-gray-50 border-t border-gray-100">
                    @if($academicRecord->transcript_file)
                        <a href="{{ Storage::url($academicRecord->transcript_file) }}" target="_blank"
                           class="flex items-center gap-1.5 text-sm text-[#4299e1] font-medium hover:underline no-underline">
                            <i class="fas fa-file-pdf"></i> Transcript
                        </a>
                    @endif
                    @if($academicRecord->character_certificate_file)
                        <a href="{{ Storage::url($academicRecord->character_certificate_file) }}" target="_blank"
                           class="flex items-center gap-1.5 text-sm text-[#4299e1] font-medium hover:underline no-underline">
                            <i class="fas fa-file-pdf"></i> Character Certificate
                        </a>
                    @endif
                </div>
                @endif
                <div class="flex justify-end px-6 py-4 border-t border-gray-100">
                    <a href="{{ route('student.academic-records.edit', $academicRecord) }}"
                       class="inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
                        <i class="fas fa-pen"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
