@php $doc = $doc ?? null; @endphp

<div>
    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Document Type <span class="text-red-500">*</span></label>
    <select name="document_type" class="w-full px-4 py-3 text-sm border {{ $errors->has('document_type') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]">
        <option value="">Select type</option>
        @foreach(\App\Models\StudentDocument::DOCUMENT_TYPES as $val => $label)
            <option value="{{ $val }}" {{ old('document_type', $doc?->document_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
    @error('document_type')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Title <span class="text-red-500">*</span></label>
    <input type="text" name="title" value="{{ old('title', $doc?->title) }}"
           placeholder="e.g. Citizenship Front, Passport Photo"
           class="w-full px-4 py-3 text-sm border {{ $errors->has('title') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]">
    @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
</div>

<div>
    <label class="block text-xs font-semibold text-gray-600 mb-1.5">File {{ $doc ? '' : '*' }}</label>
    <input type="file" name="file_path" accept=".pdf,.jpg,.jpeg,.png"
           class="block w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-[#ebf8ff] file:text-[#2c5aa0] hover:file:bg-[#bee3f8]">
    @if($doc?->file_path)
        <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="text-xs text-[#4299e1] hover:underline mt-1 block no-underline">View current file</a>
    @endif
    @error('file_path')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    <p class="text-xs text-gray-400 mt-1">PDF, JPG, PNG. Max 10MB.</p>
</div>

<div>
    <label class="block text-xs font-semibold text-gray-600 mb-1.5">Remarks</label>
    <textarea name="remarks" rows="2"
              class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]"
              placeholder="Optional notes">{{ old('remarks', $doc?->remarks) }}</textarea>
</div>
