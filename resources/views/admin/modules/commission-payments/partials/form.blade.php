{{-- Commission Invoice --}}
<div>
    <label for="commission_invoice_id" class="form-label">Commission Invoice <span class="text-red-500">*</span></label>
    <select id="commission_invoice_id" name="commission_invoice_id" class="form-control @error('commission_invoice_id') is-invalid @enderror">
        <option value="">Select Invoice</option>
        @foreach($invoices as $invoice)
            <option value="{{ $invoice->id }}"
                {{ old('commission_invoice_id', $commissionPayment->commission_invoice_id ?? $selectedInvoiceId ?? '') == $invoice->id ? 'selected' : '' }}>
                {{ $invoice->invoice_number }}
            </option>
        @endforeach
    </select>
    @error('commission_invoice_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Amount --}}
<div>
    <label for="amount" class="form-label">Amount <span class="text-red-500">*</span></label>
    <input type="number" id="amount" name="amount" step="0.0001" min="0.01"
        value="{{ old('amount', $commissionPayment->amount ?? '') }}"
        class="form-control @error('amount') is-invalid @enderror"
        placeholder="0.00">
    @error('amount') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Payment Method --}}
<div>
    <label for="payment_method" class="form-label">Payment Method <span class="text-red-500">*</span></label>
    <select id="payment_method" name="payment_method" class="form-control @error('payment_method') is-invalid @enderror">
        <option value="">Select Method</option>
        @foreach($paymentMethods as $value => $label)
            <option value="{{ $value }}" {{ old('payment_method', $commissionPayment->payment_method ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('payment_method') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Payment Date --}}
<div>
    <label for="payment_date" class="form-label">Payment Date <span class="text-red-500">*</span></label>
    <input type="date" id="payment_date" name="payment_date"
        value="{{ old('payment_date', isset($commissionPayment) ? $commissionPayment->payment_date?->format('Y-m-d') : '') }}"
        class="form-control @error('payment_date') is-invalid @enderror">
    @error('payment_date') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Transaction Reference --}}
<div>
    <label for="transaction_reference" class="form-label">Transaction Reference</label>
    <input type="text" id="transaction_reference" name="transaction_reference"
        value="{{ old('transaction_reference', $commissionPayment->transaction_reference ?? '') }}"
        class="form-control @error('transaction_reference') is-invalid @enderror"
        placeholder="e.g. TXN-123456">
    @error('transaction_reference') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Payment Proof --}}
<div>
    <label for="payment_proof" class="form-label">Payment Proof <span class="text-slate-400 text-xs">(PDF/JPG/PNG, max 10 MB)</span></label>
    @if(isset($commissionPayment) && $commissionPayment->payment_proof)
        <p class="text-xs text-slate-500 mb-1">
            Current:
            <a href="{{ Storage::url($commissionPayment->payment_proof) }}" target="_blank" class="text-blue-600 hover:underline">
                {{ basename($commissionPayment->payment_proof) }}
            </a>
            - upload a new file to replace it.
        </p>
    @endif
    <input type="file" id="payment_proof" name="payment_proof" accept=".pdf,.jpg,.jpeg,.png"
        class="form-control @error('payment_proof') is-invalid @enderror">
    @error('payment_proof') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Remarks --}}
<div class="md:col-span-2">
    <label for="remarks" class="form-label">Remarks</label>
    <textarea id="remarks" name="remarks" rows="3"
        class="form-control @error('remarks') is-invalid @enderror"
        placeholder="Optional notes about this payment">{{ old('remarks', $commissionPayment->remarks ?? '') }}</textarea>
    @error('remarks') <p class="form-error">{{ $message }}</p> @enderror
</div>
