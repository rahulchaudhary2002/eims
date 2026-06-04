@extends('admin.layouts.app')
@section('title', 'Inquiries')
@section('page-title', 'Inquiries')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Inquiries"
        subtitle="Manage lead inquiries from prospective students."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Inquiries'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.inquiries.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Inquiry
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.inquiries.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
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
                <label class="form-label text-xs">Program</label>
                <select name="institution_program_id" class="form-control">
                    <option value="">All Programs</option>
                    @foreach($institutionPrograms as $program)
                        <option value="{{ $program->id }}" {{ request('institution_program_id') == $program->id ? 'selected' : '' }}>
                            {{ $program->title ?: ($program->program->name ?? 'Program #' . $program->id) }}
                        </option>
                    @endforeach
                </select>
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
                <label class="form-label text-xs">Assigned To</label>
                <select name="assigned_to" class="form-control">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Contacted From</label>
                <input type="date" name="contacted_from" value="{{ request('contacted_from') }}" class="form-control">
            </div>
            <div>
                <label class="form-label text-xs">Contacted To</label>
                <input type="date" name="contacted_to" value="{{ request('contacted_to') }}" class="form-control">
            </div>
            <div class="flex gap-2 md:col-span-3 xl:col-span-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Name / Email</th>
                        <th>Institution</th>
                        <th>Source</th>
                        <th>Assigned To</th>
                        <th>Last Contacted</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inquiries as $inquiry)
                        <tr>
                            <td>
                                <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="font-semibold text-blue-600 hover:underline text-sm">
                                    {{ $inquiry->name }}
                                </a>
                                <div class="text-xs text-slate-400">{{ $inquiry->email }}</div>
                                @if($inquiry->phone)
                                    <div class="text-xs text-slate-400">{{ $inquiry->phone }}</div>
                                @endif
                            </td>
                            <td class="text-sm">
                                <div>{{ $inquiry->institution->name ?? '-' }}</div>
                                @if($inquiry->institutionProgram)
                                    <div class="text-xs text-slate-400">
                                        {{ $inquiry->institutionProgram->title ?: ($inquiry->institutionProgram->program->name ?? '') }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-sm">{{ $sources[$inquiry->source] ?? ($inquiry->source ?: '-') }}</td>
                            <td class="text-sm">{{ $inquiry->assignedTo->name ?? '-' }}</td>
                            <td class="text-xs text-slate-500">{{ $inquiry->last_contacted_at?->format('d M Y, H:i') ?? '-' }}</td>
                            <td><span class="badge">{{ $statuses[$inquiry->status] ?? $inquiry->status }}</span></td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.inquiries.show', $inquiry) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.inquiries.edit', $inquiry) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Delete this inquiry?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-400 py-10">No inquiries found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($inquiries->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $inquiries->links() }}</div>
        @endif
    </div>
</div>
@endsection
