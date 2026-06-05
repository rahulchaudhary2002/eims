{{-- Name --}}
<div>
    <label for="name" class="form-label">Name <span class="text-red-500">*</span></label>
    <input type="text" id="name" name="name"
        value="{{ old('name', $inquiry->name ?? '') }}"
        class="form-control @error('name') is-invalid @enderror"
        placeholder="Full name of the inquirer">
    @error('name') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Email --}}
<div>
    <label for="email" class="form-label">Email <span class="text-red-500">*</span></label>
    <input type="email" id="email" name="email"
        value="{{ old('email', $inquiry->email ?? '') }}"
        class="form-control @error('email') is-invalid @enderror"
        placeholder="email@example.com">
    @error('email') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Phone --}}
<div>
    <label for="phone" class="form-label">Phone</label>
    <input type="text" id="phone" name="phone"
        value="{{ old('phone', $inquiry->phone ?? '') }}"
        class="form-control @error('phone') is-invalid @enderror"
        placeholder="+977-XXXXXXXXXX">
    @error('phone') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Source --}}
<div>
    <label for="source" class="form-label">Source</label>
    <select id="source" name="source" class="form-control @error('source') is-invalid @enderror">
        <option value="">Not specified</option>
        @foreach($sources as $value => $label)
            <option value="{{ $value }}" {{ old('source', $inquiry->source ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('source') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Status --}}
<div>
    <label for="status" class="form-label">Status <span class="text-red-500">*</span></label>
    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
        @foreach($statuses as $value => $label)
            <option value="{{ $value }}" {{ old('status', $inquiry->status ?? 'new') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('status') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution</label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Not specified</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $inquiry->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Applicable Type + Item --}}
<div>
    <label for="applicable_type" class="form-label">Type</label>
    <select id="applicable_type" name="applicable_type" class="form-control @error('applicable_type') is-invalid @enderror">
        <option value="">Not specified</option>
        @foreach(\App\Models\Application::APPLICABLE_TYPES as $typeClass => $typeLabel)
            <option value="{{ $typeClass }}" {{ old('applicable_type', $inquiry->applicable_type ?? '') === $typeClass ? 'selected' : '' }}>{{ $typeLabel }}</option>
        @endforeach
    </select>
    @error('applicable_type') <p class="form-error">{{ $message }}</p> @enderror
</div>
<div>
    <label for="applicable_id" class="form-label">Item</label>
    <select id="applicable_id" name="applicable_id" class="form-control @error('applicable_id') is-invalid @enderror">
        <option value="">Not specified</option>
        @foreach($applicables ?? [] as $typeClass => $items)
            @foreach($items as $item)
                <option value="{{ $item->id }}"
                    data-type="{{ $typeClass }}"
                    {{ old('applicable_id', $inquiry->applicable_id ?? '') == $item->id && old('applicable_type', $inquiry->applicable_type ?? '') === $typeClass ? 'selected' : '' }}>
                    {{ $item->display_name ?? $item->title }} ({{ \App\Models\Application::APPLICABLE_TYPES[$typeClass] ?? '' }})
                </option>
            @endforeach
        @endforeach
    </select>
    @error('applicable_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Student --}}
<div>
    <label for="student_id" class="form-label">Student <span class="text-slate-400 text-xs">(if registered)</span></label>
    <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror">
        <option value="">Not linked</option>
        @foreach($students as $student)
            <option value="{{ $student->id }}"
                {{ old('student_id', $inquiry->student_id ?? $selectedStudentId ?? '') == $student->id ? 'selected' : '' }}>
                {{ $student->name }} - {{ $student->email }}
            </option>
        @endforeach
    </select>
    @error('student_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Assigned To --}}
<div>
    <label for="assigned_to" class="form-label">Assigned To</label>
    <select id="assigned_to" name="assigned_to" class="form-control @error('assigned_to') is-invalid @enderror">
        <option value="">Unassigned</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}"
                {{ old('assigned_to', $inquiry->assigned_to ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} - {{ $user->email }}
            </option>
        @endforeach
    </select>
    @error('assigned_to') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Last Contacted At --}}
<div>
    <label for="last_contacted_at" class="form-label">Last Contacted At</label>
    <input type="datetime-local" id="last_contacted_at" name="last_contacted_at"
        value="{{ old('last_contacted_at', isset($inquiry) ? $inquiry->last_contacted_at?->format('Y-m-d\TH:i') : '') }}"
        class="form-control @error('last_contacted_at') is-invalid @enderror">
    @error('last_contacted_at') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Message --}}
<div class="md:col-span-2">
    <label for="message" class="form-label">Message</label>
    <textarea id="message" name="message" rows="4"
        class="form-control @error('message') is-invalid @enderror"
        placeholder="Inquiry message or notes">{{ old('message', $inquiry->message ?? '') }}</textarea>
    @error('message') <p class="form-error">{{ $message }}</p> @enderror
</div>
