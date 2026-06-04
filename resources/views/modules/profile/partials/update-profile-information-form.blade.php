<form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @method('patch')

    {{-- Avatar --}}
    <div class="flex items-center gap-5">
        <div class="flex-shrink-0">
            @if ($user->avatar)
                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                     class="w-20 h-20 rounded-full object-cover border-4 border-[#4299e1]/30">
            @else
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-[#2c5aa0] to-[#4299e1] flex items-center justify-center text-white text-2xl font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <div class="flex-1">
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Profile Photo</label>
            <input type="file" name="avatar" accept="image/jpeg,image/png,image/jpg,image/webp"
                   class="block w-full text-sm text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#4299e1] file:text-white hover:file:bg-[#2c5aa0] transition">
            <p class="text-xs text-gray-400 mt-1.5">Max 2 MB. JPEG, PNG, WebP.</p>
            @error('avatar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        {{-- Name --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   placeholder="Your full name"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('name') border-red-400 @enderror">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Phone --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Phone Number</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                   placeholder="+977 98XXXXXXXX"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('phone') border-red-400 @enderror">
            @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Email --}}
    <div>
        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
               placeholder="your@email.com"
               class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('email') border-red-400 @enderror">
        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        {{-- Date of Birth --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Date of Birth</label>
            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('date_of_birth') border-red-400 @enderror">
            @error('date_of_birth')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Gender --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Gender</label>
            <select name="gender"
                    class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition bg-white @error('gender') border-red-400 @enderror">
                <option value="">- Select -</option>
                <option value="male"   {{ old('gender', $user->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other"  {{ old('gender', $user->gender) === 'other'  ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
        <i class="fas fa-check"></i> Save Changes
    </button>
</form>
