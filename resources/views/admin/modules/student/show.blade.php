@extends('admin.layouts.app')
@section('title', $student->name)
@section('page-title', 'Student Details')

@section('content')
<div class="space-y-5">

    <x-admin.page-header :title="$student->name" subtitle="Student account details"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Students','route'=>'admin.students.index'],['label'=>$student->name]]">
        <x-slot:actions>
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    {{-- Avatar Banner --}}
    <div class="eims-card p-6">
        <div class="flex items-center gap-5">
            @if($student->avatar)
            <img src="{{ Storage::url($student->avatar) }}" alt="Avatar"
                 class="w-20 h-20 rounded-full object-cover border-2 border-slate-200 shrink-0">
            @else
            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
            </div>
            @endif
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl font-bold text-slate-800">{{ $student->name }}</h2>
                    @if($student->is_active)
                    <span class="badge badge-green">Active</span>
                    @else
                    <span class="badge badge-red">Inactive</span>
                    @endif
                    @if($student->gender)
                    <span class="badge badge-blue capitalize">{{ $student->gender }}</span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 mt-0.5">{{ $student->email }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left: Info --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">Account Information</h3>
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Name</dt>
                        <dd class="mt-1 text-slate-800 font-medium">{{ $student->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Email</dt>
                        <dd class="mt-1">
                            <a href="mailto:{{ $student->email }}" class="text-blue-600 hover:underline">{{ $student->email }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Phone</dt>
                        <dd class="mt-1 text-slate-700">{{ $student->phone ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Gender</dt>
                        <dd class="mt-1">
                            @if($student->gender)
                                <span class="badge badge-blue capitalize">{{ $student->gender }}</span>
                            @else
                                <span class="text-slate-400 text-sm">-</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Date of Birth</dt>
                        <dd class="mt-1 text-slate-700 text-sm">
                            {{ $student->date_of_birth ? $student->date_of_birth->format('d M Y') : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Email Verified</dt>
                        <dd class="mt-1">
                            @if($student->email_verified_at)
                            <span class="text-emerald-600 font-medium text-sm">{{ $student->email_verified_at->format('d M Y, h:i A') }}</span>
                            @else
                            <span class="badge badge-yellow">Not Verified</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Registered</dt>
                        <dd class="mt-1 text-slate-600 text-sm">{{ $student->created_at->format('d M Y, h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Updated</dt>
                        <dd class="mt-1 text-slate-600 text-sm">{{ $student->updated_at->format('d M Y, h:i A') }}</dd>
                    </div>
                </dl>
            </div>

        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-5">

            {{-- Quick Status Update --}}
            <div class="eims-card p-5">
                <h4 class="text-sm font-semibold text-slate-700 mb-4">Quick Status Update</h4>
                <form method="POST" action="{{ route('admin.students.update-status', $student) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label text-xs">Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $student->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$student->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Update Status</button>
                </form>
            </div>

            {{-- Settings Summary --}}
            <div class="eims-card p-5">
                <h4 class="text-sm font-semibold text-slate-700 mb-4">Settings</h4>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-slate-600">Active</span>
                    @if($student->is_active)
                    <span class="badge badge-green">Yes</span>
                    @else
                    <span class="badge badge-red">No</span>
                    @endif
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="eims-card p-5 border border-red-100">
                <h4 class="text-sm font-semibold text-red-600 mb-3">Danger Zone</h4>
                <p class="text-xs text-slate-500 mb-4">This will soft-delete the student. The record can be restored later.</p>
                <form method="POST" action="{{ route('admin.students.destroy', $student) }}"
                      onsubmit="return confirm('Delete {{ addslashes($student->name) }}? This action can be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn w-full border border-red-300 text-red-600 hover:bg-red-50 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete Student
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- Student Profile Section --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-slate-800">Student Profile</h3>
                <p class="text-xs text-slate-400 mt-0.5">Guardian, location, budget &amp; preferences</p>
            </div>
            @if($student->profile)
                <div class="flex gap-2">
                    <a href="{{ route('admin.student-profiles.show', $student->profile) }}" class="btn btn-secondary text-sm">
                        View Full Profile
                    </a>
                    <a href="{{ route('admin.student-profiles.edit', $student->profile) }}" class="btn btn-primary text-sm">
                        Edit Profile
                    </a>
                </div>
            @else
                <a href="{{ route('admin.student-profiles.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Profile
                </a>
            @endif
        </div>

        @if($student->profile)
        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 text-sm">

            {{-- Guardian --}}
            <div class="space-y-2">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Guardian</p>
                @if($student->profile->guardian_name || $student->profile->guardian_phone)
                    <p class="text-slate-800 font-medium">{{ $student->profile->guardian_name ?? '-' }}</p>
                    <p class="text-slate-500">{{ $student->profile->guardian_phone ?? '' }}</p>
                @else
                    <p class="text-slate-400 italic">Not specified</p>
                @endif
            </div>

            {{-- Location --}}
            <div class="space-y-2">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Location</p>
                @if($student->profile->province || $student->profile->district || $student->profile->city)
                    <p class="text-slate-800">
                        {{ implode(', ', array_filter([
                            $student->profile->city,
                            $student->profile->district,
                            $student->profile->province,
                        ])) }}
                    </p>
                    @if($student->profile->preferred_location)
                        <p class="text-slate-500 text-xs">Preferred: {{ $student->profile->preferred_location }}</p>
                    @endif
                @else
                    <p class="text-slate-400 italic">Not specified</p>
                @endif
            </div>

            {{-- Budget --}}
            <div class="space-y-2">
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Budget (NPR)</p>
                @if($student->profile->budget_min !== null || $student->profile->budget_max !== null)
                    <p class="text-slate-800 font-medium">
                        {{ number_format($student->profile->budget_min ?? 0) }}
                        –
                        {{ $student->profile->budget_max ? number_format($student->profile->budget_max) : '∞' }}
                    </p>
                @else
                    <p class="text-slate-400 italic">Not specified</p>
                @endif
            </div>

            {{-- Interests & Faculties --}}
            <div class="space-y-3">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Career Interests</p>
                    @php $ci = $student->profile->career_interests ?? [] @endphp
                    @if(count($ci))
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($ci, 0, 4) as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-xs">{{ $tag }}</span>
                            @endforeach
                            @if(count($ci) > 4)<span class="text-xs text-slate-400">+{{ count($ci) - 4 }} more</span>@endif
                        </div>
                    @else
                        <p class="text-slate-400 italic text-xs">None</p>
                    @endif
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1">Preferred Faculties</p>
                    @php $pf = $student->profile->preferred_faculties ?? [] @endphp
                    @if(count($pf))
                        <div class="flex flex-wrap gap-1">
                            @foreach(array_slice($pf, 0, 4) as $tag)
                                <span class="inline-flex items-center px-2 py-0.5 bg-violet-50 text-violet-700 border border-violet-200 rounded-full text-xs">{{ $tag }}</span>
                            @endforeach
                            @if(count($pf) > 4)<span class="text-xs text-slate-400">+{{ count($pf) - 4 }} more</span>@endif
                        </div>
                    @else
                        <p class="text-slate-400 italic text-xs">None</p>
                    @endif
                </div>
            </div>

        </div>
        @else
        <div class="px-6 py-10 text-center text-slate-400">
            <p class="mb-3">No profile created yet for this student.</p>
            <a href="{{ route('admin.student-profiles.create', ['student_id' => $student->id]) }}"
                class="btn btn-primary text-sm">
                Create Profile Now
            </a>
        </div>
        @endif
    </div>

    {{-- Academic Records Section --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-slate-800">Academic Records</h3>
                <p class="text-xs text-slate-400 mt-0.5">Education history, results &amp; documents</p>
            </div>
            <div class="flex gap-2">
                @if($student->academicRecords->isNotEmpty())
                    <a href="{{ route('admin.student-academic-records.index', ['student_id' => $student->id]) }}"
                        class="btn btn-secondary text-sm">View All</a>
                @endif
                <a href="{{ route('admin.student-academic-records.create', ['student_id' => $student->id]) }}"
                    class="btn btn-primary text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Record
                </a>
            </div>
        </div>

        @if($student->academicRecords->isNotEmpty())
        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Institution</th>
                        <th>Board</th>
                        <th>Faculty</th>
                        <th>Year</th>
                        <th>GPA / %</th>
                        <th>Documents</th>
                        <th>Verified</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->academicRecords->sortByDesc('passed_year')->take(5) as $record)
                    <tr>
                        <td>
                            <span class="badge badge-blue">
                                {{ \App\Models\StudentAcademicRecord::LEVELS[$record->level] ?? $record->level }}
                            </span>
                        </td>
                        <td class="text-sm">{{ $record->institution_name ?? '-' }}</td>
                        <td class="text-sm">
                            {{ \App\Models\StudentAcademicRecord::BOARDS[$record->board] ?? ($record->board ?? '-') }}
                        </td>
                        <td class="text-sm">{{ $record->faculty ?? '-' }}</td>
                        <td class="text-sm">{{ $record->passed_year ?? '-' }}</td>
                        <td class="text-sm">
                            @if($record->gpa !== null)
                                {{ number_format($record->gpa, 2) }} GPA
                            @endif
                            @if($record->percentage !== null)
                                @if($record->gpa !== null) / @endif
                                {{ number_format($record->percentage, 2) }}%
                            @endif
                            @if($record->gpa === null && $record->percentage === null)
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td class="text-center text-sm">
                            @php
                                $docCount = (int)($record->transcript_file ? 1 : 0) + (int)($record->character_certificate_file ? 1 : 0);
                            @endphp
                            @if($docCount)
                                <span class="badge badge-blue">{{ $docCount }} file{{ $docCount > 1 ? 's' : '' }}</span>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                        <td>
                            @if($record->is_verified)
                                <span class="badge badge-green">Verified</span>
                            @else
                                <span class="badge badge-yellow">Pending</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('admin.student-academic-records.show', $record) }}"
                                    class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.student-academic-records.edit', $record) }}"
                                    class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($student->academicRecords->count() > 5)
        <div class="px-6 py-3 border-t border-slate-100 text-center">
            <a href="{{ route('admin.student-academic-records.index', ['student_id' => $student->id]) }}"
                class="text-sm text-blue-600 hover:underline">
                View all {{ $student->academicRecords->count() }} records →
            </a>
        </div>
        @endif
        @else
        <div class="px-6 py-10 text-center text-slate-400">
            <p class="mb-3">No academic records added yet.</p>
            <a href="{{ route('admin.student-academic-records.create', ['student_id' => $student->id]) }}"
                class="btn btn-primary text-sm">
                Add First Record
            </a>
        </div>
        @endif
    </div>

    {{-- ===================== STUDENT DOCUMENTS ===================== --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Student Documents</h2>
                <p class="text-xs text-slate-500 mt-0.5">Uploaded identity &amp; supporting documents</p>
            </div>
            <div class="flex items-center gap-2">
                @if($student->documents->count() > 0)
                <a href="{{ route('admin.student-documents.index', ['student_id' => $student->id]) }}"
                    class="btn btn-secondary text-sm">View All</a>
                @endif
                <a href="{{ route('admin.student-documents.create', ['student_id' => $student->id]) }}"
                    class="btn btn-primary text-sm">Upload Document</a>
            </div>
        </div>

        @if($student->documents->count() > 0)
        @php $latestDocs = $student->documents->sortByDesc('created_at')->take(5); @endphp
        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Uploaded</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($latestDocs as $doc)
                    <tr>
                        <td>
                            <span class="badge badge-blue">
                                {{ \App\Models\StudentDocument::DOCUMENT_TYPES[$doc->document_type] ?? $doc->document_type }}
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
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('admin.student-documents.show', $doc) }}"
                                    class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.student-documents.edit', $doc) }}"
                                    class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($student->documents->count() > 5)
        <div class="px-6 py-3 border-t border-slate-100 text-center">
            <a href="{{ route('admin.student-documents.index', ['student_id' => $student->id]) }}"
                class="text-sm text-blue-600 hover:underline">
                View all {{ $student->documents->count() }} documents →
            </a>
        </div>
        @endif
        @else
        <div class="px-6 py-10 text-center text-slate-400">
            <p class="mb-3">No documents uploaded yet.</p>
            <a href="{{ route('admin.student-documents.create', ['student_id' => $student->id]) }}"
                class="btn btn-primary text-sm">
                Upload First Document
            </a>
        </div>
        @endif
    </div>

    {{-- Applications --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Applications</h2>
                <p class="text-xs text-slate-500 mt-0.5">Institution program applications submitted by this student</p>
            </div>
            <div class="flex items-center gap-2">
                @if($student->applications->count() > 0)
                    <a href="{{ route('admin.applications.index', ['student_id' => $student->id]) }}" class="btn btn-secondary text-sm">View All</a>
                @endif
                <a href="{{ route('admin.applications.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add Application</a>
            </div>
        </div>

        @if($student->applications->count() > 0)
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Application</th>
                            <th>Institution</th>
                            <th>Program</th>
                            <th>Scholarship</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->applications->take(5) as $application)
                            <tr>
                                <td class="font-medium text-slate-800">{{ $application->application_number }}</td>
                                <td>{{ $application->institution->name ?? '-' }}</td>
                                <td>{{ $application->institutionProgram?->title ?: ($application->institutionProgram?->program?->name ?? '-') }}</td>
                                <td>{{ $application->scholarship->title ?? '-' }}</td>
                                <td><span class="badge">{{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}</span></td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.applications.show', $application) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.applications.edit', $application) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-10 text-center text-slate-400">
                <p class="mb-3">No applications created yet.</p>
                <a href="{{ route('admin.applications.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add First Application</a>
            </div>
        @endif
    </div>

    {{-- Scholarship Applications --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Scholarship Applications</h2>
                <p class="text-xs text-slate-500 mt-0.5">Scholarship applications submitted by this student</p>
            </div>
            <div class="flex items-center gap-2">
                @if($student->scholarshipApplications->count() > 0)
                    <a href="{{ route('admin.scholarship-applications.index', ['student_id' => $student->id]) }}" class="btn btn-secondary text-sm">View All</a>
                @endif
                <a href="{{ route('admin.scholarship-applications.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add Application</a>
            </div>
        </div>

        @if($student->scholarshipApplications->count() > 0)
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Scholarship</th>
                            <th>Institution</th>
                            <th>Approved Amount</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->scholarshipApplications->take(5) as $sa)
                            <tr>
                                <td class="font-medium text-sm">{{ $sa->scholarship->title ?? '-' }}</td>
                                <td class="text-sm text-slate-500">{{ $sa->scholarship?->institution?->name ?? '-' }}</td>
                                <td class="font-mono text-sm">{{ $sa->approved_amount !== null ? number_format((float) $sa->approved_amount, 2) : '-' }}</td>
                                <td><span class="badge">{{ \App\Models\ScholarshipApplication::STATUSES[$sa->status] ?? $sa->status }}</span></td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.scholarship-applications.show', $sa) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.scholarship-applications.edit', $sa) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-10 text-center text-slate-400">
                <p class="mb-3">No scholarship applications yet.</p>
                <a href="{{ route('admin.scholarship-applications.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add First</a>
            </div>
        @endif
    </div>

    {{-- Scholarship Cashbacks --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Scholarship Cashbacks</h2>
                <p class="text-xs text-slate-500 mt-0.5">Cashback disbursements for this student</p>
            </div>
            <div class="flex items-center gap-2">
                @if($student->scholarshipCashbacks->count() > 0)
                    <a href="{{ route('admin.scholarship-cashbacks.index', ['student_id' => $student->id]) }}" class="btn btn-secondary text-sm">View All</a>
                @endif
                <a href="{{ route('admin.scholarship-cashbacks.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add Cashback</a>
            </div>
        </div>

        @if($student->scholarshipCashbacks->count() > 0)
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Commission Received</th>
                            <th>Cashback %</th>
                            <th>Cashback Amount</th>
                            <th>Paid At</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->scholarshipCashbacks->take(5) as $cashback)
                            <tr>
                                <td class="font-mono text-xs text-slate-500">
                                    @if($cashback->commissionInvoice)
                                        <a href="{{ route('admin.commission-invoices.show', $cashback->commissionInvoice) }}" class="text-blue-600 hover:underline">
                                            {{ $cashback->commissionInvoice->invoice_number }}
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="font-mono text-sm">{{ number_format((float) $cashback->commission_received_amount, 2) }}</td>
                                <td class="font-mono text-sm">{{ number_format((float) $cashback->cashback_percentage, 4) }}%</td>
                                <td class="font-mono text-sm font-semibold text-green-700">{{ number_format((float) $cashback->cashback_amount, 2) }}</td>
                                <td class="text-xs text-slate-500">{{ $cashback->paid_at?->format('d M Y') ?? '-' }}</td>
                                <td><span class="badge">{{ \App\Models\ScholarshipCashback::STATUSES[$cashback->status] ?? $cashback->status }}</span></td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.scholarship-cashbacks.show', $cashback) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.scholarship-cashbacks.edit', $cashback) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-10 text-center text-slate-400">
                <p class="mb-3">No scholarship cashbacks yet.</p>
                <a href="{{ route('admin.scholarship-cashbacks.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add First</a>
            </div>
        @endif
    </div>

    {{-- Favorite Institutions --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Favorite Institutions</h2>
                <p class="text-xs text-slate-500 mt-0.5">Institutions bookmarked by this student</p>
            </div>
            <div class="flex items-center gap-2">
                @if($student->favoriteInstitutions->count() > 0)
                    <a href="{{ route('admin.student-favorite-institutions.index', ['student_id' => $student->id]) }}" class="btn btn-secondary text-sm">View All</a>
                @endif
                <a href="{{ route('admin.student-favorite-institutions.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add Favorite</a>
            </div>
        </div>

        @if($student->favoriteInstitutions->count() > 0)
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Added At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->favoriteInstitutions->take(5) as $fav)
                            <tr>
                                <td>
                                    @if($fav->institution)
                                        <a href="{{ route('admin.institutions.show', $fav->institution) }}" class="font-medium text-blue-600 hover:underline text-sm">
                                            {{ $fav->institution->name }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">{{ $fav->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.student-favorite-institutions.show', $fav) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.student-favorite-institutions.destroy', $fav) }}" method="POST" onsubmit="return confirm('Remove this favorite?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-delete" title="Remove">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
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
            <div class="px-6 py-10 text-center text-slate-400">
                <p class="mb-3">No favorite institutions yet.</p>
                <a href="{{ route('admin.student-favorite-institutions.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add First</a>
            </div>
        @endif
    </div>

    {{-- Compare Items --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Compare Items</h2>
                <p class="text-xs text-slate-500 mt-0.5">Institutions and programs in this student's comparison list</p>
            </div>
            <div class="flex items-center gap-2">
                @if($student->compareItems->count() > 0)
                    <a href="{{ route('admin.student-compare-items.index', ['student_id' => $student->id]) }}" class="btn btn-secondary text-sm">View All</a>
                @endif
                <a href="{{ route('admin.student-compare-items.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add Item</a>
            </div>
        </div>

        @if($student->compareItems->count() > 0)
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Program</th>
                            <th>Added At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->compareItems->take(5) as $item)
                            <tr>
                                <td>
                                    @if($item->institution)
                                        <a href="{{ route('admin.institutions.show', $item->institution) }}" class="font-medium text-blue-600 hover:underline text-sm">
                                            {{ $item->institution->name }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="text-sm text-slate-600">
                                    @if($item->institutionProgram)
                                        {{ $item->institutionProgram->title ?: ($item->institutionProgram->program->name ?? '-') }}
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">{{ $item->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.student-compare-items.show', $item) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.student-compare-items.destroy', $item) }}" method="POST" onsubmit="return confirm('Remove this compare item?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-delete" title="Remove">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
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
            <div class="px-6 py-10 text-center text-slate-400">
                <p class="mb-3">No compare items yet.</p>
                <a href="{{ route('admin.student-compare-items.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add First</a>
            </div>
        @endif
    </div>

    {{-- Recommendations --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Recommendations</h2>
                <p class="text-xs text-slate-500 mt-0.5">Institution and program recommendations for this student</p>
            </div>
            <div class="flex items-center gap-2">
                @if($student->recommendations->count() > 0)
                    <a href="{{ route('admin.student-recommendations.index', ['student_id' => $student->id]) }}" class="btn btn-secondary text-sm">View All</a>
                @endif
                <a href="{{ route('admin.student-recommendations.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add</a>
            </div>
        </div>

        @if($student->recommendations->count() > 0)
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Program</th>
                            <th>Score</th>
                            <th>Viewed</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->recommendations->take(5) as $rec)
                            <tr>
                                <td>
                                    @if($rec->institution)
                                        <a href="{{ route('admin.institutions.show', $rec->institution) }}" class="font-medium text-blue-600 hover:underline text-sm">{{ $rec->institution->name }}</a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="text-sm text-slate-600">
                                    {{ $rec->institutionProgram?->title ?: ($rec->institutionProgram?->program?->name ?? '-') }}
                                </td>
                                <td>
                                    @if($rec->score !== null)
                                        <span class="font-mono font-semibold text-sm {{ (float) $rec->score >= 80 ? 'text-green-600' : ((float) $rec->score >= 50 ? 'text-amber-600' : 'text-red-500') }}">
                                            {{ number_format((float) $rec->score, 2) }}
                                        </span>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($rec->is_viewed)
                                        <span class="badge badge-green text-xs">Viewed</span>
                                    @else
                                        <span class="badge text-xs">Not Viewed</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.student-recommendations.show', $rec) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-10 text-center text-slate-400">
                <p class="mb-3">No recommendations yet.</p>
                <a href="{{ route('admin.student-recommendations.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Add First</a>
            </div>
        @endif
    </div>

    {{-- Followed Institutions --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Followed Institutions</h2>
                <p class="text-xs text-slate-500 mt-0.5">Institutions this student follows</p>
            </div>
            @if($student->followedInstitutions->count() > 0)
                <a href="{{ route('admin.institution-followers.index', ['student_id' => $student->id]) }}" class="btn btn-secondary text-sm">View All</a>
            @endif
        </div>

        @if($student->followedInstitutions->count() > 0)
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Followed At</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->followedInstitutions->take(5) as $follow)
                            <tr>
                                <td>
                                    @if($follow->institution)
                                        <a href="{{ route('admin.institutions.show', $follow->institution) }}" class="font-medium text-blue-600 hover:underline text-sm">
                                            {{ $follow->institution->name }}
                                        </a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">{{ $follow->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.institution-followers.show', $follow) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.institution-followers.destroy', $follow) }}" method="POST" onsubmit="return confirm('Remove this follow?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-delete" title="Remove">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
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
            <div class="px-6 py-10 text-center text-slate-400">
                <p>No followed institutions yet.</p>
            </div>
        @endif
    </div>

    {{-- Counseling Sessions --}}
    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-semibold text-slate-800">Counseling Sessions</h2>
                <p class="text-xs text-slate-500 mt-0.5">Scheduled counseling sessions for this student</p>
            </div>
            <div class="flex items-center gap-2">
                @if($student->counselingSessions->count() > 0)
                    <a href="{{ route('admin.counseling-sessions.index', ['student_id' => $student->id]) }}" class="btn btn-secondary text-sm">View All</a>
                @endif
                <a href="{{ route('admin.counseling-sessions.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Schedule</a>
            </div>
        </div>

        @if($student->counselingSessions->count() > 0)
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Scheduled At</th>
                            <th>Institution</th>
                            <th>Counselor</th>
                            <th>Mode</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->counselingSessions->take(5) as $cs)
                            <tr>
                                <td class="text-sm {{ $cs->scheduled_at->isPast() && $cs->status === 'scheduled' ? 'text-red-600 font-medium' : 'text-slate-700' }}">
                                    {{ $cs->scheduled_at->format('d M Y, H:i') }}
                                </td>
                                <td class="text-sm">{{ $cs->institution->name ?? '-' }}</td>
                                <td class="text-sm">{{ $cs->counselor->name ?? '-' }}</td>
                                <td class="text-sm">{{ \App\Models\CounselingSession::MODES[$cs->mode] ?? $cs->mode }}</td>
                                <td><span class="badge">{{ \App\Models\CounselingSession::STATUSES[$cs->status] ?? $cs->status }}</span></td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.counseling-sessions.show', $cs) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-10 text-center text-slate-400">
                <p class="mb-3">No counseling sessions yet.</p>
                <a href="{{ route('admin.counseling-sessions.create', ['student_id' => $student->id]) }}" class="btn btn-primary text-sm">Schedule First</a>
            </div>
        @endif
    </div>

    {{-- Reviews --}}
    <div class="eims-card overflow-hidden">
        <div class="card-header flex items-center justify-between">
            <h2 class="eims-card-title !mb-0 !pb-0 !border-0">Institution Reviews</h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-500">{{ $student->reviews->count() }} total</span>
                <a href="{{ route('admin.institution-reviews.create', ['student_id' => $student->id]) }}" class="btn btn-primary btn-sm">Add Review</a>
            </div>
        </div>
        @if($student->reviews->isEmpty())
            <div class="px-6 py-8 text-center text-slate-400">
                <p>No reviews submitted yet.</p>
            </div>
        @else
            <div class="eims-table-wrapper">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Approved</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->reviews->take(5) as $review)
                            <tr>
                                <td class="text-sm">
                                    <a href="{{ route('admin.institutions.show', $review->institution) }}" class="text-blue-600 hover:underline">{{ $review->institution->name }}</a>
                                </td>
                                <td>
                                    <span class="font-semibold text-amber-500">{{ $review->rating }}</span>
                                    <span class="text-slate-400 text-xs">/ 5</span>
                                </td>
                                <td class="text-sm max-w-xs truncate text-slate-600">{{ $review->review ? \Illuminate\Support\Str::limit($review->review, 50) : '-' }}</td>
                                <td>
                                    @if($review->is_approved)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">{{ $review->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.institution-reviews.show', $review) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($student->reviews->count() > 5)
                <div class="px-6 py-3 border-t border-slate-100 text-right">
                    <a href="{{ route('admin.institution-reviews.index', ['student_id' => $student->id]) }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                        View all reviews by this student →
                    </a>
                </div>
            @endif
        @endif
    </div>

    {{-- Conversations --}}
    <div class="eims-card overflow-hidden">
        <div class="card-header flex items-center justify-between">
            <h2 class="eims-card-title !mb-0 !pb-0 !border-0">Conversations</h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-500">{{ $student->conversations->count() }} total</span>
                <a href="{{ route('admin.conversations.create', ['student_id' => $student->id]) }}" class="btn btn-primary btn-sm">New Conversation</a>
            </div>
        </div>
        @if($student->conversations->isEmpty())
            <div class="px-6 py-8 text-center text-slate-400">
                <p>No conversations yet.</p>
            </div>
        @else
            <div class="eims-table-wrapper">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Institution</th>
                            <th>Type</th>
                            <th>Started</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->conversations->take(5) as $conv)
                            <tr>
                                <td class="text-sm">
                                    @if($conv->institution)
                                        <a href="{{ route('admin.institutions.show', $conv->institution) }}" class="text-blue-600 hover:underline">{{ $conv->institution->name }}</a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge">{{ \App\Models\Conversation::TYPES[$conv->type] ?? $conv->type }}</span>
                                </td>
                                <td class="text-xs text-slate-500">{{ $conv->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <div class="flex justify-center gap-1">
                                        <a href="{{ route('admin.conversations.show', $conv) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($student->conversations->count() > 5)
                <div class="px-6 py-3 border-t border-slate-100 text-right">
                    <a href="{{ route('admin.conversations.index', ['student_id' => $student->id]) }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                        View all conversations →
                    </a>
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
