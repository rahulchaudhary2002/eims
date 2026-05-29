@extends('admin.layouts.app')
@section('title', ($levels[$studentAcademicRecord->level] ?? $studentAcademicRecord->level) . ' — ' . $studentAcademicRecord->student->name)
@section('page-title', 'Academic Record')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="Academic Record"
        :subtitle="($levels[$studentAcademicRecord->level] ?? $studentAcademicRecord->level) . ' — ' . $studentAcademicRecord->student->name"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Academic Records','route' => 'admin.student-academic-records.index'],
            ['label'=>$studentAcademicRecord->student->name],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.student-academic-records.edit', $studentAcademicRecord) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                Edit
            </a>
            @if(!$studentAcademicRecord->is_verified)
            <form action="{{ route('admin.student-academic-records.verify', $studentAcademicRecord) }}"
                method="POST" class="inline">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-secondary" style="color:#059669;border-color:#6ee7b7;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Verify
                </button>
            </form>
            @endif
            <a href="{{ route('admin.students.show', $studentAcademicRecord->student) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                View Student
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left --}}
        <div class="space-y-5">

            {{-- Student card --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Student</h3>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-base shrink-0">
                        {{ mb_strtoupper(mb_substr($studentAcademicRecord->student->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800">{{ $studentAcademicRecord->student->name }}</p>
                        <p class="text-xs text-slate-500">{{ $studentAcademicRecord->student->email }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.students.show', $studentAcademicRecord->student) }}"
                    class="block text-center text-sm text-blue-600 hover:text-blue-800 hover:underline pt-1">
                    View full student record →
                </a>
            </div>

            {{-- Status card --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Verification Status</h3>
                @if($studentAcademicRecord->is_verified)
                    <div class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="badge badge-green">Verified</span>
                    </div>
                @else
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="badge badge-yellow">Not Yet Verified</span>
                        <form action="{{ route('admin.student-academic-records.verify', $studentAcademicRecord) }}"
                            method="POST" class="inline">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-primary text-xs px-3 py-1">Mark as Verified</button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Timestamps --}}
            <div class="eims-card p-5 text-sm space-y-3">
                <div>
                    <p class="text-slate-400 text-xs mb-1">Created</p>
                    <p class="text-slate-700">{{ $studentAcademicRecord->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs mb-1">Last Updated</p>
                    <p class="text-slate-700">{{ $studentAcademicRecord->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Right --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Academic Info --}}
            <div class="eims-card p-6 space-y-5">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">Academic Information</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Education Level</dt>
                        <dd>
                            <span class="badge badge-blue">
                                {{ $levels[$studentAcademicRecord->level] ?? $studentAcademicRecord->level }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution Name</dt>
                        <dd class="text-slate-800 font-medium">{{ $studentAcademicRecord->institution_name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Board / University</dt>
                        <dd class="text-slate-800">{{ $boards[$studentAcademicRecord->board] ?? ($studentAcademicRecord->board ?? '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Faculty / Stream</dt>
                        <dd class="text-slate-800">{{ $studentAcademicRecord->faculty ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Passed Year</dt>
                        <dd class="text-slate-800 font-medium">{{ $studentAcademicRecord->passed_year ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Symbol / Roll Number</dt>
                        <dd class="text-slate-800">{{ $studentAcademicRecord->symbol_number ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">GPA</dt>
                        <dd class="text-slate-800 font-semibold text-base">
                            {{ $studentAcademicRecord->gpa !== null ? number_format($studentAcademicRecord->gpa, 2) : '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Percentage</dt>
                        <dd class="text-slate-800 font-semibold text-base">
                            {{ $studentAcademicRecord->percentage !== null ? number_format($studentAcademicRecord->percentage, 2) . '%' : '—' }}
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Documents --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">Documents</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-slate-400 text-xs mb-2">Transcript / Marksheet</p>
                        @if($studentAcademicRecord->transcript_file)
                            <a href="{{ Storage::url($studentAcademicRecord->transcript_file) }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-blue-600 hover:bg-blue-50 hover:border-blue-300 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                View Transcript
                            </a>
                        @else
                            <span class="text-slate-400 italic text-xs">Not uploaded</span>
                        @endif
                    </div>

                    <div>
                        <p class="text-slate-400 text-xs mb-2">Character Certificate</p>
                        @if($studentAcademicRecord->character_certificate_file)
                            <a href="{{ Storage::url($studentAcademicRecord->character_certificate_file) }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg text-blue-600 hover:bg-blue-50 hover:border-blue-300 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                                View Certificate
                            </a>
                        @else
                            <span class="text-slate-400 italic text-xs">Not uploaded</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="eims-card border border-red-100 p-5 flex items-center justify-between gap-4">
                <div>
                    <p class="font-semibold text-red-600 text-sm">Delete Record</p>
                    <p class="text-xs text-slate-500 mt-0.5">Permanently removes this academic record and associated uploaded files.</p>
                </div>
                <form action="{{ route('admin.student-academic-records.destroy', $studentAcademicRecord) }}"
                    method="POST"
                    onsubmit="return confirm('Permanently delete this academic record?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger text-sm">Delete</button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
