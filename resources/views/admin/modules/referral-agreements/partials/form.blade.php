{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Select Institution</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $referralAgreement->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Commission Type --}}
<div>
    <label for="commission_type" class="form-label">Commission Type <span class="text-red-500">*</span></label>
    <select id="commission_type" name="commission_type" class="form-control @error('commission_type') is-invalid @enderror">
        <option value="">Select Type</option>
        @foreach($commissionTypes as $value => $label)
            <option value="{{ $value }}" {{ old('commission_type', $referralAgreement->commission_type ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('commission_type') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Commission Value --}}
<div>
    <label for="commission_value" class="form-label">Commission Value <span class="text-red-500">*</span></label>
    <input type="number" id="commission_value" name="commission_value" step="0.0001" min="0"
        value="{{ old('commission_value', $referralAgreement->commission_value ?? '') }}"
        class="form-control @error('commission_value') is-invalid @enderror"
        placeholder="e.g. 10.00 for 10% or flat amount">
    @error('commission_value') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Student Cashback Percentage --}}
<div>
    <label for="student_cashback_percentage" class="form-label">Student Cashback % <span class="text-red-500">*</span></label>
    <input type="number" id="student_cashback_percentage" name="student_cashback_percentage" step="0.0001" min="0" max="100"
        value="{{ old('student_cashback_percentage', $referralAgreement->student_cashback_percentage ?? '0') }}"
        class="form-control @error('student_cashback_percentage') is-invalid @enderror"
        placeholder="0 – 100">
    @error('student_cashback_percentage') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Platform Revenue Percentage --}}
<div>
    <label for="platform_revenue_percentage" class="form-label">Platform Revenue % <span class="text-red-500">*</span></label>
    <input type="number" id="platform_revenue_percentage" name="platform_revenue_percentage" step="0.0001" min="0" max="100"
        value="{{ old('platform_revenue_percentage', $referralAgreement->platform_revenue_percentage ?? '0') }}"
        class="form-control @error('platform_revenue_percentage') is-invalid @enderror"
        placeholder="0 – 100">
    @error('platform_revenue_percentage') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Start Date --}}
<div>
    <label for="start_date" class="form-label">Start Date</label>
    <input type="date" id="start_date" name="start_date"
        value="{{ old('start_date', isset($referralAgreement) ? $referralAgreement->start_date?->format('Y-m-d') : '') }}"
        class="form-control @error('start_date') is-invalid @enderror">
    @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- End Date --}}
<div>
    <label for="end_date" class="form-label">End Date</label>
    <input type="date" id="end_date" name="end_date"
        value="{{ old('end_date', isset($referralAgreement) ? $referralAgreement->end_date?->format('Y-m-d') : '') }}"
        class="form-control @error('end_date') is-invalid @enderror">
    @error('end_date') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Agreement File --}}
<div>
    <label for="agreement_file" class="form-label">Agreement File <span class="text-slate-400 text-xs">(PDF/DOC/DOCX, max 10 MB)</span></label>
    @if(isset($referralAgreement) && $referralAgreement->agreement_file)
        <p class="text-xs text-slate-500 mb-1">
            Current:
            <a href="{{ Storage::url($referralAgreement->agreement_file) }}" target="_blank" class="text-blue-600 hover:underline">
                {{ basename($referralAgreement->agreement_file) }}
            </a>
            - upload a new file to replace it.
        </p>
    @endif
    <input type="file" id="agreement_file" name="agreement_file" accept=".pdf,.doc,.docx"
        class="form-control @error('agreement_file') is-invalid @enderror">
    @error('agreement_file') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
        @foreach($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $referralAgreement->status ?? 'draft') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status') <p class="form-error">{{ $message }}</p> @enderror
</div>
