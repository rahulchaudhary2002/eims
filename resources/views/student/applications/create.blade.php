@extends('layouts.student')

@section('title', 'New Application')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.applications.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">New Application</h1>
                <p class="text-white/70 text-sm mt-1">Apply to an institution program</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                <form method="POST" action="{{ route('student.applications.store') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Institution <span class="text-red-500">*</span></label>
                        <select name="institution_id" id="institution-select"
                                class="w-full px-4 py-3 text-sm border {{ $errors->has('institution_id') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]">
                            <option value="">Select institution</option>
                            @foreach($institutions as $inst)
                                <option value="{{ $inst->id }}" {{ $selectedInstitution?->id == $inst->id ? 'selected' : '' }}>{{ $inst->name }}</option>
                            @endforeach
                        </select>
                        @error('institution_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Type <span class="text-red-500">*</span></label>
                        <select name="applicable_type" id="type-select"
                                class="w-full px-4 py-3 text-sm border {{ $errors->has('applicable_type') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]"
                                onchange="filterApplicables()">
                            <option value="">Select type</option>
                            @foreach(\App\Models\Application::APPLICABLE_TYPES as $typeClass => $typeLabel)
                                <option value="{{ $typeClass }}" {{ old('applicable_type') === $typeClass ? 'selected' : '' }}>{{ $typeLabel }}</option>
                            @endforeach
                        </select>
                        @error('applicable_type')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Item <span class="text-red-500">*</span></label>
                        <select name="applicable_id" id="applicable-select"
                                class="w-full px-4 py-3 text-sm border {{ $errors->has('applicable_id') ? 'border-red-400' : 'border-gray-200' }} rounded-xl focus:outline-none focus:border-[#4299e1]">
                            <option value="">Select type first</option>
                            @if($selectedApplicable)
                                <option value="{{ $selectedApplicable->id }}" selected>{{ method_exists($selectedApplicable, 'getDisplayNameAttribute') ? $selectedApplicable->display_name : $selectedApplicable->title }}</option>
                            @endif
                        </select>
                        @error('applicable_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Scholarship (optional)</label>
                        <select name="scholarship_id" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
                            <option value="">No scholarship</option>
                            @foreach($scholarships as $sc)
                                <option value="{{ $sc->id }}" {{ $selectedScholarship?->id == $sc->id ? 'selected' : '' }}>{{ $sc->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Source</label>
                        <select name="source" class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]">
                            @foreach(\App\Models\Application::SOURCES as $val => $label)
                                <option value="{{ $val }}" {{ old('source', 'direct') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Message to Institution</label>
                        <textarea name="student_message" rows="4"
                                  class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:border-[#4299e1]"
                                  placeholder="Tell the institution about yourself and your goals...">{{ old('student_message') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <a href="{{ route('student.applications.index') }}"
                           class="px-5 py-2.5 text-sm text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 no-underline">Cancel</a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] text-white text-sm font-bold px-6 py-2.5 rounded-xl hover:opacity-90 transition">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
const applicablesData = @json(collect($applicables)->map(fn($items, $type) => $items->map(fn($item) => ['id' => $item->id, 'institution_id' => $item->institution_id, 'label' => method_exists($item, 'getDisplayNameAttribute') ? $item->display_name : $item->title]))->toArray());

function filterApplicables() {
    const typeSelect = document.getElementById('type-select');
    const institutionSelect = document.getElementById('institution-select');
    const applicableSelect = document.getElementById('applicable-select');
    const type = typeSelect.value;
    const institutionId = institutionSelect.value;
    applicableSelect.innerHTML = '<option value="">Select item</option>';
    if (!type || !institutionId) return;
    const items = (applicablesData[type] ?? []).filter(i => String(i.institution_id) === String(institutionId));
    items.forEach(item => {
        const opt = document.createElement('option');
        opt.value = item.id;
        opt.textContent = item.label;
        applicableSelect.appendChild(opt);
    });
}

document.getElementById('institution-select')?.addEventListener('change', filterApplicables);
</script>
@endpush
@endsection
