@php $doc = $doc ?? null; @endphp

<div>
    <label class="student-form-label">Document Type <span class="text-red-500">*</span></label>
    <select name="document_type" class="student-form-control student-form-select {{ $errors->has('document_type') ? 'is-invalid' : '' }}">
        <option value="">Select type</option>
        @foreach(\App\Models\StudentDocument::DOCUMENT_TYPES as $val => $label)
            <option value="{{ $val }}" {{ old('document_type', $doc?->document_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
        @error('document_type')<p class="student-form-error">{{ $message }}</p>@enderror
</div>

<div>
        <label class="student-form-label">Title <span class="text-red-500">*</span></label>
    <input type="text" name="title" value="{{ old('title', $doc?->title) }}"
           placeholder="e.g. Citizenship Front, Passport Photo"
            class="student-form-control {{ $errors->has('title') ? 'is-invalid' : '' }}">
        @error('title')<p class="student-form-error">{{ $message }}</p>@enderror
</div>

<div>
        <label class="student-form-label">File {{ $doc ? '' : '*' }}</label>
    <input type="file" name="file_path" accept=".pdf,.jpg,.jpeg,.png"
            class="student-form-file">
    @if(storage_exists($doc?->file_path))
        <a href="{{ storage_url($doc->file_path) }}" target="_blank" class="text-xs text-[#4299e1] hover:underline mt-1 block no-underline">View current file</a>
    @endif
    @error('file_path')<p class="student-form-error">{{ $message }}</p>@enderror
    <p class="student-form-help">PDF, JPG, PNG. Max 10MB.</p>
</div>

<div>
    <label class="student-form-label">Remarks</label>
    <textarea name="remarks" rows="2"
              class="student-form-control student-form-textarea"
              placeholder="Optional notes">{{ old('remarks', $doc?->remarks) }}</textarea>
</div>
