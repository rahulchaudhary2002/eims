@php $profile = $user->profile; @endphp

<form method="post" action="{{ route('profile.update-extended') }}" class="space-y-5">
    @csrf
    @method('patch')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Guardian Name --}}
        <div>
            <x-input-label for="guardian_name" :value="__('Guardian Name')" />
            <x-text-input id="guardian_name" name="guardian_name" type="text" class="mt-1 block w-full"
                :value="old('guardian_name', $profile?->guardian_name)" placeholder="Parent / guardian name" />
            <x-input-error class="mt-2" :messages="$errors->get('guardian_name')" />
        </div>

        {{-- Guardian Phone --}}
        <div>
            <x-input-label for="guardian_phone" :value="__('Guardian Phone')" />
            <x-text-input id="guardian_phone" name="guardian_phone" type="text" class="mt-1 block w-full"
                :value="old('guardian_phone', $profile?->guardian_phone)" placeholder="+977 98XXXXXXXX" />
            <x-input-error class="mt-2" :messages="$errors->get('guardian_phone')" />
        </div>

        {{-- Province --}}
        <div>
            <x-input-label for="province" :value="__('Province')" />
            <x-text-input id="province" name="province" type="text" class="mt-1 block w-full"
                :value="old('province', $profile?->province)" placeholder="e.g. Bagmati" />
            <x-input-error class="mt-2" :messages="$errors->get('province')" />
        </div>

        {{-- District --}}
        <div>
            <x-input-label for="district" :value="__('District')" />
            <x-text-input id="district" name="district" type="text" class="mt-1 block w-full"
                :value="old('district', $profile?->district)" placeholder="e.g. Kathmandu" />
            <x-input-error class="mt-2" :messages="$errors->get('district')" />
        </div>

        {{-- City --}}
        <div>
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full"
                :value="old('city', $profile?->city)" placeholder="City / Municipality" />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        {{-- Preferred Study Location --}}
        <div>
            <x-input-label for="preferred_location" :value="__('Preferred Study Location')" />
            <x-text-input id="preferred_location" name="preferred_location" type="text" class="mt-1 block w-full"
                :value="old('preferred_location', $profile?->preferred_location)" placeholder="e.g. Kathmandu, Pokhara" />
            <x-input-error class="mt-2" :messages="$errors->get('preferred_location')" />
        </div>

        {{-- Budget Min --}}
        <div>
            <x-input-label for="budget_min" :value="__('Budget Min (NPR)')" />
            <x-text-input id="budget_min" name="budget_min" type="number" class="mt-1 block w-full"
                :value="old('budget_min', $profile?->budget_min)" placeholder="0" min="0" />
            <x-input-error class="mt-2" :messages="$errors->get('budget_min')" />
        </div>

        {{-- Budget Max --}}
        <div>
            <x-input-label for="budget_max" :value="__('Budget Max (NPR)')" />
            <x-text-input id="budget_max" name="budget_max" type="number" class="mt-1 block w-full"
                :value="old('budget_max', $profile?->budget_max)" placeholder="500000" min="0" />
            <x-input-error class="mt-2" :messages="$errors->get('budget_max')" />
        </div>
    </div>

    {{-- Address --}}
    <div>
        <x-input-label for="address" :value="__('Full Address')" />
        <textarea id="address" name="address" rows="2"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            placeholder="Street / Tole / Ward">{{ old('address', $profile?->address) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('address')" />
    </div>

    {{-- Career Interests --}}
    <div>
        <x-input-label :value="__('Career Interests')" />
        <p class="text-xs text-gray-400 mb-2">Enter each interest on a separate line.</p>
        <textarea id="career_interests_raw" name="career_interests_raw" rows="3"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            placeholder="Software Engineering&#10;Medicine&#10;Business">{{ old('career_interests_raw', implode("\n", $profile?->career_interests ?? [])) }}</textarea>
    </div>

    {{-- Preferred Faculties --}}
    <div>
        <x-input-label :value="__('Preferred Faculties / Programs')" />
        <p class="text-xs text-gray-400 mb-2">Enter each faculty on a separate line.</p>
        <textarea id="preferred_faculties_raw" name="preferred_faculties_raw" rows="3"
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm"
            placeholder="Science &amp; Technology&#10;Management">{{ old('preferred_faculties_raw', implode("\n", $profile?->preferred_faculties ?? [])) }}</textarea>
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ __('Save Details') }}</x-primary-button>
        @if(session('status') === 'profile-extended-updated')
            <p class="text-sm text-green-600" x-data="{show:true}" x-show="show" x-transition x-init="setTimeout(()=>show=false,2000)">Saved.</p>
        @endif
    </div>
</form>

{{-- Convert textarea lines to array inputs before submit --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('form[action="{{ route('profile.update-extended') }}"]');
    if (!form) return;
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // career_interests
        const ciRaw  = document.getElementById('career_interests_raw');
        const pfRaw  = document.getElementById('preferred_faculties_raw');

        // remove old hidden inputs
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
