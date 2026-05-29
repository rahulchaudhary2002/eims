{{-- Name --}}
<div>
    <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
    <input type="text" id="name" name="name"
        value="{{ old('name', $subscriptionPlan->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror"
        placeholder="e.g. Starter, Professional, Enterprise">
    @error('name') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Slug --}}
<div>
    <label for="slug" class="form-label">Slug <span class="text-slate-400 text-xs">(auto-generated if blank)</span></label>
    <input type="text" id="slug" name="slug"
        value="{{ old('slug', $subscriptionPlan->slug ?? '') }}"
        class="form-control @error('slug') is-invalid @enderror"
        placeholder="e.g. starter">
    @error('slug') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Monthly Price --}}
<div>
    <label for="price_monthly" class="form-label">Monthly Price <span class="text-red-500">*</span></label>
    <input type="number" id="price_monthly" name="price_monthly" step="0.0001" min="0"
        value="{{ old('price_monthly', $subscriptionPlan->price_monthly ?? '0') }}"
        class="form-control @error('price_monthly') is-invalid @enderror"
        placeholder="0.00">
    @error('price_monthly') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Yearly Price --}}
<div>
    <label for="price_yearly" class="form-label">Yearly Price <span class="text-red-500">*</span></label>
    <input type="number" id="price_yearly" name="price_yearly" step="0.0001" min="0"
        value="{{ old('price_yearly', $subscriptionPlan->price_yearly ?? '0') }}"
        class="form-control @error('price_yearly') is-invalid @enderror"
        placeholder="0.00">
    @error('price_yearly') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Is Active --}}
<div class="flex items-center gap-3 pt-2">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1"
        {{ old('is_active', $subscriptionPlan->is_active ?? true) ? 'checked' : '' }}
        class="w-4 h-4 rounded border-slate-300 text-indigo-600">
    <label for="is_active" class="form-label mb-0">Active</label>
    @error('is_active') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Features --}}
<div class="md:col-span-2">
    <label for="features_text" class="form-label">Features <span class="text-slate-400 text-xs">(one per line)</span></label>
    <textarea id="features_text" name="features_text" rows="6"
        class="form-control @error('features_text') is-invalid @enderror"
        placeholder="Unlimited institutions&#10;Priority support&#10;API access&#10;Advanced analytics">{{ old('features_text', isset($subscriptionPlan) && is_array($subscriptionPlan->features) ? implode("\n", $subscriptionPlan->features) : '') }}</textarea>
    @error('features_text') <p class="form-error">{{ $message }}</p> @enderror
    <p class="text-xs text-slate-400 mt-1">Each non-empty line becomes a separate feature bullet.</p>
</div>
