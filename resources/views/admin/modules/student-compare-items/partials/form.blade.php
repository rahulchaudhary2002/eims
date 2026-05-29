{{-- Student --}}
<div>
    <label for="student_id" class="form-label">Student <span class="text-red-500">*</span></label>
    <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror">
        <option value="">Select Student</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}"
                {{ old('student_id', $studentCompareItem->student_id ?? $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
                {{ $student->name }} — {{ $student->email }}
            </option>
        @endforeach
    </select>
    @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Select Institution</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $studentCompareItem->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Institution Program --}}
<div>
    <label for="institution_program_id" class="form-label">Program <span class="text-slate-400 text-xs">(optional)</span></label>
    <select id="institution_program_id" name="institution_program_id" class="form-control @error('institution_program_id') is-invalid @enderror">
        <option value="">Not specified</option>
        @foreach($institutionPrograms as $program)
            <option value="{{ $program->id }}"
                {{ old('institution_program_id', $studentCompareItem->institution_program_id ?? '') == $program->id ? 'selected' : '' }}>
                {{ $program->title ?: ($program->program->name ?? 'Program #' . $program->id) }}
            </option>
        @endforeach
    </select>
    @error('institution_program_id') <p class="form-error">{{ $message }}</p> @enderror
</div>
