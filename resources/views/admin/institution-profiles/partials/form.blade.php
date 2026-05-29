{{--
    partials/form.blade.php
    Shared form fields for create and edit institution profile.
    Variables: $institutionProfile (optional — null on create), $institutions
--}}

{{-- Institution --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Institution</h3>
    </div>

    <div>
        <label class="form-label" for="institution_id">Institution <span class="text-red-500">*</span></label>
        <select name="institution_id" id="institution_id"
            class="form-control @error('institution_id') is-invalid @enderror"
            {{ isset($institutionProfile) ? 'disabled' : 'required' }}>
            <option value="">— Select institution —</option>
            @foreach($institutions as $inst)
                <option value="{{ $inst->id }}"
                    {{ old('institution_id', $institutionProfile->institution_id ?? '') == $inst->id ? 'selected' : '' }}>
                    {{ $inst->name }}
                </option>
            @endforeach
        </select>
        {{-- Re-send disabled value --}}
        @isset($institutionProfile)
            <input type="hidden" name="institution_id" value="{{ $institutionProfile->institution_id }}">
        @endisset
        @error('institution_id')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Amenities --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-emerald-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Amenities & Facilities</h3>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
        @foreach([
            'has_hostel'        => 'Hostel',
            'has_transportation'=> 'Transportation',
            'has_library'       => 'Library',
            'has_lab'           => 'Laboratory',
            'has_cafeteria'     => 'Cafeteria',
            'has_sports'        => 'Sports Facility',
            'has_scholarship'   => 'Scholarship',
        ] as $field => $label)
        <label class="flex items-center gap-2 p-3 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-50">
            <input type="checkbox" name="{{ $field }}" value="1"
                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                {{ old($field, $institutionProfile->$field ?? false) ? 'checked' : '' }}>
            <span class="text-sm text-slate-700">{{ $label }}</span>
        </label>
        @endforeach
    </div>
</div>

{{-- Tag Fields --}}
<div class="eims-card p-6 space-y-6">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-violet-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Details</h3>
        <p class="ml-auto text-xs text-slate-400">Type and press Enter to add items</p>
    </div>

    @foreach([
        'facilities'     => ['label' => 'Facilities', 'placeholder' => 'e.g. Swimming Pool'],
        'infrastructure' => ['label' => 'Infrastructure', 'placeholder' => 'e.g. Smart Classrooms'],
        'achievements'   => ['label' => 'Achievements', 'placeholder' => 'e.g. Best University 2024'],
        'accreditations' => ['label' => 'Accreditations', 'placeholder' => 'e.g. ISO 9001:2015'],
    ] as $field => $meta)
    @php
        $existingItems = old($field)
            ? (is_array(old($field)) ? old($field) : (json_decode(old($field), true) ?: []))
            : ($institutionProfile->$field ?? []);
        $existingItems = is_array($existingItems) ? $existingItems : [];
    @endphp
    <div>
        <label class="form-label">{{ $meta['label'] }}</label>
        <div x-data="{
            items: @json($existingItems),
            newItem: '',
            add() { if (this.newItem.trim()) { this.items.push(this.newItem.trim()); this.newItem = ''; } },
            remove(i) { this.items.splice(i, 1); }
        }">
            {{-- Tags display --}}
            <div class="flex flex-wrap gap-2 mb-2 min-h-[42px] p-2 border border-slate-200 rounded-lg bg-slate-50">
                <template x-for="(item, i) in items" :key="i">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white border border-slate-200 rounded-full text-sm text-slate-700 shadow-sm">
                        <span x-text="item"></span>
                        <button type="button" @click="remove(i)"
                            class="ml-0.5 text-slate-400 hover:text-red-500 rounded-full focus:outline-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </span>
                </template>
                <span x-show="items.length === 0" class="text-xs text-slate-400 self-center px-1">No items yet.</span>
            </div>
            {{-- Input row --}}
            <div class="flex gap-2">
                <input type="text" x-model="newItem"
                    @keydown.enter.prevent="add()"
                    class="form-control flex-1"
                    placeholder="{{ $meta['placeholder'] }}">
                <button type="button" @click="add()" class="btn btn-secondary whitespace-nowrap">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add
                </button>
            </div>
            {{-- Hidden JSON value --}}
            <input type="hidden" name="{{ $field }}" :value="JSON.stringify(items)">
        </div>
        @error($field)<p class="form-error">{{ $message }}</p>@enderror
    </div>
    @endforeach
</div>

{{-- Social Links --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-sky-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-sky-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Social Links</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach([
            'facebook_url'  => ['label' => 'Facebook URL', 'placeholder' => 'https://facebook.com/...'],
            'instagram_url' => ['label' => 'Instagram URL', 'placeholder' => 'https://instagram.com/...'],
            'linkedin_url'  => ['label' => 'LinkedIn URL', 'placeholder' => 'https://linkedin.com/...'],
            'youtube_url'   => ['label' => 'YouTube URL', 'placeholder' => 'https://youtube.com/...'],
        ] as $field => $meta)
        <div>
            <label class="form-label" for="{{ $field }}">{{ $meta['label'] }}</label>
            <input type="url" name="{{ $field }}" id="{{ $field }}"
                value="{{ old($field, $institutionProfile->$field ?? '') }}"
                class="form-control @error($field) is-invalid @enderror"
                placeholder="{{ $meta['placeholder'] }}">
            @error($field)<p class="form-error">{{ $message }}</p>@enderror
        </div>
        @endforeach
    </div>
</div>
