@php $record = $record ?? null; @endphp

<div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Education Level <span class="text-red-500">*</span></label>
        <select name="level" class="w-full px-4 py-3 text-sm border {{ $errors->has('level') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]">
            <option value="">Select level</option>
            @foreach(\App\Models\StudentAcademicRecord::LEVELS as $val => $label)
                <option value="{{ $val }}" {{ old('level', $record?->level) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('level')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div class="sm:col-span-2">
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Institution Name <span class="text-red-500">*</span></label>
        <input type="text" name="institution_name" value="{{ old('institution_name', $record?->institution_name) }}"
               class="w-full px-4 py-3 text-sm border {{ $errors->has('institution_name') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]">
        @error('institution_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Board / University</label>
        <select name="board" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
            <option value="">Select board</option>
            @foreach(\App\Models\StudentAcademicRecord::BOARDS as $val => $label)
                <option value="{{ $val }}" {{ old('board', $record?->board) === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Faculty / Stream</label>
        <input type="text" name="faculty" value="{{ old('faculty', $record?->faculty) }}"
               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]" placeholder="e.g. Science, Management">
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Passed Year</label>
        <input type="number" name="passed_year" value="{{ old('passed_year', $record?->passed_year) }}"
               min="1950" max="{{ date('Y') + 1 }}"
               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Symbol Number</label>
        <input type="text" name="symbol_number" value="{{ old('symbol_number', $record?->symbol_number) }}"
               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">GPA (0–4.0)</label>
        <input type="number" step="0.01" name="gpa" value="{{ old('gpa', $record?->gpa) }}" min="0" max="4"
               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Percentage (%)</label>
        <input type="number" step="0.01" name="percentage" value="{{ old('percentage', $record?->percentage) }}" min="0" max="100"
               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Transcript File</label>
        <input type="file" name="transcript_file" accept=".pdf,.jpg,.jpeg,.png"
               class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#ebf8ff] file:text-[#2c5aa0] hover:file:bg-[#bee3f8]">
        @if($record?->transcript_file)
            <a href="{{ Storage::url($record->transcript_file) }}" target="_blank" class="text-xs text-[#4299e1] hover:underline mt-1 block no-underline">View current file</a>
        @endif
    </div>
    <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Character Certificate</label>
        <input type="file" name="character_certificate_file" accept=".pdf,.jpg,.jpeg,.png"
               class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#ebf8ff] file:text-[#2c5aa0] hover:file:bg-[#bee3f8]">
        @if($record?->character_certificate_file)
            <a href="{{ Storage::url($record->character_certificate_file) }}" target="_blank" class="text-xs text-[#4299e1] hover:underline mt-1 block no-underline">View current file</a>
        @endif
    </div>
</div>
