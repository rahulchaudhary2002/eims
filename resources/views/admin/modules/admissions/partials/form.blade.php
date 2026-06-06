@php
    $selectedInstitutionId = (string) old('institution_id', $admission->institution_id ?? $selectedInstitutionId ?? '');
    $selectedApplicationId = (string) old('application_id', $admission->application_id ?? $selectedApplicationId ?? '');
    $applicationOptions = $applications->map(function ($application) {
        $programName = $application->applicable_label ?? 'Item not set';

        return [
            'id' => (string) $application->id,
            'institution_id' => (string) $application->institution_id,
            'label' => $application->application_number . ' - ' . ($application->student?->name ?? 'Student') . ' - ' . $programName,
        ];
    })->values();
@endphp

<div
    x-data="{
        institutionId: @js($selectedInstitutionId),
        applicationId: @js($selectedApplicationId),
        applications: @js($applicationOptions),
        get filteredApplications() {
            if (! this.institutionId) {
                return [];
            }

            return this.applications.filter((application) => application.institution_id === this.institutionId);
        }
    }"
    x-effect="if (applicationId && ! filteredApplications.some((application) => application.id === applicationId)) { applicationId = ''; }"
    class="grid grid-cols-1 gap-5 md:grid-cols-2"
>
    <div class="space-y-2">
        <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
        <select name="institution_id" id="institution_id" x-model="institutionId" class="form-control @error('institution_id') border-red-400 @enderror">
            <option value="">Select Institution</option>
            @foreach($institutions as $institution)
                <option value="{{ $institution->id }}">{{ $institution->name }}</option>
            @endforeach
        </select>
        @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="application_id" class="form-label">Application <span class="text-red-500">*</span></label>
        <select name="application_id" id="application_id" x-model="applicationId" class="form-control @error('application_id') border-red-400 @enderror" :disabled="!institutionId">
            <option value="" x-text="institutionId ? 'Select Application' : 'Select Institution First'"></option>
            <template x-for="application in filteredApplications" :key="application.id">
                <option :value="application.id" x-text="application.label"></option>
            </template>
        </select>
        @error('application_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="admission_number" class="form-label">Admission Number</label>
        <input type="text" name="admission_number" id="admission_number" value="{{ old('admission_number', $admission->admission_number ?? '') }}" class="form-control @error('admission_number') border-red-400 @enderror" placeholder="Auto-generated if blank">
        @error('admission_number') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="admission_date" class="form-label">Admission Date <span class="text-red-500">*</span></label>
        <input type="date" name="admission_date" id="admission_date" value="{{ old('admission_date', $admission?->admission_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="form-control @error('admission_date') border-red-400 @enderror">
        @error('admission_date') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="paid_amount" class="form-label">Paid Amount</label>
        <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $admission->paid_amount ?? '') }}" class="form-control @error('paid_amount') border-red-400 @enderror">
        @error('paid_amount') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="payment_proof" class="form-label">Payment Proof</label>
        <input type="file" name="payment_proof" id="payment_proof" class="form-control @error('payment_proof') border-red-400 @enderror">
        @if(!empty(storage_exists($admission?->payment_proof)))
            <a href="{{ storage_url($admission->payment_proof) }}" target="_blank" rel="noopener" class="text-xs text-blue-600 hover:underline">View current proof</a>
        @endif
        @error('payment_proof') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="verification_status" class="form-label">Verification Status <span class="text-red-500">*</span></label>
        <select name="verification_status" id="verification_status" class="form-control @error('verification_status') border-red-400 @enderror">
            @foreach($verificationStatuses as $value => $label)
                <option value="{{ $value }}" {{ old('verification_status', $admission->verification_status ?? 'pending') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('verification_status') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2">
        <label for="verified_at" class="form-label">Verified At</label>
        <input type="datetime-local" name="verified_at" id="verified_at" value="{{ old('verified_at', $admission?->verified_at?->format('Y-m-d\TH:i') ?? '') }}" class="form-control @error('verified_at') border-red-400 @enderror">
        @error('verified_at') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="space-y-2 md:col-span-2">
        <label for="remarks" class="form-label">Remarks</label>
        <textarea name="remarks" id="remarks" rows="4" class="form-control @error('remarks') border-red-400 @enderror">{{ old('remarks', $admission->remarks ?? '') }}</textarea>
        @error('remarks') <p class="form-error">{{ $message }}</p> @enderror
    </div>
</div>
