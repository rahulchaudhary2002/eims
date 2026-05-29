{{-- Title --}}
<div class="md:col-span-2">
    <label for="title" class="form-label">Title <span class="text-red-500">*</span></label>
    <input type="text" id="title" name="title"
        value="{{ old('title', $post->title ?? '') }}"
        class="form-control @error('title') is-invalid @enderror"
        placeholder="Post title">
    @error('title') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Type --}}
<div>
    <label for="type" class="form-label">Type <span class="text-red-500">*</span></label>
    <select id="type" name="type" class="form-control @error('type') is-invalid @enderror">
        @foreach($types as $value => $label)
            <option value="{{ $value }}" {{ old('type', $post->type ?? 'article') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('type') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Institution --}}
<div>
    <label for="institution_id" class="form-label">Institution</label>
    <select id="institution_id" name="institution_id" class="form-control @error('institution_id') is-invalid @enderror">
        <option value="">Not linked</option>
        @foreach($institutions as $institution)
            <option value="{{ $institution->id }}"
                {{ old('institution_id', $post->institution_id ?? $selectedInstitutionId ?? '') == $institution->id ? 'selected' : '' }}>
                {{ $institution->name }}
            </option>
        @endforeach
    </select>
    @error('institution_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Created By --}}
<div>
    <label for="created_by" class="form-label">Author</label>
    <select id="created_by" name="created_by" class="form-control @error('created_by') is-invalid @enderror">
        <option value="">Select Author</option>
        @foreach($users as $user)
            <option value="{{ $user->id }}"
                {{ old('created_by', $post->created_by ?? $defaultCreatedBy ?? '') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
            </option>
        @endforeach
    </select>
    @error('created_by') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Published At --}}
<div>
    <label for="published_at" class="form-label">Publish Date</label>
    <input type="datetime-local" id="published_at" name="published_at"
        value="{{ old('published_at', isset($post) ? $post->published_at?->format('Y-m-d\TH:i') : '') }}"
        class="form-control @error('published_at') is-invalid @enderror">
    @error('published_at') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Thumbnail --}}
<div>
    <label for="thumbnail" class="form-label">Thumbnail <span class="text-slate-400 text-xs">(Image, max 5 MB)</span></label>
    @if(isset($post) && $post->thumbnail)
        <div class="mb-2">
            <img src="{{ Storage::url($post->thumbnail) }}" alt="Thumbnail" class="h-24 w-auto rounded-lg object-cover border border-slate-200">
            <p class="text-xs text-slate-400 mt-1">Upload a new image to replace it.</p>
        </div>
    @endif
    <input type="file" id="thumbnail" name="thumbnail" accept="image/*"
        class="form-control @error('thumbnail') is-invalid @enderror">
    @error('thumbnail') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Toggles --}}
<div class="flex flex-col gap-3 pt-1">
    <div class="flex items-center gap-3">
        <input type="hidden" name="is_published" value="0">
        <input type="checkbox" id="is_published" name="is_published" value="1"
            {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-slate-300 text-indigo-600">
        <label for="is_published" class="form-label mb-0">Published</label>
    </div>
    <div class="flex items-center gap-3">
        <input type="hidden" name="is_featured" value="0">
        <input type="checkbox" id="is_featured" name="is_featured" value="1"
            {{ old('is_featured', $post->is_featured ?? false) ? 'checked' : '' }}
            class="w-4 h-4 rounded border-slate-300 text-amber-500">
        <label for="is_featured" class="form-label mb-0">Featured</label>
    </div>
</div>

{{-- Content --}}
<div class="md:col-span-2">
    <label for="content" class="form-label">Content</label>
    <textarea id="content" name="content" rows="10"
        class="form-control @error('content') is-invalid @enderror"
        placeholder="Write the post content here...">{{ old('content', $post->content ?? '') }}</textarea>
    @error('content') <p class="form-error">{{ $message }}</p> @enderror
</div>
