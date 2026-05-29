@extends('admin.layouts.app')
@section('title', 'Counseling Session #' . $counselingSession->id)
@section('page-title', 'Counseling Session Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Session #{{ $counselingSession->id }}"
        subtitle="{{ $counselingSession->student->name ?? 'Student' }} · {{ $counselingSession->scheduled_at->format('d M Y, H:i') }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Counseling Sessions', 'route' => 'admin.counseling-sessions.index'],
            ['label' => 'Session #' . $counselingSession->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.counseling-sessions.edit', $counselingSession) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.counseling-sessions.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Session Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Student</dt>
                        <dd class="mt-1">
                            @if($counselingSession->student)
                                <a href="{{ route('admin.students.show', $counselingSession->student) }}" class="text-blue-600 hover:underline font-medium">
                                    {{ $counselingSession->student->name }}
                                </a>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $counselingSession->student->email }}</div>
                            @else <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Institution</dt>
                        <dd class="mt-1">
                            @if($counselingSession->institution)
                                <a href="{{ route('admin.institutions.show', $counselingSession->institution) }}" class="text-blue-600 hover:underline">
                                    {{ $counselingSession->institution->name }}
                                </a>
                            @else <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Counselor</dt>
                        <dd class="mt-1 text-slate-800">{{ $counselingSession->counselor->name ?? 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Mode</dt>
                        <dd class="mt-1 text-slate-800">{{ \App\Models\CounselingSession::MODES[$counselingSession->mode] ?? $counselingSession->mode }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Scheduled At</dt>
                        <dd class="mt-1 {{ $counselingSession->scheduled_at->isPast() && $counselingSession->status === 'scheduled' ? 'text-red-600 font-semibold' : 'text-slate-800' }}">
                            {{ $counselingSession->scheduled_at->format('d M Y, H:i') }}
                            @if($counselingSession->scheduled_at->isPast() && $counselingSession->status === 'scheduled')
                                <span class="text-xs ml-1">(Overdue)</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $counselingSession->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>

                @if($counselingSession->student_message)
                    <div class="mt-5 pt-5 border-t border-slate-100">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Student Message</h4>
                        <p class="text-sm text-slate-700 whitespace-pre-line bg-slate-50 rounded-lg p-3">{{ $counselingSession->student_message }}</p>
                    </div>
                @endif

                @if($counselingSession->counselor_notes)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Counselor Notes</h4>
                        <p class="text-sm text-slate-700 whitespace-pre-line bg-indigo-50 rounded-lg p-3 border-l-4 border-indigo-300">{{ $counselingSession->counselor_notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Status + Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Status</h3>
                <span class="badge text-sm">{{ \App\Models\CounselingSession::STATUSES[$counselingSession->status] ?? $counselingSession->status }}</span>

                <form action="{{ route('admin.counseling-sessions.update-status', $counselingSession) }}" method="POST" class="mt-4 space-y-2">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control text-sm">
                        @foreach(\App\Models\CounselingSession::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $counselingSession->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-full text-sm">Update Status</button>
                </form>
            </div>

            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.counseling-sessions.edit', $counselingSession) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Session
                    </a>
                    <form action="{{ route('admin.counseling-sessions.destroy', $counselingSession) }}" method="POST" onsubmit="return confirm('Delete this session? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Session
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
