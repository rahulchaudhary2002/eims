{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Select Institution</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $institutionSubscription->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Subscription Plan --}}
<div>
    <label for="subscription_plan_id" class="form-label">Subscription Plan <span class="text-red-500">*</span></label>
    <select id="subscription_plan_id" name="subscription_plan_id" class="form-control @error('subscription_plan_id') is-invalid @enderror">
        <option value="">Select Plan</option>
        @foreach($plans as $plan)
            <option value="{{ $plan->id }}"
                {{ old('subscription_plan_id', $institutionSubscription->subscription_plan_id ?? $selectedPlanId ?? '') == $plan->id ? 'selected' : '' }}>
                {{ $plan->name }}
                @if(isset($plan->price_monthly))
                    ({{ number_format((float) $plan->price_monthly, 2) }}/mo · {{ number_format((float) $plan->price_yearly, 2) }}/yr)
                @endif
            </option>
        @endforeach
    </select>
    @error('subscription_plan_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Billing Cycle --}}
<div>
    <label for="billing_cycle" class="form-label">Billing Cycle <span class="text-red-500">*</span></label>
    <select id="billing_cycle" name="billing_cycle" class="form-control @error('billing_cycle') is-invalid @enderror">
        @foreach($billingCycles as $value => $label)
            <option value="{{ $value }}" {{ old('billing_cycle', $institutionSubscription->billing_cycle ?? 'monthly') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('billing_cycle') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
        @foreach($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $institutionSubscription->status ?? 'active') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Amount --}}
<div>
    <label for="amount" class="form-label">Amount <span class="text-red-500">*</span></label>
    <input type="number" id="amount" name="amount" step="0.0001" min="0"
        value="{{ old('amount', $institutionSubscription->amount ?? '0') }}"
        class="form-control @error('amount') is-invalid @enderror"
        placeholder="0.00">
    @error('amount') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Starts At --}}
<div>
    <label for="starts_at" class="form-label">Starts At <span class="text-red-500">*</span></label>
    <input type="date" id="starts_at" name="starts_at"
        value="{{ old('starts_at', isset($institutionSubscription) ? $institutionSubscription->starts_at?->format('Y-m-d') : '') }}"
        class="form-control @error('starts_at') is-invalid @enderror">
    @error('starts_at') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Ends At --}}
<div>
    <label for="ends_at" class="form-label">Ends At <span class="text-slate-400 text-xs">(leave blank for ongoing)</span></label>
    <input type="date" id="ends_at" name="ends_at"
        value="{{ old('ends_at', isset($institutionSubscription) ? $institutionSubscription->ends_at?->format('Y-m-d') : '') }}"
        class="form-control @error('ends_at') is-invalid @enderror">
    @error('ends_at') <p class="form-error">{{ $message }}</p> @enderror
</div>
