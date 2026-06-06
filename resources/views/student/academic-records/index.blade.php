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

        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            @if($records->count())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            <th class="px-5 py-3.5 text-left">Level</th>
                            <th class="px-5 py-3.5 text-left">Institution</th>
                            <th class="px-5 py-3.5 text-left">Board / Faculty</th>
                            <th class="px-5 py-3.5 text-left">Year</th>
                            <th class="px-5 py-3.5 text-left">Grade</th>
                            <th class="px-5 py-3.5 text-left">Documents</th>
                            <th class="px-5 py-3.5 text-left">Status</th>
                            <th class="px-5 py-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($records as $record)
                        <tr class="hover:bg-gray-50/60 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-[#ebf8ff] flex items-center justify-center shrink-0">
                                        <i class="fas fa-graduation-cap text-[#4299e1] text-sm"></i>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ \App\Models\StudentAcademicRecord::LEVELS[$record->level] ?? $record->level }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-700 max-w-[200px]">
                                {{ $record->institution_name }}
                            </td>
                            <td class="px-5 py-4 text-gray-500">
                                @if($record->board)<div>{{ \App\Models\StudentAcademicRecord::BOARDS[$record->board] ?? $record->board }}</div>@endif
                                @if($record->faculty)<div class="text-gray-400 text-xs mt-0.5">{{ $record->faculty }}</div>@endif
                                @if(!$record->board && !$record->faculty)<span class="text-gray-300">—</span>@endif
                            </td>
                            <td class="px-5 py-4 text-gray-700 font-medium">
                                {{ $record->passed_year ?? '—' }}
                            </td>
                            <td class="px-5 py-4">
                                @if($record->gpa)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-50 text-blue-700 rounded-lg font-semibold text-sm">
                                        GPA {{ number_format($record->gpa, 2) }}
                                    </span>
                                @elseif($record->percentage)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-purple-50 text-purple-700 rounded-lg font-semibold text-sm">
                                        {{ number_format($record->percentage, 2) }}%
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex flex-col gap-1">
                                    @if($record->transcript_file)
                                        <a href="{{ Storage::url($record->transcript_file) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 text-xs text-[#4299e1] hover:underline no-underline">
                                            <i class="fas fa-file-pdf text-red-400"></i> Transcript
                                        </a>
                                    @endif
                                    @if($record->character_certificate_file)
                                        <a href="{{ Storage::url($record->character_certificate_file) }}" target="_blank"
                                           class="inline-flex items-center gap-1.5 text-xs text-[#4299e1] hover:underline no-underline">
                                            <i class="fas fa-file-pdf text-red-400"></i> Certificate
                                        </a>
                                    @endif
                                    @if(!$record->transcript_file && !$record->character_certificate_file)
                                        <span class="text-gray-300 text-xs">None</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $record->is_verified ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $record->is_verified ? 'bg-green-500' : 'bg-yellow-500' }}"></span>
                                    {{ $record->is_verified ? 'Verified' : 'Pending' }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('student.academic-records.edit', $record) }}"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-[#4299e1] border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">
                                        <i class="fas fa-pen"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('student.academic-records.destroy', $record) }}" onsubmit="return confirm('Delete this record?')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-red-500 border border-red-200 rounded-lg hover:bg-red-50 transition">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="px-6 py-16 text-center">
                <i class="fas fa-graduation-cap text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-500 font-semibold">No academic records yet</p>
                <p class="text-gray-400 text-sm mt-1">Add your educational qualifications</p>
                <a href="{{ route('student.academic-records.create') }}"
                   class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
                    Add Record
                </a>
            </div>
            @endif
        </div>

    </div>
</section>

@endsection
