{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Select Institution</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $consultancyDestination->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Country --}}
<div>
    <label for="country" class="form-label">Country <span class="text-red-500">*</span></label>
    <input type="text" id="country" name="country"
        value="{{ old('country', $consultancyDestination->country ?? '') }}"
        class="form-control @error('country') is-invalid @enderror"
        placeholder="e.g. Australia">
    @error('country') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- City --}}
<div>
    <label for="city" class="form-label">City <span class="text-slate-400 text-xs">(optional)</span></label>
    <input type="text" id="city" name="city"
        value="{{ old('city', $consultancyDestination->city ?? '') }}"
        class="form-control @error('city') is-invalid @enderror"
        placeholder="e.g. Sydney">
    @error('city') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Is Active --}}
<div class="flex items-center gap-3 pt-2">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1"
        {{ old('is_active', $consultancyDestination->is_active ?? true) ? 'checked' : '' }}
        class="w-4 h-4 rounded border-slate-300 text-indigo-600">
    <label for="is_active" class="form-label mb-0">Active</label>
    @error('is_active') <p class="form-error">{{ $message }}</p> @enderror
</div>
