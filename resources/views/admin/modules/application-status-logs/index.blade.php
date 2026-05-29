@extends('admin.layouts.app')
@section('title', 'Application Status Logs')
@section('page-title', 'Application Status Logs')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Application Status Logs"
        subtitle="Review status changes recorded from application workflows."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Application Status Logs'],
        ]" />

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.application-status-logs.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
            <div>
                <label class="form-label text-xs">Application</label>
                <select name="application_id" class="form-control">
                    <option value="">All Applications</option>
                    @foreach($applications as $application)
                        <option value="{{ $application->id }}" {{ request('application_id') == $application->id ? 'selected' : '' }}>
                            {{ $application->application_number }} - {{ $application->student->name ?? 'Student' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">From Status</label>
                <select name="from_status" class="form-control">
                    <option value="">Any</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('from_status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">To Status</label>
                <select name="to_status" class="form-control">
                    <option value="">Any</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('to_status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Changed By</label>
                <select name="changed_by_type" class="form-control">
                    <option value="">Anyone</option>
                    @foreach($changedByTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('changed_by_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Created From</label>
                <input type="date" name="created_from" value="{{ request('created_from') }}" class="form-control">
            </div>
            <div>
                <label class="form-label text-xs">Created To</label>
                <input type="date" name="created_to" value="{{ request('created_to') }}" class="form-control">
            </div>
            <div class="flex gap-2 md:col-span-3 xl:col-span-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.application-status-logs.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Application</th>
                        <th>Institution</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Changed By</th>
                        <th>Changed At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>
                                <a href="{{ route('admin.applications.show', $log->application) }}" class="font-semibold text-blue-600 hover:underline">
                                    {{ $log->application->application_number ?? '-' }}
                                </a>
                                <div class="text-xs text-slate-400">{{ $log->application->student->name ?? '-' }}</div>
                            </td>
                            <td>{{ $log->application->institution->name ?? '-' }}</td>
                            <td>{{ $log->from_status ? ($statuses[$log->from_status] ?? $log->from_status) : '-' }}</td>
                            <td>{{ $statuses[$log->to_status] ?? $log->to_status }}</td>
                            <td>
                                <div>{{ $log->changedBy->name ?? 'System' }}</div>
                                <div class="text-xs text-slate-400">{{ $changedByTypes[$log->changed_by_type] ?? class_basename($log->changed_by_type ?? '') }}</div>
                            </td>
                            <td class="text-xs text-slate-500">{{ $log->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.application-status-logs.show', $log) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.application-status-logs.destroy', $log) }}" method="POST" onsubmit="return confirm('Delete this status log?')">
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
                            <td colspan="7" class="text-center text-slate-400 py-10">No application status logs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $logs->links() }}</div>
        @endif
    </div>
</div>
@endsection
