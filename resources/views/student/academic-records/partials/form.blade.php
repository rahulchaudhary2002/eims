@php $record = $record ?? null; @endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="student-form-label">Education Level <span class="text-red-500">*</span></label>
        <select name="level" class="student-form-control student-form-select {{ $errors->has('level') ? 'is-invalid' : '' }}">
            <option value="">Select level</option>
            @foreach(\App\Models\StudentAcademicRecord::LEVELS as $val => $label)
                <option value="{{ $val }}" {{ old('level', $record?->level) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('level')<p class="student-form-error">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label class="student-form-label">Institution Name <span class="text-red-500">*</span></label>
        <input type="text" name="institution_name" value="{{ old('institution_name', $record?->institution_name) }}"
               class="student-form-control {{ $errors->has('institution_name') ? 'is-invalid' : '' }}">
        @error('institution_name')<p class="student-form-error">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="student-form-label">Board / University</label>
        <select name="board" class="student-form-control student-form-select">
            <option value="">Select board</option>
            @foreach(\App\Models\StudentAcademicRecord::BOARDS as $val => $label)
                <option value="{{ $val }}" {{ old('board', $record?->board) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
         <label class="student-form-label">Faculty / Stream</label>
        <input type="text" name="faculty" value="{{ old('faculty', $record?->faculty) }}"
             class="student-form-control" placeholder="e.g. Science, Management">
    </div>
    <div>
         <label class="student-form-label">Passed Year</label>
        <input type="number" name="passed_year" value="{{ old('passed_year', $record?->passed_year) }}"
               min="1950" max="{{ date('Y') + 1 }}"
             class="student-form-control">
    </div>
    <div>
         <label class="student-form-label">Symbol Number</label>
        <input type="text" name="symbol_number" value="{{ old('symbol_number', $record?->symbol_number) }}"
             class="student-form-control">
    </div>
    <div>
         <label class="student-form-label">GPA (0–4.0)</label>
        <input type="number" step="0.01" name="gpa" value="{{ old('gpa', $record?->gpa) }}" min="0" max="4"
             class="student-form-control">
    </div>
    <div>
         <label class="student-form-label">Percentage (%)</label>
        <input type="number" step="0.01" name="percentage" value="{{ old('percentage', $record?->percentage) }}" min="0" max="100"
             class="student-form-control">
    </div>
    <div>
         <label class="student-form-label">Transcript File</label>
        <input type="file" name="transcript_file" accept=".pdf,.jpg,.jpeg,.png"
             class="student-form-file">
        @if($record?->transcript_file)
            <a href="{{ Storage::url($record->transcript_file) }}" target="_blank" class="text-xs text-[#4299e1] hover:underline mt-1 block no-underline">View current file</a>
        @endif
    </div>
    <div>
         <label class="student-form-label">Character Certificate</label>
        <input type="file" name="character_certificate_file" accept=".pdf,.jpg,.jpeg,.png"
             class="student-form-file">
        @if($record?->character_certificate_file)
            <a href="{{ Storage::url($record->character_certificate_file) }}" target="_blank" class="text-xs text-[#4299e1] hover:underline mt-1 block no-underline">View current file</a>
        @endif
    </div>
</div>
