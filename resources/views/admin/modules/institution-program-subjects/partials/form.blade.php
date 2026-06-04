<div class="space-y-5">
    {{-- Institution Program dropdown --}}
    <div>
        <label for="institution_program_id" class="form-label">Institution Program <span class="text-red-500">*</span></label>
        <select name="institution_program_id" id="institution_program_id" class="form-control @error('institution_program_id') border-red-400 @enderror">
            <option value="">- Select Institution Program -</option>
            @foreach($institutionPrograms as $ip)
                <option value="{{ $ip->id }}"
                    {{ old('institution_program_id', $institutionProgramSubject->institution_program_id ?? $selectedProgramId ?? '') == $ip->id ? 'selected' : '' }}>
                    {{ $ip->institution->name ?? '?' }} - {{ $ip->program->name ?? '?' }}
                </option>
            @endforeach
        </select>
        @error('institution_program_id')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- Subject Name --}}
    <div>
        <label for="subject_name" class="form-label">Subject Name <span class="text-red-500">*</span></label>
        <input
            type="text"
            name="subject_name"
            id="subject_name"
            value="{{ old('subject_name', $institutionProgramSubject->subject_name ?? '') }}"
            class="form-control @error('subject_name') border-red-400 @enderror"
            placeholder="e.g. Mathematics, Physics, English"
        >
        @error('subject_name')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>

    {{-- Is Optional --}}
    <div>
        <label class="flex items-center gap-3 cursor-pointer select-none">
            <input
                type="checkbox"
                name="is_optional"
                value="1"
                class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                {{ old('is_optional', ($institutionProgramSubject->is_optional ?? false) ? '1' : '0') == '1' ? 'checked' : '' }}
            >
            <span class="text-sm text-slate-700">This subject is <strong>optional</strong> (elective)</span>
        </label>
        @error('is_optional')
            <p class="form-error">{{ $message }}</p>
        @enderror
    </div>
</div>
