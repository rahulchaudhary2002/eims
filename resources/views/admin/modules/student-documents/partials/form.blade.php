{{--
    partials/form.blade.php - shared fields for create / edit student document.

    Variables:
      $studentDocument  - StudentDocument|null  (null on create)
      $students         - Collection<Student>
      $documentTypes    - array  (StudentDocument::DOCUMENT_TYPES)
      $statuses         - array  (StudentDocument::STATUSES)
--}}

{{-- Student + Type + Title --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Document Information</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="student_id">Student <span class="text-red-500">*</span></label>
            <select name="student_id" id="student_id"
                class="form-control @error('student_id') is-invalid @enderror"
                {{ isset($studentDocument) ? 'disabled' : 'required' }}>
                <option value="">- Select student -</option>
                @foreach($students as $s)
                    <option value="{{ $s->id }}"
                        {{ old('student_id', $studentDocument->student_id ?? '') == $s->id ? 'selected' : '' }}>
                        {{ $s->name }} ({{ $s->email }})
                    </option>
                @endforeach
            </select>
            @isset($studentDocument)
                <input type="hidden" name="student_id" value="{{ $studentDocument->student_id }}">
            @endisset
            @error('student_id')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="document_type">Document Type <span class="text-red-500">*</span></label>
            <select name="document_type" id="document_type" required
                class="form-control @error('document_type') is-invalid @enderror">
                <option value="">- Select type -</option>
                @foreach($documentTypes as $val => $label)
                    <option value="{{ $val }}"
                        {{ old('document_type', $studentDocument->document_type ?? '') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('document_type')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="sm:col-span-2">
            <label class="form-label" for="title">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title" required
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $studentDocument->title ?? '') }}"
                maxlength="255" placeholder="e.g. Citizenship Front & Back">
            @error('title')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- File Upload --}}
<div class="eims-card p-6 space-y-4">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-violet-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">File</h3>
    </div>

    @isset($studentDocument)
        @if(storage_exists($studentDocument->file_path))
        <div class="flex items-center gap-2 text-sm text-slate-600 mb-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-violet-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
            Current:
            <a href="{{ storage_url($studentDocument->file_path) }}"
               target="_blank" class="text-blue-600 hover:underline truncate max-w-xs">
                {{ basename($studentDocument->file_path) }}
            </a>
            <span class="text-slate-400 text-xs">(upload new to replace)</span>
        </div>
        @endif
    @endisset

    <div>
        <label class="form-label" for="file_path">
            Document File
            @if(!isset($studentDocument)) <span class="text-red-500">*</span> @endif
            <span class="text-slate-400 font-normal">(PDF, JPG, PNG - max 5 MB)</span>
        </label>
        <input type="file" name="file_path" id="file_path"
            class="form-control @error('file_path') is-invalid @enderror"
            accept=".pdf,.jpg,.jpeg,.png"
            {{ !isset($studentDocument) ? 'required' : '' }}>
        @error('file_path')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Status + Remarks --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-amber-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Status &amp; Remarks</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="status">Status <span class="text-red-500">*</span></label>
            <select name="status" id="status" required
                class="form-control @error('status') is-invalid @enderror">
                @foreach($statuses as $val => $label)
                    <option value="{{ $val }}"
                        {{ old('status', $studentDocument->status ?? 'active') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="sm:col-span-2">
            <label class="form-label" for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks" rows="3"
                class="form-control @error('remarks') is-invalid @enderror"
                maxlength="1000">{{ old('remarks', $studentDocument->remarks ?? '') }}</textarea>
            @error('remarks')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
