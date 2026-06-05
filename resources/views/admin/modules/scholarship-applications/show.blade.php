@extends('admin.layouts.app')
@section('title', 'Scholarship Application #' . $scholarshipApplication->id)
@section('page-title', 'Scholarship Application Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Application #{{ $scholarshipApplication->id }}"
        subtitle="{{ $scholarshipApplication->scholarship->title ?? 'Scholarship Application' }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Scholarship Applications', 'route' => 'admin.scholarship-applications.index'],
            ['label' => 'Application #' . $scholarshipApplication->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.applications.edit', $scholarshipApplication) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit Application
            </a>
            <a href="{{ route('admin.scholarship-applications.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main Details --}}
        <div class="lg:col-span-2">
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Application Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Scholarship</dt>
                        <dd class="mt-1">
                            @if($scholarshipApplication->scholarship)
                                <a href="{{ route('admin.scholarships.show', $scholarshipApplication->scholarship) }}" class="text-blue-600 hover:underline">
                                    {{ $scholarshipApplication->scholarship->title }}
                                </a>
                            @else -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Institution</dt>
                        <dd class="mt-1 text-slate-700">
                            @if($scholarshipApplication->scholarship?->institution)
                                <a href="{{ route('admin.institutions.show', $scholarshipApplication->scholarship->institution) }}" class="text-blue-600 hover:underline">
                                    {{ $scholarshipApplication->scholarship->institution->name }}
                                </a>
                            @else -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Student</dt>
                        <dd class="mt-1">
                            @if($scholarshipApplication->student)
                                <a href="{{ route('admin.students.show', $scholarshipApplication->student) }}" class="text-blue-600 hover:underline">
                                    {{ $scholarshipApplication->student->name }}
                                </a>
                            @else -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Applied For</dt>
                        <dd class="mt-1 text-slate-700">{{ $scholarshipApplication->applicable_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Application</dt>
                        <dd class="mt-1">
                            <a href="{{ route('admin.applications.show', $scholarshipApplication) }}" class="text-blue-600 hover:underline font-mono text-sm">
                                {{ $scholarshipApplication->application_number }}
                            </a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Scholarship Approved Amount</dt>
                        <dd class="mt-1 font-mono font-semibold text-slate-800">
                            {{ $scholarshipApplication->scholarship_approved_amount !== null ? number_format((float) $scholarshipApplication->scholarship_approved_amount, 2) : '-' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $scholarshipApplication->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($scholarshipApplication->scholarship_remarks)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Remarks</dt>
                            <dd class="mt-1 text-slate-700 whitespace-pre-line">{{ $scholarshipApplication->scholarship_remarks }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Status + Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Status</h3>
                <span class="badge text-sm">{{ \App\Models\Application::SCHOLARSHIP_STATUSES[$scholarshipApplication->scholarship_status] ?? ($scholarshipApplication->scholarship_status ?? '-') }}</span>

                <form action="{{ route('admin.scholarship-applications.update-status', $scholarshipApplication) }}" method="POST" class="mt-4 space-y-2">
                    @csrf @method('PATCH')
                    <select name="scholarship_status" class="form-control text-sm">
                        @foreach(\App\Models\Application::SCHOLARSHIP_STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $scholarshipApplication->scholarship_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="scholarship_approved_amount" step="0.01" min="0" placeholder="Approved amount (optional)" class="form-control text-sm" value="{{ $scholarshipApplication->scholarship_approved_amount }}">
                    <textarea name="scholarship_remarks" rows="2" class="form-control text-sm" placeholder="Remarks (optional)">{{ $scholarshipApplication->scholarship_remarks }}</textarea>
                    <button type="submit" class="btn btn-primary w-full text-sm">Update Status</button>
                </form>
            </div>

            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.applications.show', $scholarshipApplication) }}" class="btn btn-secondary w-full text-sm">
                        View Full Application
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
