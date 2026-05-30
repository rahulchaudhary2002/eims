@extends('layouts.student')

@section('title', 'Academic Records')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Academic Records</h1>
                <p class="text-white/70 text-sm mt-1">Your educational history and qualifications</p>
            </div>
            <a href="{{ route('student.academic-records.create') }}"
               class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm no-underline shrink-0">
                <i class="fas fa-plus"></i> Add Record
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

        @forelse($records as $record)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="flex items-start justify-between px-6 py-4">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#ebf8ff] flex items-center justify-center shrink-0">
                        <i class="fas fa-graduation-cap text-[#4299e1]"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="text-sm font-bold text-gray-800">{{ \App\Models\StudentAcademicRecord::LEVELS[$record->level] ?? $record->level }}</h3>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full {{ $record->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ $record->is_verified ? 'Verified' : 'Pending' }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $record->institution_name }}</p>
                        <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1.5 text-xs text-gray-400">
                            @if($record->board)<span>{{ $record->board }}</span>@endif
                            @if($record->faculty)<span>{{ $record->faculty }}</span>@endif
                            @if($record->passed_year)<span>{{ $record->passed_year }}</span>@endif
                            @if($record->gpa)<span>GPA: {{ $record->gpa }}</span>@endif
                            @if($record->percentage)<span>{{ $record->percentage }}%</span>@endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('student.academic-records.show', $record) }}"
                       class="text-xs text-gray-500 hover:text-[#4299e1] px-3 py-1.5 border border-gray-200 rounded-lg hover:border-[#4299e1]/30 transition no-underline">View</a>
                    <a href="{{ route('student.academic-records.edit', $record) }}"
                       class="text-xs text-[#4299e1] px-3 py-1.5 border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">Edit</a>
                    <form method="POST" action="{{ route('student.academic-records.destroy', $record) }}" onsubmit="return confirm('Delete this record?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-500 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">Delete</button>
                    </form>
                </div>
            </div>
            @if($record->transcript_file || $record->character_certificate_file)
            <div class="flex gap-4 px-6 py-3 border-t border-gray-50 bg-gray-50/50">
                @if($record->transcript_file)
                    <a href="{{ Storage::url($record->transcript_file) }}" target="_blank"
                       class="flex items-center gap-1.5 text-xs text-[#4299e1] hover:underline no-underline">
                        <i class="fas fa-file-pdf"></i> Transcript
                    </a>
                @endif
                @if($record->character_certificate_file)
                    <a href="{{ Storage::url($record->character_certificate_file) }}" target="_blank"
                       class="flex items-center gap-1.5 text-xs text-[#4299e1] hover:underline no-underline">
                        <i class="fas fa-file-pdf"></i> Character Certificate
                    </a>
                @endif
            </div>
            @endif
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-graduation-cap text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No academic records yet</p>
            <p class="text-gray-400 text-sm mt-1">Add your educational qualifications</p>
            <a href="{{ route('student.academic-records.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
                Add Record
            </a>
        </div>
        @endforelse

    </div>
</section>

@endsection
