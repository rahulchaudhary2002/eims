@extends('admin.layouts.app')
@section('title', 'Admissions')
@section('page-title', 'Admissions')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Admissions"
        subtitle="Manage verified admission records."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Admissions'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.admissions.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Admission
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.admissions.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
            <div>
                <label class="form-label text-xs">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Admission, application, student">
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
                <label class="form-label text-xs">Verification</label>
                <select name="verification_status" class="form-control">
                    <option value="">All Statuses</option>
                    @foreach($verificationStatuses as $value => $label)
                        <option value="{{ $value }}" {{ request('verification_status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Admission From</label>
                <input type="date" name="admission_from" value="{{ request('admission_from') }}" class="form-control">
            </div>
            <div>
                <label class="form-label text-xs">Admission To</label>
                <input type="date" name="admission_to" value="{{ request('admission_to') }}" class="form-control">
            </div>
            <div class="flex gap-2 md:col-span-3 xl:col-span-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Admission</th>
                        <th>Student</th>
                        <th>Institution</th>
                        <th>Paid</th>
                        <th>Date</th>
                        <th>Verification</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admissions as $admission)
                        <tr>
                            <td>
                                <a href="{{ route('admin.admissions.show', $admission) }}" class="font-semibold text-blue-600 hover:underline">{{ $admission->admission_number }}</a>
                                <div class="text-xs text-slate-400">{{ $admission->application->application_number ?? '-' }}</div>
                            </td>
                            <td>{{ $admission->student->name ?? '-' }}</td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $admission->institution->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $admission->applicable_label ?? '-' }}</div>
                            </td>
                            <td>{{ $admission->paid_amount !== null ? number_format((float) $admission->paid_amount, 2) : '-' }}</td>
                            <td class="text-xs text-slate-500">{{ $admission->admission_date?->format('d M Y') ?? '-' }}</td>
                            <td><span class="badge">{{ $verificationStatuses[$admission->verification_status] ?? $admission->verification_status }}</span></td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.admissions.show', $admission) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.admissions.edit', $admission) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.admissions.destroy', $admission) }}" method="POST" onsubmit="return confirm('Delete this admission?')">
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
                            <td colspan="7" class="text-center text-slate-400 py-10">No admissions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($admissions->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $admissions->links() }}</div>
        @endif
    </div>
</div>
@endsection
