{{-- Scholarship --}}
<div>
    <label for="scholarship_id" class="form-label">Scholarship <span class="text-red-500">*</span></label>
    <select id="scholarship_id" name="scholarship_id" class="form-control @error('scholarship_id') is-invalid @enderror">
        <option value="">Select Scholarship</option>
        @foreach($scholarships as $scholarship)
            <option value="{{ $scholarship->id }}"
                {{ old('scholarship_id', $scholarshipApplication->scholarship_id ?? $selectedScholarshipId ?? '') == $scholarship->id ? 'selected' : '' }}>
                {{ $scholarship->title }}
            </option>
        @endforeach
    </select>
    @error('scholarship_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Student --}}
<div>
    <label for="student_id" class="form-label">Student <span class="text-red-500">*</span></label>
    <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror">
        <option value="">Select Student</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}"
                {{ old('student_id', $scholarshipApplication->student_id ?? $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
                {{ $student->name }} — {{ $student->email }}
            </option>
        @endforeach
    </select>
    @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Application --}}
<div>
    <label for="application_id" class="form-label">Application <span class="text-slate-400 text-xs">(optional)</span></label>
    <select id="application_id" name="application_id" class="form-control @error('application_id') is-invalid @enderror">
        <option value="">None</option>
        @foreach($applications as $application)
            <option value="{{ $application->id }}"
                {{ old('application_id', $scholarshipApplication->application_id ?? $selectedApplicationId ?? '') == $application->id ? 'selected' : '' }}>
                {{ $application->application_number }}
            </option>
        @endforeach
    </select>
    @error('application_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
        @foreach($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $scholarshipApplication->status ?? 'pending') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Approved Amount --}}
<div>
    <label for="approved_amount" class="form-label">Approved Amount <span class="text-slate-400 text-xs">(leave blank if not yet approved)</span></label>
    <input type="number" id="approved_amount" name="approved_amount" step="0.0001" min="0"
        value="{{ old('approved_amount', $scholarshipApplication->approved_amount ?? '') }}"
        class="form-control @error('approved_amount') is-invalid @enderror"
        placeholder="0.00">
    @error('approved_amount') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Remarks --}}
<div class="md:col-span-2">
    <label for="remarks" class="form-label">Remarks</label>
    <textarea id="remarks" name="remarks" rows="3"
        class="form-control @error('remarks') is-invalid @enderror"
        placeholder="Any notes about this scholarship application">{{ old('remarks', $scholarshipApplication->remarks ?? '') }}</textarea>
    @error('remarks') <p class="form-error">{{ $message }}</p> @enderror
</div>
