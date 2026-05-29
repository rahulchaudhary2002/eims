{{-- Conversations Form Partial --}}

<div class="form-group">
    <label class="form-label">Student</label>
    <select name="student_id" class="form-select @error('student_id') is-invalid @enderror">
        <option value="">— Select Student —</option>
        @foreach($students as $st)
            <option value="{{ $st->id }}"
                {{ old('student_id', $conversation->student_id ?? $selectedStudentId ?? '') == $st->id ? 'selected' : '' }}>
                {{ $st->name }}
            </option>
        @endforeach
    </select>
    @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

<div class="form-group">
    <label class="form-label">Institution</label>
    <select name="institution_id" class="form-select @error('institution_id') is-invalid @enderror">
        <option value="">— Select Institution —</option>
        @foreach($institutions as $inst)
            <option value="{{ $inst->id }}"
                {{ old('institution_id', $conversation->institution_id ?? $selectedInstitutionId ?? '') == $inst->id ? 'selected' : '' }}>
                {{ $inst->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

<div class="form-group">
    <label class="form-label required">Type</label>
    <select name="type" class="form-select @error('type') is-invalid @enderror" required>
        <option value="">— Select Type —</option>
        @foreach($types as $value => $label)
            <option value="{{ $value }}"
                {{ old('type', $conversation->type ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('type') <p class="form-error">{{ $message }}</p> @enderror
</div>
