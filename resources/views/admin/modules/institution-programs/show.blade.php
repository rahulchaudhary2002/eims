@extends('admin.layouts.app')
@section('title', ($institutionProgram->institution->name ?? '') . ' - ' . ($institutionProgram->program->name ?? ''))
@section('page-title', 'Institution Program')

@section('content')
@php
    use App\Models\InstitutionProgram;
    $ip = $institutionProgram;
    $statusColor = match($ip->status) {
        'open'      => 'green',
        'upcoming'  => 'blue',
        'suspended' => 'orange',
        default     => 'red',
    };
@endphp
<div class="space-y-5">

    <x-admin.page-header
        :title="($ip->title ?: ($ip->program->name ?? 'Program'))"
        :subtitle="($ip->institution->name ?? '-')"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Institution Programs','route' => 'admin.institution-programs.index'],
            ['label'=>$ip->title ?: ($ip->program->name ?? 'Details')],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-programs.edit', $ip) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.institution-programs.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left sidebar --}}
        <div class="space-y-5">

            {{-- Status card --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Status</h3>
                <div>
                    <span class="badge badge-{{ $statusColor }}">
                        {{ InstitutionProgram::STATUSES[$ip->status] ?? $ip->status }}
                    </span>
                </div>
                <form action="{{ route('admin.institution-programs.update-status', $ip) }}" method="POST" class="space-y-2">
                    @csrf @method('PATCH')
                    <label class="form-label text-xs">Change Status</label>
                    <div class="flex gap-2">
                        <select name="status" class="form-control text-sm">
                            @foreach(InstitutionProgram::STATUSES as $val => $label)
                                <option value="{{ $val }}" {{ $ip->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn btn-primary text-xs px-3 whitespace-nowrap">Update</button>
                    </div>
                </form>
            </div>

            {{-- Institution card --}}
            @if($ip->institution)
            <div class="eims-card p-5 space-y-3">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Institution</h3>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-sm shrink-0">
                        {{ mb_strtoupper(mb_substr($ip->institution->name, 0, 1)) }}
                    </div>
                    <p class="font-semibold text-slate-800 text-sm">{{ $ip->institution->name }}</p>
                </div>
                <a href="{{ route('admin.institutions.show', $ip->institution) }}"
                   class="block text-center text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    View institution →
                </a>
            </div>
            @endif

            {{-- Program card --}}
            @if($ip->program)
            <div class="eims-card p-5 space-y-3">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Program</h3>
                <div>
                    <p class="font-semibold text-slate-800 text-sm">{{ $ip->program->name }}</p>
                    @if($ip->program->level)
                        <span class="badge badge-blue text-xs mt-1">{{ $ip->program->level }}</span>
                    @endif
                    @if($ip->program->faculty)
                        <p class="text-xs text-slate-500 mt-1">{{ $ip->program->faculty->name }}</p>
                    @endif
                </div>
                <a href="{{ route('admin.programs.show', $ip->program) }}"
                   class="block text-center text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    View program →
                </a>
            </div>
            @endif

            {{-- Timestamps --}}
            <div class="eims-card p-5 text-sm space-y-3">
                <div>
                    <p class="text-slate-400 text-xs mb-1">Created</p>
                    <p class="text-slate-700">{{ $ip->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs mb-1">Last Updated</p>
                    <p class="text-slate-700">{{ $ip->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Main content --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Core details --}}
            <div class="eims-card p-6 space-y-5">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    @if($ip->title)
                    <div class="sm:col-span-2">
                        <dt class="text-slate-400 text-xs mb-1">Title</dt>
                        <dd class="text-slate-800 font-semibold">{{ $ip->title }}</dd>
                    </div>
                    @endif
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Duration</dt>
                        <dd class="text-slate-800">{{ $ip->duration_months ? $ip->duration_months . ' months' : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Admission Window</dt>
                        <dd class="text-slate-800">
                            @if($ip->admission_start_date || $ip->admission_end_date)
                                {{ $ip->admission_start_date?->format('d M Y') ?? '?' }}
                                -
                                {{ $ip->admission_end_date?->format('d M Y') ?? '?' }}
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            {{-- Fees --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">Fees</h3>
                <dl class="grid grid-cols-2 sm:grid-cols-3 gap-5 text-sm">
                    @foreach([
                        'admission_fee' => 'Admission Fee',
                        'monthly_fee'   => 'Monthly Fee',
                        'semester_fee'  => 'Semester Fee',
                        'annual_fee'    => 'Annual Fee',
                        'total_fee'     => 'Total Fee',
                    ] as $field => $label)
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">{{ $label }}</dt>
                        <dd class="text-slate-800 font-medium">
                            {{ $ip->$field !== null ? number_format($ip->$field, 2) : '-' }}
                        </dd>
                    </div>
                    @endforeach
                </dl>
            </div>

            {{-- Seats & Requirements --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">Seats & Requirements</h3>
                <dl class="grid grid-cols-2 sm:grid-cols-4 gap-5 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Total Seats</dt>
                        <dd class="text-slate-800 font-medium">{{ $ip->total_seats ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Available</dt>
                        <dd class="text-slate-800 font-medium">{{ $ip->available_seats ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Min GPA</dt>
                        <dd class="text-slate-800 font-medium">{{ $ip->minimum_gpa !== null ? number_format($ip->minimum_gpa, 2) : '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Min %</dt>
                        <dd class="text-slate-800 font-medium">{{ $ip->minimum_percentage !== null ? number_format($ip->minimum_percentage, 2) . '%' : '-' }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Subjects --}}
            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Subjects</h3>
                    <a href="{{ route('admin.institution-program-subjects.create', ['institution_program_id' => $ip->id]) }}" class="btn btn-primary btn-sm text-xs py-1 px-3">
                        + Add Subject
                    </a>
                </div>
                @if($ip->subjects->isEmpty())
                    <p class="text-slate-400 text-sm">No subjects added yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="eims-table text-sm">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Type</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ip->subjects->sortBy('subject_name') as $subject)
                                <tr>
                                    <td class="font-medium text-slate-800">{{ $subject->subject_name }}</td>
                                    <td>
                                        @if($subject->is_optional)
                                            <span class="badge badge-blue">Optional</span>
                                        @else
                                            <span class="badge badge-orange">Required</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <a href="{{ route('admin.institution-program-subjects.show', $subject) }}" class="btn-icon btn-icon-view" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.institution-program-subjects.edit', $subject) }}" class="btn-icon btn-icon-edit" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                            </a>
                                            <form action="{{ route('admin.institution-program-subjects.destroy', $subject) }}" method="POST"
                                                  onsubmit="return confirm('Delete subject?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-right">
                        <a href="{{ route('admin.institution-program-subjects.index', ['institution_program_id' => $ip->id]) }}" class="text-xs text-indigo-600 hover:underline">
                            View all subjects →
                        </a>
                    </div>
                @endif
            </div>

            {{-- Applications --}}
            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Applications</h3>
                    <a href="{{ route('admin.applications.create', ['institution_id' => $ip->institution_id, 'applicable_type' => \App\Models\InstitutionProgram::class, 'applicable_id' => $ip->id]) }}" class="btn btn-primary btn-sm text-xs py-1 px-3">
                        + Add Application
                    </a>
                </div>
                @if($ip->applications->isEmpty())
                    <p class="text-slate-400 text-sm">No applications added yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="eims-table text-sm">
                            <thead>
                                <tr>
                                    <th>Application</th>
                                    <th>Student</th>
                                    <th>Scholarship</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ip->applications->sortByDesc('created_at')->take(5) as $application)
                                    <tr>
                                        <td class="font-medium text-slate-800">{{ $application->application_number }}</td>
                                        <td>{{ $application->student->name ?? '-' }}</td>
                                        <td>{{ $application->scholarship->title ?? '-' }}</td>
                                        <td><span class="badge">{{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}</span></td>
                                        <td>
                                            <div class="flex items-center gap-1">
                                                <a href="{{ route('admin.applications.show', $application) }}" class="btn-icon btn-icon-view" title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                </a>
                                                <a href="{{ route('admin.applications.edit', $application) }}" class="btn-icon btn-icon-edit" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-right">
                        <a href="{{ route('admin.applications.index', ['applicable_type' => \App\Models\InstitutionProgram::class, 'applicable_id' => $ip->id]) }}" class="text-xs text-indigo-600 hover:underline">
                            View all applications →
                        </a>
                    </div>
                @endif
            </div>

            {{-- Scholarships --}}
            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Scholarships</h3>
                    <a href="{{ route('admin.scholarships.create', ['institution_id' => $ip->institution_id, 'institution_program_id' => $ip->id]) }}" class="btn btn-primary btn-sm text-xs py-1 px-3">
                        + Add Scholarship
                    </a>
                </div>
                @if($ip->scholarships->isEmpty())
                    <p class="text-slate-400 text-sm">No scholarships added yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="eims-table text-sm">
                            <thead>
                                <tr>
                                    <th>Scholarship</th>
                                    <th>Type</th>
                                    <th>Benefit</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ip->scholarships->sortBy('title') as $scholarship)
                                    <tr>
                                        <td class="font-medium text-slate-800">{{ $scholarship->title }}</td>
                                        <td>{{ \App\Models\Scholarship::TYPES[$scholarship->type] ?? $scholarship->type }}</td>
                                        <td>
                                            {{ \App\Models\Scholarship::BENEFIT_TYPES[$scholarship->benefit_type] ?? $scholarship->benefit_type }}
                                            @if($scholarship->benefit_value !== null)
                                                <span class="text-slate-400">({{ number_format((float) $scholarship->benefit_value, 2) }})</span>
                                            @endif
                                        </td>
                                        <td><span class="badge">{{ \App\Models\Scholarship::STATUSES[$scholarship->status] ?? $scholarship->status }}</span></td>
                                        <td>
                                            <div class="flex items-center gap-1">
                                                <a href="{{ route('admin.scholarships.show', $scholarship) }}" class="btn-icon btn-icon-view" title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                </a>
                                                <a href="{{ route('admin.scholarships.edit', $scholarship) }}" class="btn-icon btn-icon-edit" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3 text-right">
                        <a href="{{ route('admin.scholarships.index', ['institution_program_id' => $ip->id]) }}" class="text-xs text-indigo-600 hover:underline">
                            View all scholarships →
                        </a>
                    </div>
                @endif
            </div>

            {{-- Danger zone --}}
            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <p class="text-sm text-slate-600 mb-4">Permanently delete this institution program entry. This cannot be undone.</p>
                <form action="{{ route('admin.institution-programs.destroy', $ip) }}" method="POST"
                      onsubmit="return confirm('Delete this entry? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete Entry
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
