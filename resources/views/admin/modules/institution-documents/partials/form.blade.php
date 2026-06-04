{{--
    partials/form.blade.php
    Shared form fields for create and edit institution document.
    Variables:
      $document      - InstitutionDocument|null (null on create)
      $institutions  - Collection
      $documentTypes - array
      $statuses      - array
--}}

{{-- Institution & Type --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Document Details</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        {{-- Institution --}}
        <div>
            <label class="form-label" for="institution_id">Institution <span class="text-red-500">*</span></label>
            <select name="institution_id" id="institution_id"
                class="form-control @error('institution_id') is-invalid @enderror"
                {{ isset($document) ? 'disabled' : 'required' }}>
                <option value="">- Select institution -</option>
                @foreach($institutions as $inst)
                    <option value="{{ $inst->id }}"
                        {{ old('institution_id', $document->institution_id ?? '') == $inst->id ? 'selected' : '' }}>
                        {{ $inst->name }}
                    </option>
                @endforeach
            </select>
            @isset($document)
                <input type="hidden" name="institution_id" value="{{ $document->institution_id }}">
            @endisset
            @error('institution_id')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Document Type --}}
        <div>
            <label class="form-label" for="document_type">Document Type <span class="text-red-500">*</span></label>
            <select name="document_type" id="document_type"
                class="form-control @error('document_type') is-invalid @enderror" required>
                <option value="">- Select type -</option>
                @foreach($documentTypes as $value => $label)
                    <option value="{{ $value }}"
                        {{ old('document_type', $document->document_type ?? '') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('document_type')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Title --}}
        <div class="sm:col-span-2">
            <label class="form-label" for="title">Title <span class="text-red-500">*</span></label>
            <input type="text" name="title" id="title"
                class="form-control @error('title') is-invalid @enderror"
                value="{{ old('title', $document->title ?? '') }}"
                placeholder="e.g. University Registration Certificate 2024"
                required maxlength="255">
            @error('title')<p class="form-error">{{ $message }}</p>@enderror
        </div>

    </div>
</div>

{{-- File Upload --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-violet-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">File</h3>
    </div>

    <div>
        <label class="form-label" for="file_path">
            Document File
            @if(!isset($document)) <span class="text-red-500">*</span> @endif
        </label>

        @isset($document)
        <div class="mb-3 flex items-center gap-3 p-3 bg-slate-50 rounded-lg border border-slate-200">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            <span class="text-sm text-slate-600 truncate">{{ basename($document->file_path) }}</span>
            <a href="{{ Storage::url($document->file_path) }}" target="_blank" rel="noopener"
               class="ml-auto text-xs text-blue-600 hover:underline shrink-0">View current</a>
        </div>
        <p class="text-xs text-slate-400 mb-2">Leave blank to keep the existing file.</p>
        @endisset

        <input type="file" name="file_path" id="file_path"
            class="form-control @error('file_path') is-invalid @enderror"
            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp"
            {{ !isset($document) ? 'required' : '' }}>
        <p class="text-xs text-slate-400 mt-1">Accepted: PDF, DOC, DOCX, JPG, PNG, WEBP - max 10 MB</p>
        @error('file_path')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Status & Remarks --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-amber-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Status &amp; Remarks</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        {{-- Status --}}
        <div>
            <label class="form-label" for="status">Status <span class="text-red-500">*</span></label>
            <select name="status" id="status"
                class="form-control @error('status') is-invalid @enderror" required>
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}"
                        {{ old('status', $document->status ?? 'active') === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Remarks --}}
        <div class="sm:col-span-2">
            <label class="form-label" for="remarks">Remarks</label>
            <textarea name="remarks" id="remarks"
                class="form-control @error('remarks') is-invalid @enderror"
                rows="3" maxlength="1000"
                placeholder="Optional notes about this document…">{{ old('remarks', $document->remarks ?? '') }}</textarea>
            @error('remarks')<p class="form-error">{{ $message }}</p>@enderror
        </div>

    </div>
</div>
