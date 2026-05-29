@extends('admin.layouts.app')
@section('title', $scholarship->title)
@section('page-title', 'Scholarship Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        :title="$scholarship->title"
        :subtitle="$scholarship->institution->name ?? 'Scholarship'"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Scholarships','route'=>'admin.scholarships.index'],
            ['label'=>$scholarship->title],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.scholarships.edit', $scholarship) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="space-y-5">
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Status</h3>
                <form action="{{ route('admin.scholarships.update-status', $scholarship) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control">
                        @foreach(\App\Models\Scholarship::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $scholarship->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-full">Update Status</button>
                </form>
            </div>

            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Slots</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Used</dt><dd>{{ $scholarship->used_slots ?? 0 }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Total</dt><dd>{{ $scholarship->total_slots ?? 'Unlimited' }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Details</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-slate-400 text-xs mb-1">Type</dt><dd>{{ \App\Models\Scholarship::TYPES[$scholarship->type] ?? $scholarship->type }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Slug</dt><dd class="font-mono">{{ $scholarship->slug }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Benefit Type</dt><dd>{{ \App\Models\Scholarship::BENEFIT_TYPES[$scholarship->benefit_type] ?? $scholarship->benefit_type }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Benefit Value</dt><dd>{{ $scholarship->benefit_value !== null ? number_format((float) $scholarship->benefit_value, 2) : '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Minimum GPA</dt><dd>{{ $scholarship->minimum_gpa ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Minimum Percentage</dt><dd>{{ $scholarship->minimum_percentage !== null ? $scholarship->minimum_percentage . '%' : '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Start Date</dt><dd>{{ $scholarship->start_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">End Date</dt><dd>{{ $scholarship->end_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution</dt>
                        <dd><a href="{{ route('admin.institutions.show', $scholarship->institution) }}" class="text-blue-600 hover:underline">{{ $scholarship->institution->name ?? '-' }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution Program</dt>
                        <dd>
                            @if($scholarship->institutionProgram)
                                <a href="{{ route('admin.institution-programs.show', $scholarship->institutionProgram) }}" class="text-blue-600 hover:underline">
                                    {{ $scholarship->institutionProgram->title ?: ($scholarship->institutionProgram->program->name ?? 'Program') }}
                                </a>
                            @else
                                Institution-wide
                            @endif
                        </dd>
                    </div>
                </dl>

                @if($scholarship->description)
                    <div class="mt-5 pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Description</h4>
                        <div class="prose prose-sm max-w-none text-slate-700">{!! nl2br(e($scholarship->description)) !!}</div>
                    </div>
                @endif
            </div>

            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Applications</h3>
                    <a href="{{ route('admin.applications.create', ['institution_id' => $scholarship->institution_id, 'institution_program_id' => $scholarship->institution_program_id, 'scholarship_id' => $scholarship->id]) }}" class="btn btn-primary btn-sm text-xs py-1 px-3">
                        Add Application
                    </a>
                </div>
                @if($scholarship->applications->isEmpty())
                    <p class="text-slate-400 text-sm">No applications linked to this scholarship yet.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="eims-table text-sm">
                            <thead>
                                <tr>
                                    <th>Application</th>
                                    <th>Student</th>
                                    <th>Status</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($scholarship->applications->sortByDesc('created_at')->take(5) as $application)
                                    <tr>
                                        <td class="font-medium text-slate-800">{{ $application->application_number }}</td>
                                        <td>{{ $application->student->name ?? '-' }}</td>
                                        <td><span class="badge">{{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}</span></td>
                                        <td class="text-xs text-slate-500">{{ $application->submitted_at?->format('d M Y') ?? '-' }}</td>
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
                        <a href="{{ route('admin.applications.index', ['scholarship_id' => $scholarship->id]) }}" class="text-xs text-indigo-600 hover:underline">
                            View all applications →
                        </a>
                    </div>
                @endif
            </div>

            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <form action="{{ route('admin.scholarships.destroy', $scholarship) }}" method="POST" onsubmit="return confirm('Delete this scholarship? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Scholarship</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
