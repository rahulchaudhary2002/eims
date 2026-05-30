{{--
    partials/form.blade.php
    Shared form fields for create and edit user.
    Variables: $user (optional - null on create), $institutions (Collection<Institution>)
--}}

{{-- Profile --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Profile</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Name --}}
        <div>
            <label class="form-label" for="name">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name"
                value="{{ old('name', $user->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Full name" required>
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="form-label" for="email">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email"
                value="{{ old('email', $user->email ?? '') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="user@example.com" required>
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Phone --}}
        <div>
            <label class="form-label" for="phone">Phone</label>
            <input type="text" name="phone" id="phone"
                value="{{ old('phone', $user->phone ?? '') }}"
                class="form-control @error('phone') is-invalid @enderror"
                placeholder="+977 98XXXXXXXX">
            @error('phone')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Email Verified At --}}
        <div>
            <label class="form-label" for="email_verified_at">Email Verified At</label>
            <input type="datetime-local" name="email_verified_at" id="email_verified_at"
                value="{{ old('email_verified_at', isset($user) && $user->email_verified_at ? $user->email_verified_at->format('Y-m-d\TH:i') : '') }}"
                class="form-control @error('email_verified_at') is-invalid @enderror">
            @error('email_verified_at')<p class="form-error">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

{{-- Password --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-amber-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
        </div>
        <div>
            <h3 class="text-base font-semibold text-slate-800">Password</h3>
            @isset($user)
            <p class="text-xs text-slate-400 mt-0.5">Leave blank to keep the current password.</p>
            @else
            <p class="text-xs text-slate-400 mt-0.5">Minimum 8 characters.</p>
            @endisset
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="password">Password @unless(isset($user))<span class="text-red-500">*</span>@endunless</label>
            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="••••••••"
                @unless(isset($user)) required @endunless>
            @error('password')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label" for="password_confirmation">Confirm Password @unless(isset($user))<span class="text-red-500">*</span>@endunless</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control"
                placeholder="••••••••"
                @unless(isset($user)) required @endunless>
        </div>
    </div>
</div>

{{-- Avatar --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-purple-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Avatar</h3>
    </div>

    @if(isset($user) && $user->avatar)
    <div class="flex items-center gap-4">
        <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
             class="w-16 h-16 rounded-full object-cover border-2 border-slate-200">
        <p class="text-sm text-slate-500">Upload a new image to replace the current avatar.</p>
    </div>
    @endif

    <div>
        <label class="form-label" for="avatar">Avatar Image</label>
        <input type="file" name="avatar" id="avatar"
            class="form-control @error('avatar') is-invalid @enderror"
            accept="image/jpeg,image/png,image/jpg,image/webp">
        <p class="text-xs text-slate-400 mt-1">Max 2 MB. Formats: JPEG, PNG, WebP.</p>
        @error('avatar')<p class="form-error">{{ $message }}</p>@enderror
    </div>
</div>

{{-- Institution Access --}}
@php
    // Build existing assignments from loaded relation (edit) or old() flash (validation failure)
    $existingAssignments = [];
    $primaryId = null;

    if (old('institutions')) {
        $primaryId = old('primary_institution_id') ? (int) old('primary_institution_id') : null;
        foreach (old('institutions', []) as $row) {
            if (empty($row['institution_id'])) continue;
            $instId = (int) $row['institution_id'];
            $instName = ($institutions ?? collect())->firstWhere('id', $instId)?->name ?? "Institution #{$instId}";
            $existingAssignments[] = [
                'institution_id' => $instId,
                'name'           => $instName,
                'role_name'      => $row['role_name'] ?? 'staff',
                'position'       => $row['position'] ?? '',
                'is_active'      => ($row['is_active'] ?? '1') === '1' ? '1' : '0',
                'joined_at'      => $row['joined_at'] ?? '',
            ];
        }
    } elseif (isset($user) && $user->relationLoaded('institutions')) {
        foreach ($user->institutions as $inst) {
            $existingAssignments[] = [
                'institution_id' => $inst->id,
                'name'           => $inst->name,
                'role_name'      => $inst->pivot->role_name ?? 'staff',
                'position'       => $inst->pivot->position ?? '',
                'is_active'      => $inst->pivot->is_active ? '1' : '0',
                'joined_at'      => $inst->pivot->joined_at ? $inst->pivot->joined_at->format('Y-m-d\TH:i') : '',
            ];
            if ($inst->pivot->is_primary) {
                $primaryId = $inst->id;
            }
        }
    }

    if ($primaryId === null && count($existingAssignments) > 0) {
        $primaryId = $existingAssignments[0]['institution_id'];
    }

    $allInstitutions = ($institutions ?? collect())->map(fn($i) => ['id' => $i->id, 'name' => $i->name])->values();
    $institutionRoles = \App\Models\UserInstitution::ROLES;
@endphp

@error('institutions')<p class="form-error mb-2">{{ $message }}</p>@enderror

<div class="eims-card p-6 space-y-5"
     x-data="{
        allInstitutions: {{ $allInstitutions->toJson() }},
        institutionRoles: {{ collect($institutionRoles)->map(fn($label, $value) => ['value' => $value, 'label' => $label])->values()->toJson() }},
        assignments: {{ json_encode($existingAssignments) }},
        primaryId: {{ json_encode($primaryId) }},
        newInstId: '',
        get availableToAdd() {
            const used = new Set(this.assignments.map(a => a.institution_id));
            return this.allInstitutions.filter(i => !used.has(i.id));
        },
        addRow() {
            if (!this.newInstId) return;
            const id = parseInt(this.newInstId);
            const inst = this.allInstitutions.find(i => i.id === id);
            if (!inst) return;
            this.assignments.push({ institution_id: id, name: inst.name, role_name: 'staff', position: '', is_active: '1', joined_at: '' });
            if (this.assignments.length === 1) this.primaryId = id;
            this.newInstId = '';
        },
        removeRow(idx) {
            const removed = this.assignments.splice(idx, 1)[0];
            if (this.primaryId == removed.institution_id) {
                this.primaryId = this.assignments.length > 0 ? this.assignments[0].institution_id : null;
            }
        },
        setPrimary(id) { this.primaryId = id; }
     }">

    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-indigo-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
        </div>
        <div>
            <h3 class="text-base font-semibold text-slate-800">Institution Access</h3>
            <p class="text-xs text-slate-400 mt-0.5">Assign institutions this user can access. One may be marked Primary.</p>
        </div>
    </div>

    <input type="hidden" name="primary_institution_id" :value="primaryId">

    <div x-show="assignments.length > 0" class="overflow-x-auto">
        <table class="w-full text-sm border-collapse">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="text-left py-2 px-2 text-xs font-semibold text-slate-500 uppercase tracking-wide w-[180px]">Institution</th>
                    <th class="text-left py-2 px-2 text-xs font-semibold text-slate-500 uppercase tracking-wide">Role</th>
                    <th class="text-left py-2 px-2 text-xs font-semibold text-slate-500 uppercase tracking-wide">Position</th>
                    <th class="text-left py-2 px-2 text-xs font-semibold text-slate-500 uppercase tracking-wide">Joined</th>
                    <th class="text-center py-2 px-2 text-xs font-semibold text-slate-500 uppercase tracking-wide">Active</th>
                    <th class="text-center py-2 px-2 text-xs font-semibold text-slate-500 uppercase tracking-wide">Primary</th>
                    <th class="w-8"></th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, idx) in assignments" :key="row.institution_id">
                    <tr class="border-b border-slate-50 hover:bg-slate-50/50">
                        <td class="py-2 px-2">
                            <input type="hidden" :name="`institutions[${idx}][institution_id]`" :value="row.institution_id">
                            <span class="font-medium text-slate-700" x-text="row.name"></span>
                        </td>
                        <td class="py-2 px-2">
                            <select :name="`institutions[${idx}][role_name]`" x-model="row.role_name" class="form-control py-1 text-sm w-full min-w-[150px]">
                                <template x-for="role in institutionRoles" :key="role.value">
                                    <option :value="role.value" x-text="role.label"></option>
                                </template>
                            </select>
                        </td>
                        <td class="py-2 px-2">
                            <input type="text" :name="`institutions[${idx}][position]`" x-model="row.position"
                                class="form-control py-1 text-sm w-full min-w-[90px]" placeholder="e.g. Principal">
                        </td>
                        <td class="py-2 px-2">
                            <input type="datetime-local" :name="`institutions[${idx}][joined_at]`" x-model="row.joined_at"
                                class="form-control py-1 text-sm w-full min-w-[160px]">
                        </td>
                        <td class="py-2 px-2 text-center">
                            <select :name="`institutions[${idx}][is_active]`" x-model="row.is_active" class="form-control py-1 text-sm">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </td>
                        <td class="py-2 px-2 text-center">
                            <button type="button" @click="setPrimary(row.institution_id)"
                                :class="primaryId == row.institution_id ? 'text-emerald-600' : 'text-slate-300 hover:text-slate-500'"
                                title="Set as primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto"
                                    :fill="primaryId == row.institution_id ? 'currentColor' : 'none'"
                                    viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
                                </svg>
                            </button>
                        </td>
                        <td class="py-2 px-2">
                            <button type="button" @click="removeRow(idx)"
                                class="text-red-400 hover:text-red-600 p-1" title="Remove">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <div x-show="assignments.length === 0" class="text-sm text-slate-400 py-2">
        No institutions assigned. Use the control below to add one.
    </div>

    <div class="flex items-end gap-3 pt-2 border-t border-slate-100">
        <div class="flex-1">
            <label class="form-label text-xs">Add Institution</label>
            <select x-model="newInstId" class="form-control">
                <option value="">- Select institution -</option>
                <template x-for="inst in availableToAdd" :key="inst.id">
                    <option :value="inst.id" x-text="inst.name"></option>
                </template>
            </select>
        </div>
        <button type="button" @click="addRow()" :disabled="!newInstId"
            class="btn btn-primary disabled:opacity-50">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add
        </button>
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

    <div class="flex flex-col gap-4">
        <label class="flex items-center gap-3 cursor-pointer select-none">
            <input type="checkbox" name="is_active" value="1"
                class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                {{ old('is_active', $user->is_active ?? true) ? 'checked' : '' }}>
            <div>
                <span class="text-sm font-medium text-slate-700">Active</span>
                <p class="text-xs text-slate-400">Allow this user to log in.</p>
            </div>
        </label>
    </div>
</div>
