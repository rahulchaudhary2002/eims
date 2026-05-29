{{-- Student --}}
<div>
    <label for="student_id" class="form-label">Student <span class="text-red-500">*</span></label>
    <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror">
        <option value="">Select Student</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}"
                {{ old('student_id', $counselingSession->student_id ?? $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
                {{ $student->name }} — {{ $student->email }}
            </option>
        @endforeach
    </select>
    @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution <span class="text-slate-400 text-xs">(optional)</span></label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Not linked</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $counselingSession->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Counselor --}}
<div>
    <label for="counselor_id" class="form-label">Counselor</label>
    <select id="counselor_id" name="counselor_id" class="form-control @error('counselor_id') is-invalid @enderror">
        <option value="">Unassigned</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}"
                {{ old('counselor_id', $counselingSession->counselor_id ?? $defaultCounselorId ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} — {{ $user->email }}
            </option>
        @endforeach
    </select>
    @error('counselor_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Mode --}}
<div>
    <label for="mode" class="form-label">Mode <span class="text-red-500">*</span></label>
    <select id="mode" name="mode" class="form-control @error('mode') is-invalid @enderror">
        @foreach($modes as $value => $label)
            <option value="{{ $value }}" {{ old('mode', $counselingSession->mode ?? 'online') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('mode') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Scheduled At --}}
<div>
    <label for="scheduled_at" class="form-label">Scheduled At <span class="text-red-500">*</span></label>
    <input type="datetime-local" id="scheduled_at" name="scheduled_at"
        value="{{ old('scheduled_at', isset($counselingSession) ? $counselingSession->scheduled_at?->format('Y-m-d\TH:i') : '') }}"
        class="form-control @error('scheduled_at') is-invalid @enderror">
    @error('scheduled_at') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
        @foreach($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $counselingSession->status ?? 'scheduled') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Student Message --}}
<div class="md:col-span-2">
    <label for="student_message" class="form-label">Student Message</label>
    <textarea id="student_message" name="student_message" rows="3"
        class="form-control @error('student_message') is-invalid @enderror"
        placeholder="What the student would like to discuss...">{{ old('student_message', $counselingSession->student_message ?? '') }}</textarea>
    @error('student_message') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Counselor Notes --}}
<div class="md:col-span-2">
    <label for="counselor_notes" class="form-label">Counselor Notes</label>
    <textarea id="counselor_notes" name="counselor_notes" rows="4"
        class="form-control @error('counselor_notes') is-invalid @enderror"
        placeholder="Notes from the counseling session...">{{ old('counselor_notes', $counselingSession->counselor_notes ?? '') }}</textarea>
    @error('counselor_notes') <p class="form-error">{{ $message }}</p> @enderror
</div>
