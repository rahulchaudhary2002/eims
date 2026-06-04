{{--
    partials/form.blade.php
    Shared form fields for create and edit student profile.
    Variables:
      $studentProfile - StudentProfile|null (null on create)
      $students       - Collection<Student>
--}}

{{-- Student Selection --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Student</h3>
    </div>

    <div>
        <label class="form-label" for="student_id">Student <span class="text-red-500">*</span></label>
        <select name="student_id" id="student_id"
            class="form-control @error('student_id') is-invalid @enderror"
            {{ isset($studentProfile) ? 'disabled' : 'required' }}>
            <option value="">- Select student -</option>
            @foreach($students as $s)
                <option value="{{ $s->id }}"
                    {{ old('student_id', $studentProfile->student_id ?? '') == $s->id ? 'selected' : '' }}>
                    {{ $s->name }} ({{ $s->email }})
                </option>
            @endforeach
        </select>
        @isset($studentProfile)
            <input type="hidden" name="student_id" value="{{ $studentProfile->student_id }}">
        @endisset
        @error('student_id')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Guardian Information --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-emerald-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Guardian Information</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="guardian_name">Guardian Name</label>
            <input type="text" name="guardian_name" id="guardian_name"
                class="form-control @error('guardian_name') is-invalid @enderror"
                value="{{ old('guardian_name', $studentProfile->guardian_name ?? '') }}"
                maxlength="255">
            @error('guardian_name')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label" for="guardian_phone">Guardian Phone</label>
            <input type="text" name="guardian_phone" id="guardian_phone"
                class="form-control @error('guardian_phone') is-invalid @enderror"
                value="{{ old('guardian_phone', $studentProfile->guardian_phone ?? '') }}"
                maxlength="30">
            @error('guardian_phone')<p class="form-error">{{ $message }}</p>@enderror
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

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
            <label class="form-label" for="province">Province</label>
            <input type="text" name="province" id="province"
                class="form-control @error('province') is-invalid @enderror"
                value="{{ old('province', $studentProfile->province ?? '') }}"
                maxlength="100">
            @error('province')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label" for="district">District</label>
            <input type="text" name="district" id="district"
                class="form-control @error('district') is-invalid @enderror"
                value="{{ old('district', $studentProfile->district ?? '') }}"
                maxlength="100">
            @error('district')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label" for="city">City</label>
            <input type="text" name="city" id="city"
                class="form-control @error('city') is-invalid @enderror"
                value="{{ old('city', $studentProfile->city ?? '') }}"
                maxlength="100">
            @error('city')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div class="sm:col-span-3">
            <label class="form-label" for="address">Address</label>
            <textarea name="address" id="address"
                class="form-control @error('address') is-invalid @enderror"
                rows="2" maxlength="1000">{{ old('address', $studentProfile->address ?? '') }}</textarea>
            @error('address')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- Budget & Preferences --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-violet-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Budget &amp; Preferences</h3>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="budget_min">Minimum Budget (NPR)</label>
            <input type="number" name="budget_min" id="budget_min"
                class="form-control @error('budget_min') is-invalid @enderror"
                value="{{ old('budget_min', $studentProfile->budget_min ?? '') }}"
                min="0" step="1000">
            @error('budget_min')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label" for="budget_max">Maximum Budget (NPR)</label>
            <input type="number" name="budget_max" id="budget_max"
                class="form-control @error('budget_max') is-invalid @enderror"
                value="{{ old('budget_max', $studentProfile->budget_max ?? '') }}"
                min="0" step="1000">
            @error('budget_max')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div class="sm:col-span-2">
            <label class="form-label" for="preferred_location">Preferred Location</label>
            <input type="text" name="preferred_location" id="preferred_location"
                class="form-control @error('preferred_location') is-invalid @enderror"
                value="{{ old('preferred_location', $studentProfile->preferred_location ?? '') }}"
                maxlength="255" placeholder="e.g. Kathmandu, Pokhara">
            @error('preferred_location')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- Career Interests & Preferred Faculties (Alpine.js tag inputs) --}}
@php
    $careerInterestsJson    = json_encode(old('career_interests')
        ? (json_decode(old('career_interests'), true) ?: [])
        : ($studentProfile->career_interests ?? []));
    $preferredFacultiesJson = json_encode(old('preferred_faculties')
        ? (json_decode(old('preferred_faculties'), true) ?: [])
        : ($studentProfile->preferred_faculties ?? []));
@endphp

<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-rose-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-rose-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Interests &amp; Faculties</h3>
    </div>

    {{-- Career Interests --}}
    <div x-data="{
        items: {{ $careerInterestsJson }},
        input: '',
        add() {
            const v = this.input.trim();
            if (v && !this.items.includes(v)) { this.items.push(v); }
            this.input = '';
        },
        remove(i) { this.items.splice(i, 1); }
    }">
        <label class="form-label">Career Interests</label>
        <div class="flex flex-wrap gap-2 mb-2">
            <template x-for="(item, i) in items" :key="i">
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-rose-50 text-rose-700 border border-rose-200 rounded-full text-sm">
                    <span x-text="item"></span>
                    <button type="button" @click="remove(i)" class="ml-1 text-rose-400 hover:text-rose-700 leading-none">&times;</button>
                </span>
            </template>
        </div>
        <div class="flex gap-2">
            <input type="text" x-model="input" @keydown.enter.prevent="add()"
                class="form-control flex-1"
                placeholder="Type and press Enter to add…">
            <button type="button" @click="add()" class="btn btn-secondary text-sm px-3">Add</button>
        </div>
        <input type="hidden" name="career_interests" :value="JSON.stringify(items)">
        @error('career_interests')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    {{-- Preferred Faculties --}}
    <div x-data="{
        items: {{ $preferredFacultiesJson }},
        input: '',
        add() {
            const v = this.input.trim();
            if (v && !this.items.includes(v)) { this.items.push(v); }
            this.input = '';
        },
        remove(i) { this.items.splice(i, 1); }
    }">
        <label class="form-label">Preferred Faculties</label>
        <div class="flex flex-wrap gap-2 mb-2">
            <template x-for="(item, i) in items" :key="i">
                <span class="inline-flex items-center gap-1 px-3 py-1 bg-violet-50 text-violet-700 border border-violet-200 rounded-full text-sm">
                    <span x-text="item"></span>
                    <button type="button" @click="remove(i)" class="ml-1 text-violet-400 hover:text-violet-700 leading-none">&times;</button>
                </span>
            </template>
        </div>
        <div class="flex gap-2">
            <input type="text" x-model="input" @keydown.enter.prevent="add()"
                class="form-control flex-1"
                placeholder="Type and press Enter to add…">
            <button type="button" @click="add()" class="btn btn-secondary text-sm px-3">Add</button>
        </div>
        <input type="hidden" name="preferred_faculties" :value="JSON.stringify(items)">
        @error('preferred_faculties')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>
