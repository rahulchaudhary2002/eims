{{-- Referral Number --}}
<div>
    <label for="referral_number" class="form-label">Referral Number <span class="text-slate-400 text-xs">(auto-generated if blank)</span></label>
    <input type="text" id="referral_number" name="referral_number"
        value="{{ old('referral_number', $referral->referral_number ?? '') }}"
        class="form-control @error('referral_number') is-invalid @enderror"
        placeholder="e.g. REF-20260529-001">
    @error('referral_number') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Select Institution</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $referral->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Application --}}
<div>
    <label for="application_id" class="form-label">Application <span class="text-red-500">*</span></label>
    <select id="application_id" name="application_id" class="form-control @error('application_id') is-invalid @enderror">
        <option value="">Select Application</option>
        @foreach($applications as $application)
            <option value="{{ $application->id }}"
                {{ old('application_id', $referral->application_id ?? $selectedApplicationId ?? '') == $application->id ? 'selected' : '' }}>
                {{ $application->application_number }}
            </option>
        @endforeach
    </select>
    @error('application_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Student --}}
<div>
    <label for="student_id" class="form-label">Student <span class="text-red-500">*</span></label>
    <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror">
        <option value="">Select Student</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}"
                {{ old('student_id', $referral->student_id ?? '') == $student->id ? 'selected' : '' }}>
                {{ $student->name }} - {{ $student->email }}
            </option>
        @endforeach
    </select>
    @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Referred By --}}
<div>
    <label for="referred_by" class="form-label">Referred By <span class="text-red-500">*</span></label>
    <select id="referred_by" name="referred_by" class="form-control @error('referred_by') is-invalid @enderror">
        <option value="">Select User</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}"
                {{ old('referred_by', $referral->referred_by ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} - {{ $user->email }}
            </option>
        @endforeach
    </select>
    @error('referred_by') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
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

{{-- Referred At --}}
<div>
    <label for="referred_at" class="form-label">Referred At</label>
    <input type="datetime-local" id="referred_at" name="referred_at"
        value="{{ old('referred_at', isset($referral) ? $referral->referred_at?->format('Y-m-d\TH:i') : '') }}"
        class="form-control @error('referred_at') is-invalid @enderror">
    @error('referred_at') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Viewed At --}}
<div>
    <label for="viewed_at" class="form-label">Viewed At</label>
    <input type="datetime-local" id="viewed_at" name="viewed_at"
        value="{{ old('viewed_at', isset($referral) ? $referral->viewed_at?->format('Y-m-d\TH:i') : '') }}"
        class="form-control @error('viewed_at') is-invalid @enderror">
    @error('viewed_at') <p class="form-error">{{ $message }}</p> @enderror
</div>
