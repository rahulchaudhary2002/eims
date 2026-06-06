{{--
    partials/form.blade.php - shared fields for create / edit student academic record.

    Variables expected:
      $studentAcademicRecord  - StudentAcademicRecord|null  (null on create)
      $students               - Collection<Student>
      $levels                 - array  (StudentAcademicRecord::LEVELS)
      $boards                 - array  (StudentAcademicRecord::BOARDS)
--}}

{{-- ── Student + Level ── --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Student &amp; Level</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="student_id">Student <span class="text-red-500">*</span></label>
            <select name="student_id" id="student_id"
                class="form-control @error('student_id') is-invalid @enderror"
                {{ isset($studentAcademicRecord) ? 'disabled' : 'required' }}>
                <option value="">- Select student -</option>
                @foreach($students as $s)
                    <option value="{{ $s->id }}"
                        {{ old('student_id', $studentAcademicRecord->student_id ?? '') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }} ({{ $s->email }})
                    </option>
                @endforeach
            </select>
            @isset($studentAcademicRecord)
                <input type="hidden" name="student_id" value="{{ $studentAcademicRecord->student_id }}">
            @endisset
            @error('student_id')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="level">Education Level <span class="text-red-500">*</span></label>
            <select name="level" id="level" required
                class="form-control @error('level') is-invalid @enderror">
                <option value="">- Select level -</option>
                @foreach($levels as $val => $label)
                    <option value="{{ $val }}"
                        {{ old('level', $studentAcademicRecord->level ?? '') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('level')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- ── Institution Details ── --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-emerald-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Institution Details</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="institution_name">Institution Name</label>
            <input type="text" name="institution_name" id="institution_name"
                class="form-control @error('institution_name') is-invalid @enderror"
                value="{{ old('institution_name', $studentAcademicRecord->institution_name ?? '') }}"
                maxlength="255">
            @error('institution_name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="board">Board / University</label>
            <select name="board" id="board"
                class="form-control @error('board') is-invalid @enderror">
                <option value="">- Select board -</option>
                @foreach($boards as $val => $label)
                    <option value="{{ $val }}"
                        {{ old('board', $studentAcademicRecord->board ?? '') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('board')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="faculty">Faculty / Stream</label>
            <input type="text" name="faculty" id="faculty"
                class="form-control @error('faculty') is-invalid @enderror"
                value="{{ old('faculty', $studentAcademicRecord->faculty ?? '') }}"
                maxlength="150" placeholder="e.g. Science, Management">
            @error('faculty')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="passed_year">Passed Year</label>
            <input type="number" name="passed_year" id="passed_year"
                class="form-control @error('passed_year') is-invalid @enderror"
                value="{{ old('passed_year', $studentAcademicRecord->passed_year ?? '') }}"
                min="1950" max="{{ date('Y') + 1 }}" placeholder="{{ date('Y') }}">
            @error('passed_year')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- ── Results ── --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-amber-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Results</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
            <label class="form-label" for="symbol_number">Symbol / Roll Number</label>
            <input type="text" name="symbol_number" id="symbol_number"
                class="form-control @error('symbol_number') is-invalid @enderror"
                value="{{ old('symbol_number', $studentAcademicRecord->symbol_number ?? '') }}"
                maxlength="100">
            @error('symbol_number')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="gpa">GPA <span class="text-slate-400 font-normal">(0-4)</span></label>
            <input type="number" name="gpa" id="gpa" step="0.01"
                class="form-control @error('gpa') is-invalid @enderror"
                value="{{ old('gpa', $studentAcademicRecord->gpa ?? '') }}"
                min="0" max="4" placeholder="e.g. 3.60">
            @error('gpa')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="percentage">Percentage <span class="text-slate-400 font-normal">(0-100)</span></label>
            <input type="number" name="percentage" id="percentage" step="0.01"
                class="form-control @error('percentage') is-invalid @enderror"
                value="{{ old('percentage', $studentAcademicRecord->percentage ?? '') }}"
                min="0" max="100" placeholder="e.g. 75.50">
            @error('percentage')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- ── Documents ── --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-violet-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Documents</h3>
    </div>

    {{-- Transcript --}}
    <div>
        <label class="form-label" for="transcript_file">
            Transcript / Marksheet
            <span class="text-slate-400 font-normal">(PDF, JPG, PNG - max 5 MB)</span>
        </label>
        @isset($studentAcademicRecord)
            @if(storage_exists($studentAcademicRecord->transcript_file))
            <div class="mb-2 flex items-center gap-2 text-sm text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Current file:
                <a href="{{ storage_url($studentAcademicRecord->transcript_file) }}"
                    target="_blank" class="text-blue-600 hover:underline">
                    {{ basename($studentAcademicRecord->transcript_file) }}
                </a>
                <span class="text-slate-400 text-xs">(upload new file to replace)</span>
            </div>
            @endif
        @endisset
        <input type="file" name="transcript_file" id="transcript_file"
            class="form-control @error('transcript_file') is-invalid @enderror"
            accept=".pdf,.jpg,.jpeg,.png">
        @error('transcript_file')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Character Certificate --}}
    <div>
        <label class="form-label" for="character_certificate_file">
            Character Certificate
            <span class="text-slate-400 font-normal">(PDF, JPG, PNG - max 5 MB)</span>
        </label>
        @isset($studentAcademicRecord)
            @if(storage_exists($studentAcademicRecord->character_certificate_file))
            <div class="mb-2 flex items-center gap-2 text-sm text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                Current file:
                <a href="{{ storage_url($studentAcademicRecord->character_certificate_file) }}"
                    target="_blank" class="text-blue-600 hover:underline">
                    {{ basename($studentAcademicRecord->character_certificate_file) }}
                </a>
                <span class="text-slate-400 text-xs">(upload new file to replace)</span>
            </div>
            @endif
        @endisset
        <input type="file" name="character_certificate_file" id="character_certificate_file"
            class="form-control @error('character_certificate_file') is-invalid @enderror"
            accept=".pdf,.jpg,.jpeg,.png">
        @error('character_certificate_file')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

{{-- ── Verification ── --}}
<div class="eims-card p-6">
    <div class="flex items-center gap-4">
        <label class="flex items-center gap-3 cursor-pointer select-none">
            <input type="hidden" name="is_verified" value="0">
            <input type="checkbox" name="is_verified" id="is_verified" value="1"
                class="w-4 h-4 text-blue-600 rounded border-slate-300 focus:ring-blue-500"
                {{ old('is_verified', $studentAcademicRecord->is_verified ?? false) ? 'checked' : '' }}>
            <span class="text-sm font-medium text-slate-700">Mark as Verified</span>
        </label>
        <p class="text-xs text-slate-400">Indicates that the documents have been physically or officially verified.</p>
    </div>
</div>
