@extends('admin.layouts.app')
@section('title', 'Application Status Log')
@section('page-title', 'Application Status Log')

@section('content')
@php
    $log = $applicationStatusLog;
    $application = $log->application;
@endphp
<div class="space-y-5">
    <x-admin.page-header
        title="Application Status Log"
        :subtitle="$application->application_number ?? 'Status change'"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Status Logs','route' => 'admin.application-status-logs.index'],
            ['label'=>'Log #' . $log->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.application-status-logs.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="space-y-5">
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Change</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">From</dt><dd>{{ $log->from_status ? (\App\Models\Application::STATUSES[$log->from_status] ?? $log->from_status) : '-' }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">To</dt><dd>{{ \App\Models\Application::STATUSES[$log->to_status] ?? $log->to_status }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Changed At</dt><dd class="text-right">{{ $log->created_at->format('d M Y, H:i') }}</dd></div>
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Changed By</dt><dd class="text-right">{{ $log->changedBy->name ?? 'System' }}</dd></div>
                </dl>
            </div>

            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <form action="{{ route('admin.application-status-logs.destroy', $log) }}" method="POST" onsubmit="return confirm('Delete this status log? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Log</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Application</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Application</dt>
                        <dd><a href="{{ route('admin.applications.show', $application) }}" class="text-blue-600 hover:underline">{{ $application->application_number ?? '-' }}</a></dd>
                    </div>
                    <div><dt class="text-slate-400 text-xs mb-1">Student</dt><dd>{{ $application->student->name ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Institution</dt><dd>{{ $application->institution->name ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Program</dt><dd>{{ $application->institutionProgram?->title ?: ($application->institutionProgram?->program?->name ?? '-') }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Scholarship</dt><dd>{{ $application->scholarship->title ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Current Status</dt><dd>{{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}</dd></div>
                </dl>
            </div>

            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Remarks</h3>
                <div class="text-sm text-slate-700 whitespace-pre-line">{{ $log->remarks ?: '-' }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
