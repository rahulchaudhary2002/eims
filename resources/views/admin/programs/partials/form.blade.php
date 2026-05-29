{{--
    partials/form.blade.php — shared fields for create / edit program.
    Variables: $program (Program|null), $faculties (Collection), $levels (Collection keyed name=>name)
--}}

<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-indigo-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Program Information</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

        <div>
            <label class="form-label" for="faculty_id">Faculty <span class="text-red-500">*</span></label>
            <select name="faculty_id" id="faculty_id" required
                class="form-control @error('faculty_id') is-invalid @enderror">
                <option value="">— Select faculty —</option>
                @foreach($faculties as $f)
                    <option value="{{ $f->id }}"
                        {{ old('faculty_id', $program->faculty_id ?? '') == $f->id ? 'selected' : '' }}>
                        {{ $f->name }}
                    </option>
                @endforeach
            </select>
            @error('faculty_id')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="level">Level</label>
            <select name="level" id="level"
                class="form-control @error('level') is-invalid @enderror">
                <option value="">— None —</option>
                @foreach($levels as $val => $label)
                    <option value="{{ $val }}"
                        {{ old('level', $program->level ?? '') === $val ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('level')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="form-label" for="name">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name" required
                class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $program->name ?? '') }}"
                maxlength="255"
                placeholder="e.g. Bachelor of Science in Computer Science"
                x-on:input="if (!slugEdited) { $refs.slugInput.value = $event.target.value.toLowerCase().replace(/[^a-z0-9\s-]/g,'').replace(/\s+/g,'-').replace(/-+/g,'-').replace(/^-|-$/g,''); }">
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div x-data="{ slugEdited: {{ old('slug', isset($program) ? $program->slug : '') ? 'true' : 'false' }} }">
            <label class="form-label" for="slug">Slug</label>
            <input type="text" name="slug" id="slug" x-ref="slugInput"
                class="form-control @error('slug') is-invalid @enderror"
                value="{{ old('slug', $program->slug ?? '') }}"
                maxlength="255"
                placeholder="auto-generated from name"
                pattern="[a-z0-9\-]+"
                title="Only lowercase letters, numbers and hyphens"
                x-on:input="slugEdited = true">
            <p class="text-xs text-slate-400 mt-1">Leave blank to auto-generate.</p>
            @error('slug')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        <div class="sm:col-span-2">
            <label class="form-label" for="description">Description</label>
            <textarea name="description" id="description" rows="4"
                class="form-control @error('description') is-invalid @enderror"
                maxlength="5000"
                placeholder="Optional program description…">{{ old('description', $program->description ?? '') }}</textarea>
            @error('description')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    <div>
        <label class="flex items-center gap-3 cursor-pointer w-fit">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="w-4 h-4 rounded accent-indigo-600"
                {{ old('is_active', ($program->is_active ?? true) ? '1' : '0') == '1' ? 'checked' : '' }}>
            <span class="text-sm font-medium text-slate-700">Active</span>
        </label>
        @error('is_active')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>
