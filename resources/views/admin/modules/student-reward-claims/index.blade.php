@extends('admin.layouts.app')
@section('title', 'Student Reward Claims')
@section('page-title', 'Student Reward Claims')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Student Reward Claims"
        subtitle="Manage student cashback and reward claims."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Reward Claims'],
        ]">
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.student-reward-claims.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
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
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\StudentRewardClaim::STATUSES as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.student-reward-claims.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Claim Number</th>
                        <th>Student</th>
                        <th>Institution</th>
                        <th>Status</th>
                        <th>Admission Date</th>
                        <th>Submitted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims as $claim)
                        <tr>
                            <td>
                                <a href="{{ route('admin.student-reward-claims.show', $claim) }}" class="font-semibold text-blue-600 hover:underline">{{ $claim->claim_number }}</a>
                                <div class="text-xs text-slate-400">#{{ $claim->id }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $claim->student->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $claim->student->email ?? '' }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $claim->institution->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">{{ $claim->applicable_label }}</div>
                            </td>
                            <td><span class="badge">{{ \App\Models\StudentRewardClaim::STATUSES[$claim->status] ?? $claim->status }}</span></td>
                            <td class="text-xs text-slate-500">{{ $claim->admission_date?->format('d M Y') ?? '-' }}</td>
                            <td class="text-xs text-slate-500">{{ $claim->submitted_at?->format('d M Y, H:i') ?? '-' }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.student-reward-claims.show', $claim) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-slate-400 py-10">No reward claims found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($claims->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $claims->links() }}</div>
        @endif
    </div>
</div>
@endsection
