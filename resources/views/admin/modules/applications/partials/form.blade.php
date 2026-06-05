<div class="space-y-5">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="application_number" class="form-label">Application Number</label>
            <input type="text" name="application_number" id="application_number" value="{{ old('application_number', $application->application_number ?? '') }}" class="form-control @error('application_number') border-red-400 @enderror" placeholder="Auto-generated if blank">
            @error('application_number') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="student_id" class="form-label">Student <span class="text-red-500">*</span></label>
            <select name="student_id" id="student_id" class="form-control @error('student_id') border-red-400 @enderror">
                <option value="">Select Student</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id', $application->student_id ?? $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
                        {{ $student->name }} - {{ $student->email }}
                    </option>
                @endforeach
            </select>
            @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div
        x-data="{
            institutionId: '{{ old('institution_id', $application->institution_id ?? $selectedInstitutionId ?? '') }}',
            applicableType: '{{ old('applicable_type', $application->applicable_type ?? '') }}',
            applicableId: '{{ old('applicable_id', $application->applicable_id ?? '') }}',
            applicables: @js(collect($applicables)->map(fn($items, $type) => $items->map(fn($item) => ['id' => (string)$item->id, 'institution_id' => (string)$item->institution_id, 'label' => method_exists($item, 'getDisplayNameAttribute') ? $item->display_name : $item->title]))->toArray()),
            get filteredItems() {
                if (!this.applicableType || !this.institutionId) return [];
                const items = this.applicables[this.applicableType] ?? [];
                return items.filter(i => i.institution_id === this.institutionId);
            }
        }"
        x-effect="if (applicableId && !filteredItems.some(i => i.id === applicableId)) { applicableId = ''; }"
        class="grid grid-cols-1 md:grid-cols-2 gap-5"
    >
        <div>
            <label for="institution_id" class="form-label">Institution <span class="text-red-500">*</span></label>
            <select name="institution_id" id="institution_id" x-model="institutionId" class="form-control @error('institution_id') border-red-400 @enderror">
                <option value="">Select Institution</option>
                @foreach($institutions as $institution)
                    <option value="{{ $institution->id }}">{{ $institution->name }}</option>
                @endforeach
            </select>
            @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="applicable_type" class="form-label">Applicable Type <span class="text-red-500">*</span></label>
            <select name="applicable_type" id="applicable_type" x-model="applicableType" @change="applicableId = ''" class="form-control @error('applicable_type') border-red-400 @enderror">
                <option value="">Select Type</option>
                @foreach(\App\Models\Application::APPLICABLE_TYPES as $typeClass => $typeLabel)
                    <option value="{{ $typeClass }}">{{ $typeLabel }}</option>
                @endforeach
            </select>
            @error('applicable_type') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div class="md:col-span-2">
            <label for="applicable_id" class="form-label">Item <span class="text-red-500">*</span></label>
            <select name="applicable_id" id="applicable_id" x-model="applicableId" :disabled="!applicableType || !institutionId" class="form-control @error('applicable_id') border-red-400 @enderror">
                <option value="" x-text="(!applicableType || !institutionId) ? 'Select type and institution first' : 'Select item'"></option>
                <template x-for="item in filteredItems" :key="item.id">
                    <option :value="item.id" x-text="item.label"></option>
                </template>
            </select>
            @error('applicable_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="scholarship_id" class="form-label">Scholarship</label>
            <select name="scholarship_id" id="scholarship_id" class="form-control @error('scholarship_id') border-red-400 @enderror">
                <option value="">No Scholarship</option>
                @foreach($scholarships as $scholarship)
                    <option value="{{ $scholarship->id }}" {{ old('scholarship_id', $application->scholarship_id ?? $selectedScholarshipId ?? '') == $scholarship->id ? 'selected' : '' }}>
                        {{ $scholarship->title }} - {{ $scholarship->institutionProgram?->title ?: ($scholarship->institutionProgram?->program?->name ?? 'Program') }}
                    </option>
                @endforeach
            </select>
            @error('scholarship_id') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="source" class="form-label">Source <span class="text-red-500">*</span></label>
            <select name="source" id="source" class="form-control @error('source') border-red-400 @enderror">
                @foreach($sources as $value => $label)
                    <option value="{{ $value }}" {{ old('source', $application->source ?? 'direct') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('source') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
        <select name="status" id="status" class="form-control @error('status') border-red-400 @enderror">
            @foreach($statuses as $value => $label)
                <option value="{{ $value }}" {{ old('status', $application->status ?? 'draft') === $value ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="student_message" class="form-label">Student Message</label>
        <textarea name="student_message" id="student_message" rows="4" class="form-control @error('student_message') border-red-400 @enderror">{{ old('student_message', $application->student_message ?? '') }}</textarea>
        @error('student_message') <p class="form-error">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label for="institution_remarks" class="form-label">Institution Remarks</label>
            <textarea name="institution_remarks" id="institution_remarks" rows="4" class="form-control @error('institution_remarks') border-red-400 @enderror">{{ old('institution_remarks', $application->institution_remarks ?? '') }}</textarea>
            @error('institution_remarks') <p class="form-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="admin_remarks" class="form-label">Admin Remarks</label>
            <textarea name="admin_remarks" id="admin_remarks" rows="4" class="form-control @error('admin_remarks') border-red-400 @enderror">{{ old('admin_remarks', $application->admin_remarks ?? '') }}</textarea>
            @error('admin_remarks') <p class="form-error">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
        @foreach(['submitted_at' => 'Submitted At', 'reviewed_at' => 'Reviewed At', 'referred_at' => 'Referred At', 'admitted_at' => 'Admitted At'] as $field => $label)
            <div>
                <label for="{{ $field }}" class="form-label">{{ $label }}</label>
                <input type="datetime-local" name="{{ $field }}" id="{{ $field }}" value="{{ old($field, $application?->$field?->format('Y-m-d\TH:i') ?? '') }}" class="form-control @error($field) border-red-400 @enderror">
                @error($field) <p class="form-error">{{ $message }}</p> @enderror
            </div>
        @endforeach
    </div>
</div>
