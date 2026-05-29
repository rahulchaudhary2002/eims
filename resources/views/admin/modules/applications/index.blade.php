@extends('admin.layouts.app')
@section('title', 'Applications')
@section('page-title', 'Applications')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Applications"
        subtitle="Manage student applications."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Applications'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.applications.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Application
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.applications.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
            <div>
                <label class="form-label text-xs">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Number or student">
            </div>
            <div>
                <label class="form-label text-xs">Student</label>
                <select name="student_id" class="form-control">
                    <option value="">All Students</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Institution</label>
                <select name="institution_id" class="form-control">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution->id }}" {{ request('institution_id') == $institution->id ? 'selected' : '' }}>{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Institution Program</label>
                <select name="institution_program_id" class="form-control">
                    <option value="">All Programs</option>
                    @foreach($institutionPrograms as $institutionProgram)
                        <option value="{{ $institutionProgram->id }}" {{ request('institution_program_id') == $institutionProgram->id ? 'selected' : '' }}>
                            {{ $institutionProgram->institution->name ?? 'Institution' }} - {{ $institutionProgram->title ?: ($institutionProgram->program->name ?? 'Program') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Scholarship</label>
                <select name="scholarship_id" class="form-control">
                    <option value="">All Scholarships</option>
                    @foreach($scholarships as $scholarship)
                        <option value="{{ $scholarship->id }}" {{ request('scholarship_id') == $scholarship->id ? 'selected' : '' }}>{{ $scholarship->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Source</label>
                <select name="source" class="form-control">
                    <option value="">All Sources</option>
                    @foreach($sources as $value => $label)
                        <option value="{{ $value }}" {{ request('source') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Submitted From</label>
                <input type="date" name="submitted_from" value="{{ request('submitted_from') }}" class="form-control">
            </div>
            <div>
                <label class="form-label text-xs">Submitted To</label>
                <input type="date" name="submitted_to" value="{{ request('submitted_to') }}" class="form-control">
            </div>
            <div class="flex gap-2 md:col-span-3 xl:col-span-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Application</th>
                        <th>Student</th>
                        <th>Institution</th>
                        <th>Scholarship</th>
                        <th>Source</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($applications as $application)
                        <tr>
                            <td>
                                <a href="{{ route('admin.applications.show', $application) }}" class="font-semibold text-blue-600 hover:underline">{{ $application->application_number }}</a>
                                <div class="text-xs text-slate-400">#{{ $application->id }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $application->student->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $application->student->email ?? '' }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $application->institution->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $application->institutionProgram?->title ?: ($application->institutionProgram?->program?->name ?? '-') }}</div>
                            </td>
                            <td>{{ $application->scholarship->title ?? '-' }}</td>
                            <td>{{ $sources[$application->source] ?? $application->source }}</td>
                            <td class="text-xs text-slate-500">{{ $application->submitted_at?->format('d M Y') ?? '-' }}</td>
                            <td><span class="badge">{{ $statuses[$application->status] ?? $application->status }}</span></td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.applications.show', $application) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.applications.edit', $application) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Delete this application?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-slate-400 py-10">No applications found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($applications->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $applications->links() }}</div>
        @endif
    </div>
</div>
@endsection
