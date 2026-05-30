{{-- Institution Reviews Form Partial --}}

<div class="form-group">
    <label class="form-label required">Institution</label>
    <select name="institution_id" class="form-control @error('institution_id') is-invalid @enderror" required>
        <option value="">— Select Institution —</option>
        @foreach($institutions as $inst)
            <option value="{{ $inst->id }}"
                {{ old('institution_id', $institutionReview->institution_id ?? $selectedInstitutionId ?? '') == $inst->id ? 'selected' : '' }}>
                {{ $inst->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

<div class="form-group">
    <label class="form-label">Student</label>
    <select name="student_id" class="form-control @error('student_id') is-invalid @enderror">
        <option value="">— Select Student —</option>
        @foreach($students as $st)
            <option value="{{ $st->id }}"
                {{ old('student_id', $institutionReview->student_id ?? '') == $st->id ? 'selected' : '' }}>
                {{ $st->name }}
            </option>
        @endforeach
    </select>
    @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

<div class="form-group">
    <label class="form-label required">Rating</label>
    <select name="rating" class="form-control @error('rating') is-invalid @enderror" required>
        <option value="">— Select Rating —</option>
        @foreach([1 => '1 — Very Poor', 2 => '2 — Poor', 3 => '3 — Average', 4 => '4 — Good', 5 => '5 — Excellent'] as $val => $label)
            <option value="{{ $val }}" {{ old('rating', $institutionReview->rating ?? '') == $val ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('rating') <p class="form-error">{{ $message }}</p> @enderror
</div>

<div class="form-group">
    <label class="form-label">Review</label>
    <textarea name="review" rows="5"
              class="form-control @error('review') is-invalid @enderror"
              placeholder="Enter review text…">{{ old('review', $institutionReview->review ?? '') }}</textarea>
    @error('review') <p class="form-error">{{ $message }}</p> @enderror
</div>

<div class="form-group">
    <label class="flex items-center gap-2 cursor-pointer">
        <input type="hidden" name="is_approved" value="0">
        <input type="checkbox" name="is_approved" value="1"
               class="form-checkbox"
               {{ old('is_approved', $institutionReview->is_approved ?? false) ? 'checked' : '' }}>
        <span class="form-label mb-0">Approved</span>
    </label>
    @error('is_approved') <p class="form-error">{{ $message }}</p> @enderror
</div>
