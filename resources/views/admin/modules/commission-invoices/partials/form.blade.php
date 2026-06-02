{{-- Invoice Number --}}
<div>
    <label for="invoice_number" class="form-label">Invoice Number <span class="text-slate-400 text-xs">(auto-generated if blank)</span></label>
    <input type="text" id="invoice_number" name="invoice_number"
        value="{{ old('invoice_number', $commissionInvoice->invoice_number ?? '') }}"
        class="form-control @error('invoice_number') is-invalid @enderror"
        placeholder="e.g. INV-20260529-001">
    @error('invoice_number') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Select Institution</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $commissionInvoice->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Admission --}}
<div>
    <label for="admission_id" class="form-label">Admission</label>
    <select id="admission_id" name="admission_id" class="form-control @error('admission_id') is-invalid @enderror">
        <option value="">None</option>
        @foreach($admissions as $admission)
            <option value="{{ $admission->id }}"
                {{ old('admission_id', $commissionInvoice->admission_id ?? $selectedAdmissionId ?? '') == $admission->id ? 'selected' : '' }}>
                {{ $admission->admission_number }}
            </option>
        @endforeach
    </select>
    @error('admission_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Referral Agreement --}}
<div>
    <label for="referral_agreement_id" class="form-label">Referral Agreement</label>
    <select id="referral_agreement_id" name="referral_agreement_id" class="form-control @error('referral_agreement_id') is-invalid @enderror">
        <option value="">None</option>
        @foreach($referralAgreements as $agreement)
            <option value="{{ $agreement->id }}"
                {{ old('referral_agreement_id', $commissionInvoice->referral_agreement_id ?? $selectedAgreementId ?? '') == $agreement->id ? 'selected' : '' }}>
                #{{ $agreement->id }} - {{ \App\Models\CommissionInvoice::COMMISSION_TYPES[$agreement->commission_type] ?? $agreement->commission_type }}
            </option>
        @endforeach
    </select>
    @error('referral_agreement_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Commission Type --}}
<div>
    <label for="commission_type" class="form-label">Commission Type <span class="text-red-500">*</span></label>
    <select id="commission_type" name="commission_type" class="form-control @error('commission_type') is-invalid @enderror">
        <option value="">Select Type</option>
        @foreach($commissionTypes as $value => $label)
            <option value="{{ $value }}" {{ old('commission_type', $commissionInvoice->commission_type ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('commission_type') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Admission Paid Amount --}}
<div>
    <label for="admission_paid_amount" class="form-label">Admission Paid Amount <span class="text-red-500">*</span></label>
    <input type="number" id="admission_paid_amount" name="admission_paid_amount" step="0.0001" min="0"
        value="{{ old('admission_paid_amount', $commissionInvoice->admission_paid_amount ?? '0') }}"
        class="form-control @error('admission_paid_amount') is-invalid @enderror">
    @error('admission_paid_amount') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Commission Value --}}
<div>
    <label for="commission_value" class="form-label">Commission Value <span class="text-red-500">*</span></label>
    <input type="number" id="commission_value" name="commission_value" step="0.0001" min="0"
        value="{{ old('commission_value', $commissionInvoice->commission_value ?? '0') }}"
        class="form-control @error('commission_value') is-invalid @enderror"
        placeholder="Rate or fixed amount">
    @error('commission_value') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Commission Amount --}}
<div>
    <label for="commission_amount" class="form-label">Commission Amount <span class="text-red-500">*</span></label>
    <input type="number" id="commission_amount" name="commission_amount" step="0.0001" min="0"
        value="{{ old('commission_amount', $commissionInvoice->commission_amount ?? '0') }}"
        class="form-control @error('commission_amount') is-invalid @enderror">
    @error('commission_amount') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Student Cashback Amount --}}
<div>
    <label for="student_cashback_amount" class="form-label">Student Cashback Amount <span class="text-red-500">*</span></label>
    <input type="number" id="student_cashback_amount" name="student_cashback_amount" step="0.0001" min="0"
        value="{{ old('student_cashback_amount', $commissionInvoice->student_cashback_amount ?? '0') }}"
        class="form-control @error('student_cashback_amount') is-invalid @enderror">
    @error('student_cashback_amount') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
        @foreach($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $commissionInvoice->status ?? 'draft') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Invoice Date --}}
<div>
    <label for="invoice_date" class="form-label">Invoice Date</label>
    <input type="date" id="invoice_date" name="invoice_date"
        value="{{ old('invoice_date', isset($commissionInvoice) ? $commissionInvoice->invoice_date?->format('Y-m-d') : '') }}"
        class="form-control @error('invoice_date') is-invalid @enderror">
    @error('invoice_date') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Due Date --}}
<div>
    <label for="due_date" class="form-label">Due Date</label>
    <input type="date" id="due_date" name="due_date"
        value="{{ old('due_date', isset($commissionInvoice) ? $commissionInvoice->due_date?->format('Y-m-d') : '') }}"
        class="form-control @error('due_date') is-invalid @enderror">
    @error('due_date') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Paid At --}}
<div>
    <label for="paid_at" class="form-label">Paid At</label>
    <input type="datetime-local" id="paid_at" name="paid_at"
        value="{{ old('paid_at', isset($commissionInvoice) ? $commissionInvoice->paid_at?->format('Y-m-d\TH:i') : '') }}"
        class="form-control @error('paid_at') is-invalid @enderror">
    @error('paid_at') <p class="form-error">{{ $message }}</p> @enderror
</div>
