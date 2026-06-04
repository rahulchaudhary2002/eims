{{-- Student --}}
<div>
    <label for="student_id" class="form-label">Student <span class="text-red-500">*</span></label>
    <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror">
        <option value="">Select Student</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}"
                {{ old('student_id', $scholarshipCashback->student_id ?? $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
                {{ $student->name }} - {{ $student->email }}
            </option>
        @endforeach
    </select>
    @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Application --}}
<div>
    <label for="application_id" class="form-label">Application <span class="text-slate-400 text-xs">(optional)</span></label>
    <select id="application_id" name="application_id" class="form-control @error('application_id') is-invalid @enderror">
        <option value="">None</option>
        @foreach($applications as $application)
            <option value="{{ $application->id }}"
                {{ old('application_id', $scholarshipCashback->application_id ?? $selectedApplicationId ?? '') == $application->id ? 'selected' : '' }}>
                {{ $application->application_number }}
            </option>
        @endforeach
    </select>
    @error('application_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Commission Invoice --}}
<div>
    <label for="commission_invoice_id" class="form-label">Commission Invoice <span class="text-slate-400 text-xs">(optional)</span></label>
    <select id="commission_invoice_id" name="commission_invoice_id" class="form-control @error('commission_invoice_id') is-invalid @enderror">
        <option value="">None</option>
        @foreach($invoices as $invoice)
            <option value="{{ $invoice->id }}"
                data-commission-amount="{{ $invoiceData[$invoice->id]['commission_amount'] ?? 0 }}"
                data-cashback-amount="{{ $invoiceData[$invoice->id]['student_cashback_amount'] ?? 0 }}"
                data-cashback-percentage="{{ $invoiceData[$invoice->id]['cashback_percentage'] ?? 0 }}"
                {{ old('commission_invoice_id', $scholarshipCashback->commission_invoice_id ?? $selectedInvoiceId ?? '') == $invoice->id ? 'selected' : '' }}>
                {{ $invoice->invoice_number }}
            </option>
        @endforeach
    </select>
    @error('commission_invoice_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
        @foreach($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $scholarshipCashback->status ?? 'pending') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Commission Received Amount --}}
<div>
    <label for="commission_received_amount" class="form-label">Commission Received Amount <span class="text-red-500">*</span></label>
    <input type="number" id="commission_received_amount" name="commission_received_amount" step="0.0001" min="0"
        value="{{ old('commission_received_amount', $scholarshipCashback->commission_received_amount ?? '0') }}"
        class="form-control @error('commission_received_amount') is-invalid @enderror">
    @error('commission_received_amount') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Cashback Percentage --}}
<div>
    <label for="cashback_percentage" class="form-label">Cashback Percentage <span class="text-red-500">*</span></label>
    <input type="number" id="cashback_percentage" name="cashback_percentage" step="0.0001" min="0" max="100"
        value="{{ old('cashback_percentage', $scholarshipCashback->cashback_percentage ?? '0') }}"
        class="form-control @error('cashback_percentage') is-invalid @enderror"
        placeholder="0 – 100">
    @error('cashback_percentage') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Cashback Amount --}}
<div>
    <label for="cashback_amount" class="form-label">Cashback Amount <span class="text-red-500">*</span></label>
    <input type="number" id="cashback_amount" name="cashback_amount" step="0.0001" min="0"
        value="{{ old('cashback_amount', $scholarshipCashback->cashback_amount ?? '0') }}"
        class="form-control @error('cashback_amount') is-invalid @enderror">
    @error('cashback_amount') <p class="form-error">{{ $message }}</p> @enderror
</div>

<script>
(function () {
    const invoiceSel       = document.getElementById('commission_invoice_id');
    const commReceivedInput = document.getElementById('commission_received_amount');
    const cashbackPctInput  = document.getElementById('cashback_percentage');
    const cashbackAmtInput  = document.getElementById('cashback_amount');

    function setLocked(locked) {
        [commReceivedInput, cashbackPctInput, cashbackAmtInput].forEach(el => {
            el.readOnly = locked;
            el.classList.toggle('bg-slate-100', locked);
        });
    }

    function applyInvoice() {
        const opt = invoiceSel.options[invoiceSel.selectedIndex];
        if (!opt || !opt.value) {
            setLocked(false);
            return;
        }

        commReceivedInput.value = (parseFloat(opt.dataset.commissionAmount) || 0).toFixed(4);
        cashbackPctInput.value  = (parseFloat(opt.dataset.cashbackPercentage) || 0).toFixed(4);
        cashbackAmtInput.value  = (parseFloat(opt.dataset.cashbackAmount) || 0).toFixed(4);
        setLocked(true);
    }

    invoiceSel.addEventListener('change', applyInvoice);

    if (invoiceSel.value) applyInvoice();
})();
</script>

{{-- Payment Method --}}
<div>
    <label for="payment_method" class="form-label">Payment Method</label>
    <select id="payment_method" name="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
        <option value="">Not set</option>
        @foreach($paymentMethods as $value => $label)
            <option value="{{ $value }}" {{ old('payment_method', $scholarshipCashback->payment_method ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('payment_method') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Transaction Reference --}}
<div>
    <label for="transaction_reference" class="form-label">Transaction Reference</label>
    <input type="text" id="transaction_reference" name="transaction_reference"
        value="{{ old('transaction_reference', $scholarshipCashback->transaction_reference ?? '') }}"
        class="form-control @error('transaction_reference') is-invalid @enderror"
        placeholder="e.g. TXN-123456">
    @error('transaction_reference') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Paid At --}}
<div>
    <label for="paid_at" class="form-label">Paid At</label>
    <input type="datetime-local" id="paid_at" name="paid_at"
        value="{{ old('paid_at', isset($scholarshipCashback) ? $scholarshipCashback->paid_at?->format('Y-m-d\TH:i') : '') }}"
        class="form-control @error('paid_at') is-invalid @enderror">
    @error('paid_at') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Remarks --}}
<div class="md:col-span-2">
    <label for="remarks" class="form-label">Remarks</label>
    <textarea id="remarks" name="remarks" rows="3"
        class="form-control @error('remarks') is-invalid @enderror"
        placeholder="Optional notes about this cashback">{{ old('remarks', $scholarshipCashback->remarks ?? '') }}</textarea>
    @error('remarks') <p class="form-error">{{ $message }}</p> @enderror
</div>
