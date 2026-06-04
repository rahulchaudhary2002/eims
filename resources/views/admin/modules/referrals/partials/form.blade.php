@php
    $selectedInstitutionId = (string) old('institution_id', $referral->institution_id ?? $selectedInstitutionId ?? '');
    $selectedApplicationId = (string) old('application_id', $referral->application_id ?? $selectedApplicationId ?? '');
    $applicationOptions = $applications->map(function ($application) {
        $programName = $application->institutionProgram?->display_name
            ?? $application->institutionProgram?->title
            ?? $application->institutionProgram?->program?->name
            ?? 'Program not set';

        return [
            'id' => (string) $application->id,
            'institution_id' => (string) $application->institution_id,
            'application_number' => $application->application_number,
            'student_name' => $application->student?->name ?? 'Unknown Student',
            'program_name' => $programName,
            'label' => $application->application_number . ' - ' . ($application->student?->name ?? 'Unknown Student') . ' - ' . $programName,
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
    class="space-y-5"
>
    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
        <div class="space-y-2">
            <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
            <select id="institution_id" name="institution_id" x-model="institutionId" class="form-control @error('institution_id') is-invalid @enderror">
                <option value="">Select Institution</option>
                @foreach($institutions as $institution)
                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                @endforeach
            </select>
            @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label for="application_id" class="form-label">Application <span class="text-red-500">*</span></label>
            <select id="application_id" name="application_id" x-model="applicationId" class="form-control @error('application_id') is-invalid @enderror" :disabled="!institutionId">
                <option value="" x-text="institutionId ? 'Select Application' : 'Select Institution First'"></option>
                <template x-for="application in filteredApplications" :key="application.id">
                    <option :value="application.id" x-text="application.label"></option>
                </template>
            </select>
            @error('application_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label for="referral_number" class="form-label">Referral Number <span class="text-slate-400 text-xs">(auto-generated if blank)</span></label>
            <input type="text" id="referral_number" name="referral_number"
                value="{{ old('referral_number', $referral->referral_number ?? '') }}"
                class="form-control @error('referral_number') is-invalid @enderror"
                placeholder="e.g. REF-20260529-001">
            @error('referral_number') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
            <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $referral->status ?? 'pending') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label for="referred_at" class="form-label">Referred At</label>
            <input type="datetime-local" id="referred_at" name="referred_at"
                value="{{ old('referred_at', isset($referral) ? $referral->referred_at?->format('Y-m-d\TH:i') : '') }}"
                class="form-control @error('referred_at') is-invalid @enderror">
            @error('referred_at') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div class="space-y-2">
            <label for="viewed_at" class="form-label">Viewed At</label>
            <input type="datetime-local" id="viewed_at" name="viewed_at"
                value="{{ old('viewed_at', isset($referral) ? $referral->viewed_at?->format('Y-m-d\TH:i') : '') }}"
                class="form-control @error('viewed_at') is-invalid @enderror">
            @error('viewed_at') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
