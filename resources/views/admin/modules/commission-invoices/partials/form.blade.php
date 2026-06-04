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
                data-institution="{{ $admission->institution_id }}"
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
            @php
                $typeLabel = \App\Models\ReferralAgreement::COMMISSION_TYPES[$agreement->commission_type] ?? $agreement->commission_type;
                $statusLabel = \App\Models\ReferralAgreement::STATUSES[$agreement->status] ?? $agreement->status;
                $valueDisplay = $agreement->commission_type === 'percentage'
                    ? number_format($agreement->commission_value, 2) . '%'
                    : '$' . number_format($agreement->commission_value, 2);
            @endphp
            <option value="{{ $agreement->id }}"
                data-institution="{{ $agreement->institution_id }}"
                data-commission-type="{{ $agreement->commission_type }}"
                data-commission-value="{{ $agreement->commission_value }}"
                data-cashback-percentage="{{ $agreement->student_cashback_percentage }}"
                data-status="{{ $agreement->status }}"
                {{ old('referral_agreement_id', $commissionInvoice->referral_agreement_id ?? $selectedAgreementId ?? '') == $agreement->id ? 'selected' : '' }}>
                Agreement #{{ $agreement->id }} - {{ $typeLabel }} {{ $valueDisplay }} | Cashback {{ number_format($agreement->student_cashback_percentage, 2) }}% | {{ $statusLabel }}
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

@once
<script>
(function () {
    const institutionSel   = document.getElementById('institution_id');
    const admissionSel     = document.getElementById('admission_id');
    const agreementSel     = document.getElementById('referral_agreement_id');
    const commissionTypeSel = document.getElementById('commission_type');
    const paidAmountInput  = document.getElementById('admission_paid_amount');
    const commValueInput   = document.getElementById('commission_value');
    const commAmountInput  = document.getElementById('commission_amount');
    const cashbackInput    = document.getElementById('student_cashback_amount');

    // Store original options so we can re-filter without re-rendering
    const allAdmissionOptions  = Array.from(admissionSel.options).slice(1); // skip "None"
    const allAgreementOptions  = Array.from(agreementSel.options).slice(1); // skip "None"

    function filterByInstitution(institutionId) {
        // Filter admissions
        const currentAdmission = admissionSel.value;
        while (admissionSel.options.length > 1) admissionSel.remove(1);
        allAdmissionOptions.forEach(opt => {
            if (!institutionId || opt.dataset.institution === institutionId) {
                admissionSel.appendChild(opt.cloneNode(true));
            }
        });
        admissionSel.value = currentAdmission;

        // Filter referral agreements
        const currentAgreement = agreementSel.value;
        while (agreementSel.options.length > 1) agreementSel.remove(1);
        allAgreementOptions.forEach(opt => {
            if (!institutionId || opt.dataset.institution === institutionId) {
                agreementSel.appendChild(opt.cloneNode(true));
            }
        });
        // If the previously selected agreement is no longer in the filtered list, clear it
        if (!Array.from(agreementSel.options).some(o => o.value === currentAgreement)) {
            agreementSel.value = '';
        } else {
            agreementSel.value = currentAgreement;
        }
    }

    function setAgreementLocked(locked) {
        commissionTypeSel.readOnly = locked;
        commissionTypeSel.classList.toggle('bg-slate-100', locked);
        commissionTypeSel.style.pointerEvents = locked ? 'none' : '';

        commValueInput.readOnly = locked;
        commValueInput.classList.toggle('bg-slate-100', locked);

        commAmountInput.readOnly = locked;
        commAmountInput.classList.toggle('bg-slate-100', locked);

        cashbackInput.readOnly = locked;
        cashbackInput.classList.toggle('bg-slate-100', locked);
    }

    function recalculate() {
        const selectedOpt = agreementSel.options[agreementSel.selectedIndex];
        if (!selectedOpt || !selectedOpt.value) return;

        const commType    = selectedOpt.dataset.commissionType;
        const commValue   = parseFloat(selectedOpt.dataset.commissionValue) || 0;
        const cashbackPct = parseFloat(selectedOpt.dataset.cashbackPercentage) || 0;
        const paidAmount  = parseFloat(paidAmountInput.value) || 0;

        let commAmount = 0;
        if (commType === 'percentage') {
            commAmount = paidAmount * commValue / 100;
        } else if (commType === 'flat_fee') {
            commAmount = commValue;
        } else {
            commAmount = commValue; // tiered: use value as-is
        }

        commAmountInput.value = commAmount.toFixed(4);
        cashbackInput.value   = (paidAmount * cashbackPct / 100).toFixed(4);
    }

    function applyAgreement() {
        const selectedOpt = agreementSel.options[agreementSel.selectedIndex];
        const hasAgreement = selectedOpt && selectedOpt.value;

        if (hasAgreement) {
            commissionTypeSel.value = selectedOpt.dataset.commissionType || '';
            commValueInput.value    = selectedOpt.dataset.commissionValue || '0';
            setAgreementLocked(true);
            recalculate();
        } else {
            setAgreementLocked(false);
        }
    }

    institutionSel.addEventListener('change', function () {
        filterByInstitution(this.value);
        applyAgreement();
    });

    agreementSel.addEventListener('change', applyAgreement);
    paidAmountInput.addEventListener('input', recalculate);
    commValueInput.addEventListener('input', recalculate);
    commissionTypeSel.addEventListener('change', recalculate);

    // Apply initial state on page load (edit / validation-failed repopulation)
    filterByInstitution(institutionSel.value);
    applyAgreement();
})();
</script>
@endonce
