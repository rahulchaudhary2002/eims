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
    @php
        $gradeType = old('grade_type', $record?->percentage !== null ? 'percentage' : 'gpa');
    @endphp
    <div x-data="{ gradeType: '{{ $gradeType }}' }" class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="student-form-label">Grade Type <span class="text-red-500">*</span></label>
            <select name="grade_type" x-model="gradeType" class="student-form-control student-form-select {{ $errors->has('grade_type') ? 'is-invalid' : '' }}">
                <option value="gpa">GPA (0–4.0)</option>
                <option value="percentage">Percentage (%)</option>
            </select>
            @error('grade_type')<p class="student-form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <template x-if="gradeType === 'gpa'">
                <div>
                    <label class="student-form-label">GPA <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="gpa" value="{{ old('gpa', $record?->gpa) }}"
                           min="0" max="4" placeholder="e.g. 3.60" class="student-form-control {{ $errors->has('gpa') ? 'is-invalid' : '' }}">
                    @error('gpa')<p class="student-form-error">{{ $message }}</p>@enderror
                </div>
            </template>
            <template x-if="gradeType === 'percentage'">
                <div>
                    <label class="student-form-label">Percentage <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="percentage" value="{{ old('percentage', $record?->percentage) }}"
                           min="0" max="100" placeholder="e.g. 85.50" class="student-form-control {{ $errors->has('percentage') ? 'is-invalid' : '' }}">
                    @error('percentage')<p class="student-form-error">{{ $message }}</p>@enderror
                </div>
            </template>
        </div>
    </div>
    <div>
         <label class="student-form-label">Transcript File</label>
        <input type="file" name="transcript_file" accept=".pdf,.jpg,.jpeg,.png" class="student-form-file">
        @if($record?->transcript_file)
            <a href="{{ Storage::url($record->transcript_file) }}" target="_blank" class="text-xs text-[#4299e1] hover:underline mt-1 block no-underline">View current file</a>
        @endif
    </div>
    <div>
         <label class="student-form-label">Character Certificate</label>
        <input type="file" name="character_certificate_file" accept=".pdf,.jpg,.jpeg,.png" class="student-form-file">
        @if($record?->character_certificate_file)
            <a href="{{ Storage::url($record->character_certificate_file) }}" target="_blank" class="text-xs text-[#4299e1] hover:underline mt-1 block no-underline">View current file</a>
        @endif
    </div>

    {{-- Additional Documents --}}
    @php
        $oldTypes  = old('additional_document_types', []);
        $oldTitles = old('additional_document_titles', []);
        $restoredRows = [];
        if (!empty($oldTypes) || !empty($oldTitles)) {
            $count = max(count($oldTypes), count($oldTitles));
            for ($j = 0; $j < $count; $j++) {
                $restoredRows[] = ['type' => $oldTypes[$j] ?? '', 'title' => $oldTitles[$j] ?? ''];
            }
        }
    @endphp
    <div class="sm:col-span-2 border-t border-gray-100 pt-5"
         x-data="{
             rows: @json($restoredRows),
             addRow() { this.rows.push({ type: '', title: '', file: null }) },
             removeRow(i) { this.rows.splice(i, 1) }
         }">
        <div class="flex items-center justify-between mb-3">
            <label class="student-form-label mb-0">Additional Documents</label>
            <button type="button" @click="addRow()"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-[#4299e1] border border-[#bee3f8] px-3 py-1.5 rounded-lg hover:bg-[#ebf8ff] transition">
                <i class="fas fa-plus"></i> Add Document
            </button>
        </div>

        {{-- Existing additional documents (edit mode) --}}
        @if($record?->relationLoaded('additionalDocuments') && $record->additionalDocuments->count())
        <div class="space-y-2 mb-3">
            @foreach($record->additionalDocuments as $doc)
            <div class="flex items-center gap-3 bg-gray-50 rounded-lg px-4 py-2.5 border border-gray-200">
                <i class="fas fa-file-alt text-sky-500 shrink-0"></i>
                <span class="text-sm text-gray-700 flex-1 truncate">{{ $doc->title }}</span>
                <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                   class="text-xs text-[#4299e1] hover:underline no-underline shrink-0">View</a>
                <form method="POST" action="{{ route('student.academic-records.documents.destroy', $doc) }}"
                      onsubmit="return confirm('Remove this document?')" class="shrink-0">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
            </div>
            @endforeach
        </div>
        @endif

        {{-- New document rows --}}
        @php
            $docTypes = \App\Models\StudentDocument::DOCUMENT_TYPES;
        @endphp
        <div class="space-y-3">
            <template x-for="(row, i) in rows" :key="i">
                <div class="relative bg-gray-50 border border-gray-200 rounded-lg p-3 pr-8">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                        <select :name="`additional_document_types[${i}]`"
                                x-model="row.type"
                                class="student-form-control student-form-select text-sm">
                            <option value="">Select type</option>
                            @foreach($docTypes as $val => $label)
                                <option value="{{ $val }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        <input type="text" :name="`additional_document_titles[${i}]`"
                               x-model="row.title"
                               placeholder="Title"
                               class="student-form-control text-sm">
                        <input type="file" :name="`additional_documents[${i}]`"
                               accept=".pdf,.jpg,.jpeg,.png"
                               class="student-form-file text-sm">
                    </div>
                    <button type="button" @click="removeRow(i)"
                        class="absolute top-0 right-0 shrink-0 w-5 h-5 flex items-center justify-center rounded-full text-red-400 hover:text-white hover:bg-red-400 transition text-xs leading-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </template>
        </div>
        <p class="text-xs text-gray-400 mt-2">PDF, JPG or PNG. Max 5MB each.</p>
        @if(!empty($restoredRows))
        <p class="text-xs text-amber-500 mt-1"><i class="fas fa-exclamation-triangle mr-1"></i>Please re-select files for the rows below - files cannot be restored after a validation error.</p>
        @endif
    </div>
</div>
