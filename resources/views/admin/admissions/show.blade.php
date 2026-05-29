@extends('admin.layouts.app')
@section('title', $admission->admission_number)
@section('page-title', 'Admission Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        :title="$admission->admission_number"
        :subtitle="$admission->student->name ?? 'Admission'"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Admissions','route'=>'admin.admissions.index'],
            ['label'=>$admission->admission_number],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.admissions.edit', $admission) }}" class="btn btn-primary">Edit</a>
            <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <div class="space-y-5">
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Verification</h3>
                <form action="{{ route('admin.admissions.verify', $admission) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="verification_status" class="form-control">
                        @foreach(\App\Models\Admission::VERIFICATION_STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $admission->verification_status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <textarea name="remarks" rows="3" class="form-control" placeholder="Verification remarks">{{ $admission->remarks }}</textarea>
                    <button type="submit" class="btn btn-primary w-full">Update Verification</button>
                </form>
            </div>

            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Payment</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between gap-3"><dt class="text-slate-500">Paid Amount</dt><dd>{{ $admission->paid_amount !== null ? number_format((float) $admission->paid_amount, 2) : '-' }}</dd></div>
                    <div class="flex justify-between gap-3">
                        <dt class="text-slate-500">Proof</dt>
                        <dd>
                            @if($admission->payment_proof)
                                <a href="{{ Storage::url($admission->payment_proof) }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline">View</a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Details</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-slate-400 text-xs mb-1">Application</dt><dd><a href="{{ route('admin.applications.show', $admission->application) }}" class="text-blue-600 hover:underline">{{ $admission->application->application_number ?? '-' }}</a></dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Student</dt><dd><a href="{{ route('admin.students.show', $admission->student) }}" class="text-blue-600 hover:underline">{{ $admission->student->name ?? '-' }}</a></dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Institution</dt><dd><a href="{{ route('admin.institutions.show', $admission->institution) }}" class="text-blue-600 hover:underline">{{ $admission->institution->name ?? '-' }}</a></dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Institution Program</dt><dd><a href="{{ route('admin.institution-programs.show', $admission->institutionProgram) }}" class="text-blue-600 hover:underline">{{ $admission->institutionProgram->title ?: ($admission->institutionProgram->program->name ?? 'Program') }}</a></dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Admission Date</dt><dd>{{ $admission->admission_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Verification Status</dt><dd>{{ \App\Models\Admission::VERIFICATION_STATUSES[$admission->verification_status] ?? $admission->verification_status }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Verified By</dt><dd>{{ $admission->verifiedBy->name ?? '-' }}</dd></div>
                    <div><dt class="text-slate-400 text-xs mb-1">Verified At</dt><dd>{{ $admission->verified_at?->format('d M Y, H:i') ?? '-' }}</dd></div>
                </dl>
            </div>

            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Remarks</h3>
                <div class="text-sm text-slate-700 whitespace-pre-line">{{ $admission->remarks ?: '-' }}</div>
            </div>

            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <form action="{{ route('admin.admissions.destroy', $admission) }}" method="POST" onsubmit="return confirm('Delete this admission? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Admission</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
