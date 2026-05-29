<div class="space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="application_id" class="form-label">Application <span class="text-red-500">*</span></label>
            <select name="application_id" id="application_id" class="form-control @error('application_id') border-red-400 @enderror">
                <option value="">Select Application</option>
                @foreach($applications as $application)
                    <option value="{{ $application->id }}" {{ old('application_id', $admission->application_id ?? $selectedApplicationId ?? '') == $application->id ? 'selected' : '' }}>
                        {{ $application->application_number }} - {{ $application->student->name ?? 'Student' }}
                    </option>
                @endforeach
            </select>
            @error('application_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="student_id" class="form-label">Student <span class="text-red-500">*</span></label>
            <select name="student_id" id="student_id" class="form-control @error('student_id') border-red-400 @enderror">
                <option value="">Select Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id', $admission->student_id ?? $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
                        {{ $student->name }} - {{ $student->email }}
                    </option>
                @endforeach
            </select>
            @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
            <select name="institution_id" id="institution_id" class="form-control @error('institution_id') border-red-400 @enderror">
                <option value="">Select Institution</option>
                @foreach($institutions as $institution)
                    <option value="{{ $institution->id }}" {{ old('institution_id', $admission->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                        {{ $institution->name }}
                    </option>
                @endforeach
            </select>
            @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="institution_program_id" class="form-label">Institution Program <span class="text-red-500">*</span></label>
            <select name="institution_program_id" id="institution_program_id" class="form-control @error('institution_program_id') border-red-400 @enderror">
                <option value="">Select Institution Program</option>
                @foreach($institutionPrograms as $institutionProgram)
                    <option value="{{ $institutionProgram->id }}" {{ old('institution_program_id', $admission->institution_program_id ?? $selectedInstitutionProgramId ?? '') == $institutionProgram->id ? 'selected' : '' }}>
                        {{ $institutionProgram->institution->name ?? 'Institution' }} - {{ $institutionProgram->title ?: ($institutionProgram->program->name ?? 'Program') }}
                    </option>
                @endforeach
            </select>
            @error('institution_program_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="admission_number" class="form-label">Admission Number</label>
            <input type="text" name="admission_number" id="admission_number" value="{{ old('admission_number', $admission->admission_number ?? '') }}" class="form-control @error('admission_number') border-red-400 @enderror" placeholder="Auto-generated if blank">
            @error('admission_number') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="admission_date" class="form-label">Admission Date <span class="text-red-500">*</span></label>
            <input type="date" name="admission_date" id="admission_date" value="{{ old('admission_date', $admission?->admission_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" class="form-control @error('admission_date') border-red-400 @enderror">
            @error('admission_date') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="paid_amount" class="form-label">Paid Amount</label>
            <input type="number" step="0.01" min="0" name="paid_amount" id="paid_amount" value="{{ old('paid_amount', $admission->paid_amount ?? '') }}" class="form-control @error('paid_amount') border-red-400 @enderror">
            @error('paid_amount') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="payment_proof" class="form-label">Payment Proof</label>
            <input type="file" name="payment_proof" id="payment_proof" class="form-control @error('payment_proof') border-red-400 @enderror">
            @if(!empty($admission?->payment_proof))
                <a href="{{ Storage::url($admission->payment_proof) }}" target="_blank" rel="noopener" class="text-xs text-blue-600 hover:underline">View current proof</a>
            @endif
            @error('payment_proof') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div>
            <label for="verification_status" class="form-label">Verification Status <span class="text-red-500">*</span></label>
            <select name="verification_status" id="verification_status" class="form-control @error('verification_status') border-red-400 @enderror">
                @foreach($verificationStatuses as $value => $label)
                    <option value="{{ $value }}" {{ old('verification_status', $admission->verification_status ?? 'pending') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('verification_status') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="verified_by" class="form-label">Verified By</label>
            <select name="verified_by" id="verified_by" class="form-control @error('verified_by') border-red-400 @enderror">
                <option value="">Not verified</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('verified_by', $admission->verified_by ?? '') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
            @error('verified_by') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="verified_at" class="form-label">Verified At</label>
            <input type="datetime-local" name="verified_at" id="verified_at" value="{{ old('verified_at', $admission?->verified_at?->format('Y-m-d\TH:i') ?? '') }}" class="form-control @error('verified_at') border-red-400 @enderror">
            @error('verified_at') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="remarks" class="form-label">Remarks</label>
        <textarea name="remarks" id="remarks" rows="4" class="form-control @error('remarks') border-red-400 @enderror">{{ old('remarks', $admission->remarks ?? '') }}</textarea>
        @error('remarks') <p class="form-error">{{ $message }}</p> @enderror
    </div>
</div>
