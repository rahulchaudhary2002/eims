{{-- Student --}}
<div>
    <label for="student_id" class="form-label">Student <span class="text-red-500">*</span></label>
    <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror">
        <option value="">Select Student</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}"
                {{ old('student_id', $studentRecommendation->student_id ?? $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
                {{ $student->name }} - {{ $student->email }}
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
                {{ old('institution_id', $studentRecommendation->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
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
                {{ old('institution_program_id', $studentRecommendation->institution_program_id ?? '') == $program->id ? 'selected' : '' }}>
                {{ $program->title ?: ($program->program->name ?? 'Program #' . $program->id) }}
            </option>
        @endforeach
    </select>
    @error('institution_program_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Score --}}
<div>
    <label for="score" class="form-label">Score <span class="text-slate-400 text-xs">(0 – 100)</span></label>
    <input type="number" id="score" name="score" step="0.01" min="0" max="100"
        value="{{ old('score', $studentRecommendation->score ?? '') }}"
        class="form-control @error('score') is-invalid @enderror"
        placeholder="e.g. 87.50">
    @error('score') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Is Viewed --}}
<div class="flex items-center gap-3 pt-2">
    <input type="hidden" name="is_viewed" value="0">
    <input type="checkbox" id="is_viewed" name="is_viewed" value="1"
        {{ old('is_viewed', $studentRecommendation->is_viewed ?? false) ? 'checked' : '' }}
        class="w-4 h-4 rounded border-slate-300 text-indigo-600">
    <label for="is_viewed" class="form-label mb-0">Mark as Viewed</label>
    @error('is_viewed') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Reasons --}}
<div class="md:col-span-2">
    <label class="form-label">Reasons <span class="text-slate-400 text-xs">(one per line)</span></label>
    <textarea id="reasons_text" name="reasons_text" rows="4"
        class="form-control @error('reasons') is-invalid @enderror"
        placeholder="Enter each reason on a new line...">{{ old('reasons_text', isset($studentRecommendation) && is_array($studentRecommendation->reasons) ? implode("\n", $studentRecommendation->reasons) : '') }}</textarea>
    @error('reasons') <p class="form-error">{{ $message }}</p> @enderror
    <p class="text-xs text-slate-400 mt-1">Each non-empty line becomes a separate reason.</p>
</div>
