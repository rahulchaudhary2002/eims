{{--
    partials/form.blade.php
    Shared form fields for create and edit student.
    Variables: $student (optional — null on create)
--}}

{{-- Profile --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-blue-50 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Profile</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Name --}}
        <div>
            <label class="form-label" for="name">Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" id="name"
                value="{{ old('name', $student->name ?? '') }}"
                class="form-control @error('name') is-invalid @enderror"
                placeholder="Full name" required>
            @error('name')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Email --}}
        <div>
            <label class="form-label" for="email">Email <span class="text-red-500">*</span></label>
            <input type="email" name="email" id="email"
                value="{{ old('email', $student->email ?? '') }}"
                class="form-control @error('email') is-invalid @enderror"
                placeholder="student@example.com" required>
            @error('email')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Phone --}}
        <div>
            <label class="form-label" for="phone">Phone</label>
            <input type="text" name="phone" id="phone"
                value="{{ old('phone', $student->phone ?? '') }}"
                class="form-control @error('phone') is-invalid @enderror"
                placeholder="+977 98XXXXXXXX">
            @error('phone')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Gender --}}
        <div>
            <label class="form-label" for="gender">Gender</label>
            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror">
                <option value="">— Select gender —</option>
                <option value="male"   {{ old('gender', $student->gender ?? '') === 'male'   ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $student->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other"  {{ old('gender', $student->gender ?? '') === 'other'  ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Date of Birth --}}
        <div>
            <label class="form-label" for="date_of_birth">Date of Birth</label>
            <input type="date" name="date_of_birth" id="date_of_birth"
                value="{{ old('date_of_birth', isset($student) && $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}"
                class="form-control @error('date_of_birth') is-invalid @enderror"
                max="{{ date('Y-m-d', strtotime('-1 day')) }}">
            @error('date_of_birth')<p class="form-error">{{ $message }}</p>@enderror
        </div>

        {{-- Email Verified At --}}
        <div>
            <label class="form-label" for="email_verified_at">Email Verified At</label>
            <input type="datetime-local" name="email_verified_at" id="email_verified_at"
                value="{{ old('email_verified_at', isset($student) && $student->email_verified_at ? $student->email_verified_at->format('Y-m-d\TH:i') : '') }}"
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
            @isset($student)
            <p class="text-xs text-slate-400 mt-0.5">Leave blank to keep the current password.</p>
            @else
            <p class="text-xs text-slate-400 mt-0.5">Minimum 8 characters.</p>
            @endisset
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div>
            <label class="form-label" for="password">Password @unless(isset($student))<span class="text-red-500">*</span>@endunless</label>
            <input type="password" name="password" id="password"
                class="form-control @error('password') is-invalid @enderror"
                placeholder="••••••••"
                @unless(isset($student)) required @endunless>
            @error('password')<p class="form-error">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="form-label" for="password_confirmation">Confirm Password @unless(isset($student))<span class="text-red-500">*</span>@endunless</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                class="form-control"
                placeholder="••••••••"
                @unless(isset($student)) required @endunless>
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

    @if(isset($student) && $student->avatar)
    <div class="flex items-center gap-4">
        <img src="{{ Storage::url($student->avatar) }}" alt="Avatar"
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

{{-- Settings --}}
<div class="eims-card p-6 space-y-5">
    <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
        <div class="p-2 bg-slate-100 rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <h3 class="text-base font-semibold text-slate-800">Settings</h3>
    </div>

    <label class="flex items-center gap-3 cursor-pointer select-none">
        <input type="checkbox" name="is_active" value="1"
            class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
            {{ old('is_active', $student->is_active ?? true) ? 'checked' : '' }}>
        <div>
            <span class="text-sm font-medium text-slate-700">Active</span>
            <p class="text-xs text-slate-400">Allow this student to log in.</p>
        </div>
    </label>
</div>
