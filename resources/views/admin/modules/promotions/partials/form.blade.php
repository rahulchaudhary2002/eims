{{-- Title --}}
<div class="md:col-span-2">
    <label for="title" class="form-label">Title <span class="text-red-500">*</span></label>
    <input type="text" id="title" name="title"
        value="{{ old('title', $promotion->title ?? '') }}"
        class="form-control @error('title') is-invalid @enderror"
        placeholder="Promotion title">
    @error('title') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Type --}}
<div>
    <label for="type" class="form-label">Type <span class="text-red-500">*</span></label>
    <select id="type" name="type" class="form-control @error('type') is-invalid @enderror">
        @foreach($types as $value => $label)
            <option value="{{ $value }}" {{ old('type', $promotion->type ?? 'banner') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('type') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
        @foreach($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $promotion->status ?? 'draft') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution</label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Platform-wide</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $promotion->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Amount --}}
<div>
    <label for="amount" class="form-label">Amount <span class="text-slate-400 text-xs">(optional - discount/cashback value)</span></label>
    <input type="number" id="amount" name="amount" step="0.0001" min="0"
        value="{{ old('amount', $promotion->amount ?? '') }}"
        class="form-control @error('amount') is-invalid @enderror"
        placeholder="0.00">
    @error('amount') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Target URL --}}
<div>
    <label for="target_url" class="form-label">Target URL</label>
    <input type="url" id="target_url" name="target_url"
        value="{{ old('target_url', $promotion->target_url ?? '') }}"
        class="form-control @error('target_url') is-invalid @enderror"
        placeholder="https://...">
    @error('target_url') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Start Date --}}
<div>
    <label for="start_date" class="form-label">Start Date</label>
    <input type="date" id="start_date" name="start_date"
        value="{{ old('start_date', isset($promotion) ? $promotion->start_date?->format('Y-m-d') : '') }}"
        class="form-control @error('start_date') is-invalid @enderror">
    @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- End Date --}}
<div>
    <label for="end_date" class="form-label">End Date</label>
    <input type="date" id="end_date" name="end_date"
        value="{{ old('end_date', isset($promotion) ? $promotion->end_date?->format('Y-m-d') : '') }}"
        class="form-control @error('end_date') is-invalid @enderror">
    @error('end_date') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Image --}}
<div>
    <label for="image" class="form-label">Image <span class="text-slate-400 text-xs">(max 5 MB)</span></label>
    @if(isset($promotion) && $promotion->image)
        <div class="mb-2">
            <img src="{{ Storage::url($promotion->image) }}" alt="{{ $promotion->title }}"
                class="h-20 w-auto rounded-lg object-cover border border-slate-200">
            <p class="text-xs text-slate-400 mt-1">Upload a new image to replace it.</p>
        </div>
    @endif
    <input type="file" id="image" name="image" accept="image/*"
        class="form-control @error('image') is-invalid @enderror">
    @error('image') <p class="form-error">{{ $message }}</p> @enderror
</div>
