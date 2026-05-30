@extends('admin.layouts.app')
@section('title', $application->application_number)
@section('page-title', 'Application Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        :title="$application->application_number"
        :subtitle="$application->student->name ?? 'Application'"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Applications','route' => 'admin.applications.index'],
            ['label'=>$application->application_number],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.applications.edit', $application) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.applications.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="space-y-5">
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Status</h3>
                <form action="{{ route('admin.applications.update-status', $application) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control">
                        @foreach(\App\Models\Application::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $application->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <textarea name="remarks" rows="3" class="form-control" placeholder="Status change remarks"></textarea>
                    <button type="submit" class="btn btn-primary w-full">Update Status</button>
                </form>
            </div>

            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Timeline</h3>
                <dl class="space-y-3 text-sm">
                    @foreach(['submitted_at' => 'Submitted', 'reviewed_at' => 'Reviewed', 'referred_at' => 'Referred', 'admitted_at' => 'Admitted'] as $field => $label)
                        <div class="flex justify-between gap-3">
                            <dt class="text-slate-500">{{ $label }}</dt>
                            <dd class="text-right">{{ $application->$field?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                    @endforeach
                </dl>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Details</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Student</dt>
                        <dd><a href="{{ route('admin.students.show', $application->student) }}" class="text-blue-600 hover:underline">{{ $application->student->name ?? '-' }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution</dt>
                        <dd><a href="{{ route('admin.institutions.show', $application->institution) }}" class="text-blue-600 hover:underline">{{ $application->institution->name ?? '-' }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution Program</dt>
                        <dd><a href="{{ route('admin.institution-programs.show', $application->institutionProgram) }}" class="text-blue-600 hover:underline">{{ $application->institutionProgram->title ?: ($application->institutionProgram->program->name ?? 'Program') }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Scholarship</dt>
                        <dd>
                            @if($application->scholarship)
                                <a href="{{ route('admin.scholarships.show', $application->scholarship) }}" class="text-blue-600 hover:underline">{{ $application->scholarship->title }}</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div><dt class="text-slate-400 text-xs mb-1">Source</dt><dd>{{ \App\Models\Application::SOURCES[$application->source] ?? $application->source }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Status</dt><dd>{{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}</dd></div>
                </dl>
            </div>

            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Messages & Remarks</h3>
                <dl class="space-y-5 text-sm">
                    <div><dt class="text-slate-400 text-xs mb-1">Student Message</dt><dd class="text-slate-700 whitespace-pre-line">{{ $application->student_message ?: '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Institution Remarks</dt><dd class="text-slate-700 whitespace-pre-line">{{ $application->institution_remarks ?: '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Admin Remarks</dt><dd class="text-slate-700 whitespace-pre-line">{{ $application->admin_remarks ?: '-' }}</dd></div>
                </dl>
            </div>

            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Status Timeline</h3>
                    <a href="{{ route('admin.application-status-logs.index', ['application_id' => $application->id]) }}" class="text-xs text-indigo-600 hover:underline">
                        View all logs
                    </a>
                </div>

                @if($application->statusLogs->isEmpty())
                    <p class="text-slate-400 text-sm">No status changes recorded yet.</p>
                @else
                    <div class="space-y-3">
                        @foreach($application->statusLogs->sortByDesc('created_at')->take(8) as $log)
                            <div class="border border-slate-100 rounded-lg p-3">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div class="text-sm font-medium text-slate-800">
                                        {{ $log->from_status ? (\App\Models\Application::STATUSES[$log->from_status] ?? $log->from_status) : 'Created' }}
                                        <span class="text-slate-400">→</span>
                                        {{ \App\Models\Application::STATUSES[$log->to_status] ?? $log->to_status }}
                                    </div>
                                    <a href="{{ route('admin.application-status-logs.show', $log) }}" class="text-xs text-blue-600 hover:underline">
                                        {{ $log->created_at->format('d M Y, H:i') }}
                                    </a>
                                </div>
                                <div class="text-xs text-slate-500 mt-1">
                                    {{ $log->changedBy->name ?? 'System' }}
                                </div>
                                @if($log->remarks)
                                    <div class="text-sm text-slate-600 mt-2 whitespace-pre-line">{{ $log->remarks }}</div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Admission</h3>
                    @if($application->admission)
                        <a href="{{ route('admin.admissions.show', $application->admission) }}" class="text-xs text-indigo-600 hover:underline">View admission</a>
                    @else
                        <a href="{{ route('admin.admissions.create', [
                            'application_id' => $application->id,
                            'student_id' => $application->student_id,
                            'institution_id' => $application->institution_id,
                            'institution_program_id' => $application->institution_program_id,
                        ]) }}" class="btn btn-primary btn-sm text-xs py-1 px-3">Create Admission</a>
                    @endif
                </div>

                @if($application->admission)
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div><dt class="text-slate-400 text-xs mb-1">Admission Number</dt><dd>{{ $application->admission->admission_number }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Admission Date</dt><dd>{{ $application->admission->admission_date?->format('d M Y') ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Paid Amount</dt><dd>{{ $application->admission->paid_amount !== null ? number_format((float) $application->admission->paid_amount, 2) : '-' }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Verification</dt><dd>{{ \App\Models\Admission::VERIFICATION_STATUSES[$application->admission->verification_status] ?? $application->admission->verification_status }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Verified By</dt><dd>{{ $application->admission->verifiedBy->name ?? '-' }}</dd></div>
                        <div><dt class="text-slate-400 text-xs mb-1">Verified At</dt><dd>{{ $application->admission->verified_at?->format('d M Y, H:i') ?? '-' }}</dd></div>
                    </dl>
                @else
                    <p class="text-slate-400 text-sm">No admission record has been created for this application.</p>
                @endif
            </div>

            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Referral</h3>
                    @if($application->referral)
                        <a href="{{ route('admin.referrals.show', $application->referral) }}" class="text-xs text-indigo-600 hover:underline">View referral</a>
                    @else
                        <a href="{{ route('admin.referrals.create', [
                            'application_id'  => $application->id,
                            'institution_id'  => $application->institution_id,
                        ]) }}" class="btn btn-primary btn-sm text-xs py-1 px-3">Create Referral</a>
                    @endif
                </div>

                @if($application->referral)
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Referral Number</dt>
                            <dd class="font-mono font-medium">
                                <a href="{{ route('admin.referrals.show', $application->referral) }}" class="text-blue-600 hover:underline">
                                    {{ $application->referral->referral_number }}
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Referred By</dt>
                            <dd>{{ $application->referral->referredBy->name ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Referred At</dt>
                            <dd>{{ $application->referral->referred_at?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Viewed At</dt>
                            <dd>{{ $application->referral->viewed_at?->format('d M Y, H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-slate-400 text-xs mb-1">Status</dt>
                            <dd><span class="badge">{{ \App\Models\Referral::STATUSES[$application->referral->status] ?? $application->referral->status }}</span></dd>
                        </div>
                    </dl>
                @else
                    <p class="text-slate-400 text-sm">No referral record has been created for this application.</p>
                @endif
            </div>

            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <form action="{{ route('admin.applications.destroy', $application) }}" method="POST" onsubmit="return confirm('Delete this application? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Application</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
