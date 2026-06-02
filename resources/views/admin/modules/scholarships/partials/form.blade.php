<div class="space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
            <select name="institution_id" id="institution_id" class="form-control @error('institution_id') border-red-400 @enderror">
                <option value="">Select Institution</option>
                @foreach($institutions as $institution)
                    <option value="{{ $institution->id }}" {{ old('institution_id', $scholarship->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
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
                    <option value="{{ $institutionProgram->id }}" {{ old('institution_program_id', $scholarship->institution_program_id ?? $selectedInstitutionProgramId ?? '') == $institutionProgram->id ? 'selected' : '' }}>
                        {{ $institutionProgram->institution->name ?? 'Institution' }} - {{ $institutionProgram->title ?: ($institutionProgram->program->name ?? 'Program') }}
                    </option>
                @endforeach
            </select>
            @error('institution_program_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="type" class="form-label">Type <span class="text-red-500">*</span></label>
            <select name="type" id="type" class="form-control @error('type') border-red-400 @enderror">
                @foreach($types as $value => $label)
                    <option value="{{ $value }}" {{ old('type', $scholarship->type ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('type') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
            <select name="status" id="status" class="form-control @error('status') border-red-400 @enderror">
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}" {{ old('status', $scholarship->status ?? 'draft') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('status') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="title" class="form-label">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" value="{{ old('title', $scholarship->title ?? '') }}" class="form-control @error('title') border-red-400 @enderror">
            @error('title') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $scholarship->slug ?? '') }}" class="form-control @error('slug') border-red-400 @enderror" placeholder="Auto-generated if blank">
            @error('slug') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" rows="5" class="form-control ckeditor @error('description') border-red-400 @enderror">{{ old('description', $scholarship->description ?? '') }}</textarea>
        @error('description') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="minimum_gpa" class="form-label">Minimum GPA</label>
            <input type="number" step="0.01" min="0" max="4" name="minimum_gpa" id="minimum_gpa" value="{{ old('minimum_gpa', $scholarship->minimum_gpa ?? '') }}" class="form-control @error('minimum_gpa') border-red-400 @enderror">
            @error('minimum_gpa') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="minimum_percentage" class="form-label">Minimum Percentage</label>
            <input type="number" step="0.01" min="0" max="100" name="minimum_percentage" id="minimum_percentage" value="{{ old('minimum_percentage', $scholarship->minimum_percentage ?? '') }}" class="form-control @error('minimum_percentage') border-red-400 @enderror">
            @error('minimum_percentage') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="benefit_type" class="form-label">Benefit Type <span class="text-red-500">*</span></label>
            <select name="benefit_type" id="benefit_type" class="form-control @error('benefit_type') border-red-400 @enderror">
                @foreach($benefitTypes as $value => $label)
                    <option value="{{ $value }}" {{ old('benefit_type', $scholarship->benefit_type ?? '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('benefit_type') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="benefit_value" class="form-label">Benefit Value</label>
            <input type="number" step="0.01" min="0" name="benefit_value" id="benefit_value" value="{{ old('benefit_value', $scholarship->benefit_value ?? '') }}" class="form-control @error('benefit_value') border-red-400 @enderror">
            @error('benefit_value') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="total_slots" class="form-label">Total Slots</label>
            <input type="number" min="0" name="total_slots" id="total_slots" value="{{ old('total_slots', $scholarship->total_slots ?? '') }}" class="form-control @error('total_slots') border-red-400 @enderror">
            @error('total_slots') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="used_slots" class="form-label">Used Slots</label>
            <input type="number" min="0" name="used_slots" id="used_slots" value="{{ old('used_slots', $scholarship->used_slots ?? 0) }}" class="form-control @error('used_slots') border-red-400 @enderror">
            @error('used_slots') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="start_date" class="form-label">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $scholarship?->start_date?->format('Y-m-d') ?? '') }}" class="form-control @error('start_date') border-red-400 @enderror">
            @error('start_date') <p class="form-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="end_date" class="form-label">End Date</label>
            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $scholarship?->end_date?->format('Y-m-d') ?? '') }}" class="form-control @error('end_date') border-red-400 @enderror">
            @error('end_date') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
