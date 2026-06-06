{{--
    partials/form.blade.php
    Shared form fields for create and edit institution.
    Variables: $institution (optional), $types, $statuses, $parents
--}}

{{-- Basic Information --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Basic Information</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Name --}}
        <div>
            <label class="form-label">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name"
                value="{{ old('name', $institution->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="e.g. Kathmandu University" required>
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Slug --}}
        <div>
            <label class="form-label">Slug</label>
            <input type="text" name="slug" id="slug"
                value="{{ old('slug', $institution->slug ?? '') }}"
                class="form-control @error('slug') is-invalid @enderror"
                placeholder="Auto-generated if blank">
            @error('slug')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Type --}}
        <div>
            <label class="form-label">Type <span class="text-red-500">*</span></label>
            <select name="type" class="form-control @error('type') is-invalid @enderror" required>
                <option value="">Select type</option>
                @foreach($types as $value => $label)
                <option value="{{ $value }}" {{ old('type', $institution->type ?? '') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
            @error('type')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Code --}}
        <div>
            <label class="form-label">Code</label>
            <input type="text" name="code"
                value="{{ old('code', $institution->code ?? '') }}"
                class="form-control @error('code') is-invalid @enderror"
                placeholder="e.g. KU, TU, PU">
            @error('code')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Parent Institution --}}
        <div>
            <label class="form-label">Parent Institution</label>
            <select name="parent_id" class="form-control @error('parent_id') is-invalid @enderror">
                <option value="">None (top-level)</option>
                @foreach($parents as $id => $pname)
                <option value="{{ $id }}" {{ old('parent_id', $institution->parent_id ?? '') == $id ? 'selected' : '' }}>
                    {{ $pname }}
                </option>
                @endforeach
            </select>
            @error('parent_id')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Established Year --}}
        <div>
            <label class="form-label">Established Year</label>
            <input type="number" name="established_year"
                value="{{ old('established_year', $institution->established_year ?? '') }}"
                class="form-control @error('established_year') is-invalid @enderror"
                placeholder="e.g. 1995" min="1800" max="{{ date('Y') + 1 }}">
            @error('established_year')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Short Description --}}
    <div>
        <label class="form-label">Short Description</label>
        <input type="text" name="short_description"
            value="{{ old('short_description', $institution->short_description ?? '') }}"
            class="form-control @error('short_description') is-invalid @enderror"
            placeholder="One-line summary (max 500 chars)" maxlength="500">
        @error('short_description')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Description --}}
    <div>
        <label class="form-label">Description</label>
        <textarea name="description" id="description" rows="5"
            class="form-control ckeditor @error('description') is-invalid @enderror"
            placeholder="Full description of the institution...">{{ old('description', $institution->description ?? '') }}</textarea>
        @error('description')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Contact --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-green-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Contact Information</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="form-label">Email</label>
            <input type="email" name="email"
                value="{{ old('email', $institution->email ?? '') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="contact@institution.edu.np">
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Phone</label>
            <input type="text" name="phone"
                value="{{ old('phone', $institution->phone ?? '') }}"
                class="form-control @error('phone') is-invalid @enderror"
                placeholder="+977-01-XXXXXXX">
            @error('phone')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div class="md:col-span-2">
            <label class="form-label">Website</label>
            <input type="url" name="website"
                value="{{ old('website', $institution->website ?? '') }}"
                class="form-control @error('website') is-invalid @enderror"
                placeholder="https://www.institution.edu.np">
            @error('website')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- Location --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-amber-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Location</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="form-label">Country</label>
            <input type="text" name="country"
                value="{{ old('country', $institution->country ?? 'Nepal') }}"
                class="form-control @error('country') is-invalid @enderror">
            @error('country')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Province</label>
            <input type="text" name="province"
                value="{{ old('province', $institution->province ?? '') }}"
                class="form-control @error('province') is-invalid @enderror"
                placeholder="e.g. Bagmati Province">
            @error('province')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">District</label>
            <input type="text" name="district"
                value="{{ old('district', $institution->district ?? '') }}"
                class="form-control @error('district') is-invalid @enderror"
                placeholder="e.g. Kathmandu">
            @error('district')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">City</label>
            <input type="text" name="city"
                value="{{ old('city', $institution->city ?? '') }}"
                class="form-control @error('city') is-invalid @enderror"
                placeholder="e.g. Kathmandu">
            @error('city')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div class="md:col-span-2">
            <label class="form-label">Address</label>
            <textarea name="address" rows="2"
                class="form-control @error('address') is-invalid @enderror"
                placeholder="Full street address">{{ old('address', $institution->address ?? '') }}</textarea>
            @error('address')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Latitude</label>
            <input type="number" step="any" name="latitude"
                value="{{ old('latitude', $institution->latitude ?? '') }}"
                class="form-control @error('latitude') is-invalid @enderror"
                placeholder="e.g. 27.7172">
            @error('latitude')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label">Longitude</label>
            <input type="number" step="any" name="longitude"
                value="{{ old('longitude', $institution->longitude ?? '') }}"
                class="form-control @error('longitude') is-invalid @enderror"
                placeholder="e.g. 85.3240">
            @error('longitude')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- Media --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-purple-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Media</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Logo --}}
        <div>
            <label class="form-label">Logo</label>
            @if(isset($institution) && storage_exists($institution->logo))
            <div class="mb-2 flex items-center gap-3">
                <img src="{{ storage_url($institution->logo) }}" alt="Logo" class="h-16 w-16 object-contain rounded border border-slate-200 bg-slate-50 p-1">
                <span class="text-xs text-slate-500">Current logo</span>
            </div>
            @endif
            <input type="file" name="logo" accept="image/*"
                class="form-control @error('logo') is-invalid @enderror">
            <p class="text-xs text-slate-400 mt-1">JPEG, PNG, WebP · max 2 MB</p>
            @error('logo')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Cover Image --}}
        <div>
            <label class="form-label">Cover Image</label>
            @if(isset($institution) && storage_exists($institution->cover_image))
            <div class="mb-2">
                <img src="{{ storage_url($institution->cover_image) }}" alt="Cover" class="h-16 w-full object-cover rounded border border-slate-200">
            </div>
            @endif
            <input type="file" name="cover_image" accept="image/*"
                class="form-control @error('cover_image') is-invalid @enderror">
            <p class="text-xs text-slate-400 mt-1">JPEG, PNG, WebP · max 5 MB</p>
            @error('cover_image')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- Settings --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-slate-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Settings</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Status --}}
        <div>
            <label class="form-label">Status <span class="text-red-500">*</span></label>
            <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                @foreach($statuses as $value => $label)
                <option value="{{ $value }}" {{ old('status', $institution->status ?? 'active') === $value ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
            @error('status')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Sort Order --}}
        <div>
            <label class="form-label">Sort Order</label>
            <input type="number" name="sort_order" min="0"
                value="{{ old('sort_order', $institution->sort_order ?? 0) }}"
                class="form-control @error('sort_order') is-invalid @enderror">
            @error('sort_order')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Checkboxes --}}
    <div class="flex flex-wrap gap-6 pt-2">
        <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="hidden" name="is_verified" value="0">
            <input type="checkbox" name="is_verified" value="1"
                class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500"
                {{ old('is_verified', $institution->is_verified ?? false) ? 'checked' : '' }}>
            <span class="text-sm font-medium text-slate-700">Verified Institution</span>
        </label>
        <label class="flex items-center gap-2.5 cursor-pointer">
            <input type="hidden" name="is_featured" value="0">
            <input type="checkbox" name="is_featured" value="1"
                class="w-4 h-4 rounded border-slate-300 text-primary-600 focus:ring-primary-500"
                {{ old('is_featured', $institution->is_featured ?? false) ? 'checked' : '' }}>
            <span class="text-sm font-medium text-slate-700">Featured</span>
        </label>
    </div>
</div>
