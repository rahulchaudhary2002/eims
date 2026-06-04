@php $profile = $user->profile; @endphp

<form method="post" action="{{ route('profile.update-extended') }}" class="space-y-5">
    @csrf
    @method('patch')

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        {{-- Guardian Name --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Guardian Name</label>
            <input type="text" name="guardian_name" value="{{ old('guardian_name', $profile?->guardian_name) }}"
                   placeholder="Parent / guardian name"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('guardian_name') border-red-400 @enderror">
            @error('guardian_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Guardian Phone --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Guardian Phone</label>
            <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $profile?->guardian_phone) }}"
                   placeholder="+977 98XXXXXXXX"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('guardian_phone') border-red-400 @enderror">
            @error('guardian_phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Province --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Province</label>
            <input type="text" name="province" value="{{ old('province', $profile?->province) }}"
                   placeholder="e.g. Bagmati"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('province') border-red-400 @enderror">
            @error('province')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- District --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">District</label>
            <input type="text" name="district" value="{{ old('district', $profile?->district) }}"
                   placeholder="e.g. Kathmandu"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('district') border-red-400 @enderror">
            @error('district')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- City --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">City</label>
            <input type="text" name="city" value="{{ old('city', $profile?->city) }}"
                   placeholder="City / Municipality"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('city') border-red-400 @enderror">
            @error('city')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Preferred Study Location --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Preferred Study Location</label>
            <input type="text" name="preferred_location" value="{{ old('preferred_location', $profile?->preferred_location) }}"
                   placeholder="e.g. Kathmandu, Pokhara"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('preferred_location') border-red-400 @enderror">
            @error('preferred_location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Budget Min --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Budget Min (NPR)</label>
            <input type="number" name="budget_min" value="{{ old('budget_min', $profile?->budget_min) }}"
                   placeholder="0" min="0"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('budget_min') border-red-400 @enderror">
            @error('budget_min')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        {{-- Budget Max --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Budget Max (NPR)</label>
            <input type="number" name="budget_max" value="{{ old('budget_max', $profile?->budget_max) }}"
                   placeholder="500000" min="0"
                   class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('budget_max') border-red-400 @enderror">
            @error('budget_max')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Address --}}
    <div>
        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Full Address</label>
        <textarea name="address" rows="2" placeholder="Street / Tole / Ward"
                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition resize-none @error('address') border-red-400 @enderror">{{ old('address', $profile?->address) }}</textarea>
        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        {{-- Career Interests --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Career Interests</label>
            <p class="text-xs text-gray-400 mb-2">One interest per line.</p>
            <textarea id="career_interests_raw" name="career_interests_raw" rows="4"
                      placeholder="Software Engineering&#10;Medicine&#10;Business"
                      class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition resize-none">{{ old('career_interests_raw', implode("\n", $profile?->career_interests ?? [])) }}</textarea>
        </div>

        {{-- Preferred Faculties --}}
        <div>
            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Preferred Faculties / Programs</label>
            <p class="text-xs text-gray-400 mb-2">One faculty per line.</p>
            <textarea id="preferred_faculties_raw" name="preferred_faculties_raw" rows="4"
                      placeholder="Science &amp; Technology&#10;Management"
                      class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition resize-none">{{ old('preferred_faculties_raw', implode("\n", $profile?->preferred_faculties ?? [])) }}</textarea>
        </div>
    </div>

    <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white text-sm font-bold rounded-xl shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-0.5">
        <i class="fas fa-check"></i> Save Details
    </button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action="{{ route('profile.update-extended') }}"]');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const ciRaw = document.getElementById('career_interests_raw');
        const pfRaw = document.getElementById('preferred_faculties_raw');
        form.querySelectorAll('input[name^="career_interests"], input[name^="preferred_faculties"]').forEach(el => el.remove());
        if (ciRaw) {
            ciRaw.value.split('\n').map(s => s.trim()).filter(Boolean).forEach((val, i) => {
                const inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = `career_interests[${i}]`; inp.value = val;
                form.appendChild(inp);
            });
        }
        if (pfRaw) {
            pfRaw.value.split('\n').map(s => s.trim()).filter(Boolean).forEach((val, i) => {
                const inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = `preferred_faculties[${i}]`; inp.value = val;
                form.appendChild(inp);
            });
        }
        form.submit();
    });
});
</script>
