@extends('admin.layouts.app')
@section('title', 'Create Application Referral')
@section('page-title', 'Create Application Referral')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Send Application Referral"
        subtitle="Refer a student application to an institution."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Application Referrals','route' => 'admin.application-referrals.index'],
            ['label'=>'Create'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.application-referrals.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Application Info (read-only) --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Application Details</h3>
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Application Number</dt>
                        <dd class="font-semibold text-blue-600">{{ $application->application_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Student</dt>
                        <dd>{{ $application->student->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Institution</dt>
                        <dd>{{ $application->institution->name ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">{{ \App\Models\Application::APPLICABLE_TYPES[$application->applicable_type] ?? 'Applicable' }}</dt>
                        <dd>{{ $application->applicable_label }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Application Status</dt>
                        <dd><span class="badge">{{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}</span></dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Submitted At</dt>
                        <dd>{{ $application->submitted_at?->format('d M Y, H:i') ?? '-' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="eims-card p-6">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide mb-4">Referral Options</h3>
                <form action="{{ route('admin.application-referrals.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <input type="hidden" name="application_id" value="{{ $application->id }}">
                    <input type="hidden" name="student_id" value="{{ $application->student_id }}">
                    <input type="hidden" name="institution_id" value="{{ $application->institution_id }}">

                    <div>
                        <label class="form-label">Referral Agreement <span class="text-slate-400 font-normal">(optional)</span></label>
                        <select name="referral_agreement_id" class="form-control">
                            <option value="">No specific agreement</option>
                            @foreach($referralAgreements ?? [] as $agreement)
                                <option value="{{ $agreement->id }}" {{ old('referral_agreement_id') == $agreement->id ? 'selected' : '' }}>
                                    {{ $agreement->title ?? 'Agreement #' . $agreement->id }}
                                </option>
                            @endforeach
                        </select>
                        @error('referral_agreement_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label">Platform Note <span class="text-slate-400 font-normal">(optional, internal only)</span></label>
                        <textarea name="platform_note" rows="4" class="form-control" placeholder="Internal notes about this referral...">{{ old('platform_note') }}</textarea>
                        @error('platform_note')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/></svg>
                            Send Referral to Institution
                        </button>
                        <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Info panel --}}
        <div class="space-y-5">
            <div class="eims-card p-6 bg-blue-50 border border-blue-100">
                <h3 class="font-semibold text-blue-800 text-sm uppercase tracking-wide mb-3">How Referrals Work</h3>
                <ul class="text-sm text-blue-700 space-y-2">
                    <li class="flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">•</span>
                        <span>The institution will receive this referral and can view a masked student preview.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">•</span>
                        <span>If they unlock the profile, a protection period begins based on the referral agreement.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="text-blue-400 mt-0.5">•</span>
                        <span>If the student is admitted during the protection period, commission may be payable.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
