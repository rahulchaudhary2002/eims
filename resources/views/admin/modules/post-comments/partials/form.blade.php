{{-- Post --}}
<div>
    <label for="post_id" class="form-label">Post <span class="text-red-500">*</span></label>
    <select id="post_id" name="post_id" class="form-control @error('post_id') is-invalid @enderror">
        <option value="">Select Post</option>
        @foreach($posts as $post)
            <option value="{{ $post->id }}"
                {{ old('post_id', $postComment->post_id ?? $selectedPostId ?? '') == $post->id ? 'selected' : '' }}>
                {{ $post->title }}
            </option>
        @endforeach
    </select>
    @error('post_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Parent Comment --}}
<div>
    <label for="parent_id" class="form-label">Reply To <span class="text-slate-400 text-xs">(optional - leave blank for top-level)</span></label>
    <input type="number" id="parent_id" name="parent_id"
        value="{{ old('parent_id', $postComment->parent_id ?? $selectedParentId ?? '') }}"
        class="form-control @error('parent_id') is-invalid @enderror"
        placeholder="Parent comment ID">
    @error('parent_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Commentable Type --}}
<div>
    <label for="commentable_type" class="form-label">Author Type <span class="text-red-500">*</span></label>
    <select id="commentable_type" name="commentable_type" class="form-control @error('commentable_type') is-invalid @enderror">
        <option value="">Select Type</option>
        @foreach($commentableTypes as $value => $label)
            <option value="{{ $value }}" {{ old('commentable_type', $postComment->commentable_type ?? '') === $value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    </select>
    @error('commentable_type') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Commentable ID --}}
<div>
    <label for="commentable_id" class="form-label">Author ID <span class="text-red-500">*</span></label>
    <input type="number" id="commentable_id" name="commentable_id"
        value="{{ old('commentable_id', $postComment->commentable_id ?? '') }}"
        class="form-control @error('commentable_id') is-invalid @enderror"
        placeholder="Student or User ID" min="1">
    @error('commentable_id') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Hidden --}}
<div class="flex items-center gap-3 pt-2">
    <input type="hidden" name="is_hidden" value="0">
    <input type="checkbox" id="is_hidden" name="is_hidden" value="1"
        {{ old('is_hidden', $postComment->is_hidden ?? false) ? 'checked' : '' }}
        class="w-4 h-4 rounded border-slate-300 text-red-500">
    <label for="is_hidden" class="form-label mb-0 text-red-600">Hidden (moderated)</label>
    @error('is_hidden') <p class="form-error">{{ $message }}</p> @enderror
</div>

{{-- Comment --}}
<div class="md:col-span-2">
    <label for="comment" class="form-label">Comment <span class="text-red-500">*</span></label>
    <textarea id="comment" name="comment" rows="5"
        class="form-control @error('comment') is-invalid @enderror"
        placeholder="Comment text...">{{ old('comment', $postComment->comment ?? '') }}</textarea>
    @error('comment') <p class="form-error">{{ $message }}</p> @enderror
</div>
