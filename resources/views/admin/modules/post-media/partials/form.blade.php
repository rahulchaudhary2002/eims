{{-- Post --}}
<div>
    <label for="post_id" class="form-label">Post <span class="text-red-500">*</span></label>
    <select id="post_id" name="post_id" class="form-control @error('post_id') is-invalid @enderror">
        <option value="">Select Post</option>
        @foreach($posts as $post)
            <option value="{{ $post->id }}"
                {{ old('post_id', $postMedium->post_id ?? $selectedPostId ?? '') == $post->id ? 'selected' : '' }}>
                {{ $post->title }}
            </option>
        @endforeach
    </select>
    @error('post_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Type --}}
<div>
    <label for="type" class="form-label">Media Type <span class="text-red-500">*</span></label>
    <select id="type" name="type" class="form-control @error('type') is-invalid @enderror">
        @foreach($types as $value => $label)
            <option value="{{ $value }}" {{ old('type', $postMedium->type ?? 'image') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('type') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- File --}}
<div>
    <label for="file_path" class="form-label">
        File <span class="{{ isset($postMedium) ? 'text-slate-400 text-xs' : 'text-red-500' }}">{{ isset($postMedium) ? '(upload to replace)' : '*' }}</span>
    </label>
    @if(isset($postMedium) && storage_exists($postMedium->file_path))
        <div class="mb-2">
            @php $ext = pathinfo($postMedium->file_path, PATHINFO_EXTENSION); @endphp
            @if(in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']))
                <img src="{{ storage_url($postMedium->file_path) }}" alt="Preview" class="h-24 w-auto rounded-lg object-cover border border-slate-200">
            @else
                <p class="text-xs text-slate-500">
                    <a href="{{ storage_url($postMedium->file_path) }}" target="_blank" class="text-blue-600 hover:underline">{{ basename($postMedium->file_path) }}</a>
                </p>
            @endif
            <p class="text-xs text-slate-400 mt-1">Upload a new file to replace the current one.</p>
        </div>
    @endif
    <input type="file" id="file_path" name="file_path"
        class="form-control @error('file_path') is-invalid @enderror">
    <p class="text-xs text-slate-400 mt-1">Max file size: 50 MB.</p>
    @error('file_path') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Caption --}}
<div>
    <label for="caption" class="form-label">Caption</label>
    <input type="text" id="caption" name="caption"
        value="{{ old('caption', $postMedium->caption ?? '') }}"
        class="form-control @error('caption') is-invalid @enderror"
        placeholder="Optional description for this media">
    @error('caption') <p class="form-error">{{ $message }}</p> @enderror
</div>
