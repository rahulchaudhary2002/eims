{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Select Institution</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $consultancyService->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Service Type --}}
<div>
    <label for="service_type" class="form-label">Service Type <span class="text-red-500">*</span></label>
    <select id="service_type" name="service_type" class="form-control @error('service_type') is-invalid @enderror">
        <option value="">Select Type</option>
        @foreach($serviceTypes as $value => $label)
            <option value="{{ $value }}" {{ old('service_type', $consultancyService->service_type ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('service_type') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Title --}}
<div>
    <label for="title" class="form-label">Title <span class="text-red-500">*</span></label>
    <input type="text" id="title" name="title"
        value="{{ old('title', $consultancyService->title ?? '') }}"
        class="form-control @error('title') is-invalid @enderror"
        placeholder="e.g. UK Student Visa Assistance">
    @error('title') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Service Fee --}}
<div>
    <label for="service_fee" class="form-label">Service Fee <span class="text-slate-400 text-xs">(optional)</span></label>
    <input type="number" id="service_fee" name="service_fee" step="0.0001" min="0"
        value="{{ old('service_fee', $consultancyService->service_fee ?? '') }}"
        class="form-control @error('service_fee') is-invalid @enderror"
        placeholder="0.00">
    @error('service_fee') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Is Active --}}
<div class="flex items-center gap-3 pt-2">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1"
        {{ old('is_active', $consultancyService->is_active ?? true) ? 'checked' : '' }}
        class="w-4 h-4 rounded border-slate-300 text-indigo-600">
    <label for="is_active" class="form-label mb-0">Active</label>
    @error('is_active') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Description --}}
<div class="md:col-span-2">
    <label for="description" class="form-label">Description</label>
    <textarea id="description" name="description" rows="4"
        class="form-control ckeditor @error('description') is-invalid @enderror"
        placeholder="Describe what this service includes...">{{ old('description', $consultancyService->description ?? '') }}</textarea>
    @error('description') <p class="form-error">{{ $message }}</p> @enderror
</div>
