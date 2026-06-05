{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Select Institution</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $institutionCertification->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Title --}}
<div>
    <label for="title" class="form-label">Title <span class="text-red-500">*</span></label>
    <input type="text" id="title" name="title"
        value="{{ old('title', $institutionCertification->title ?? '') }}"
        class="form-control @error('title') is-invalid @enderror"
        placeholder="e.g. PTE Academic Certification">
    @error('title') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Fee --}}
<div>
    <label for="fee" class="form-label">Fee <span class="text-slate-400 text-xs">(optional)</span></label>
    <input type="number" id="fee" name="fee" step="0.01" min="0"
        value="{{ old('fee', $institutionCertification->fee ?? '') }}"
        class="form-control @error('fee') is-invalid @enderror"
        placeholder="0.00">
    @error('fee') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Duration --}}
<div>
    <label for="duration_hours" class="form-label">Duration (hours) <span class="text-slate-400 text-xs">(optional)</span></label>
    <input type="number" id="duration_hours" name="duration_hours" min="1"
        value="{{ old('duration_hours', $institutionCertification->duration_hours ?? '') }}"
        class="form-control @error('duration_hours') is-invalid @enderror"
        placeholder="e.g. 20">
    @error('duration_hours') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Is Active --}}
<div class="flex items-center gap-3 pt-2">
    <input type="hidden" name="is_active" value="0">
    <input type="checkbox" id="is_active" name="is_active" value="1"
        {{ old('is_active', $institutionCertification->is_active ?? true) ? 'checked' : '' }}
        class="w-4 h-4 rounded border-slate-300 text-indigo-600">
    <label for="is_active" class="form-label mb-0">Active</label>
    @error('is_active') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Description --}}
<div class="md:col-span-2">
    <label for="description" class="form-label">Description <span class="text-slate-400 text-xs">(optional)</span></label>
    <textarea id="description" name="description" rows="6"
        class="form-control ckeditor @error('description') is-invalid @enderror">{{ old('description', $institutionCertification->description ?? '') }}</textarea>
    @error('description') <p class="form-error">{{ $message }}</p> @enderror
</div>
