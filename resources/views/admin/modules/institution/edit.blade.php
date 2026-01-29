@extends('admin.layouts.app')

@section('title', 'Edit Institution')

@section('page-specific-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
    .choices {
        margin-bottom: 0;
    }

    .choices__inner {
        background-color: #ffffff !important;
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        padding: 4px 8px !important;
        min-height: 48px;
        display: flex;
        align-items: center;
    }

    .choices__list--multiple .choices__item {
        background-color: #3b82f6 !important;
        border: 1px solid #2563eb !important;
        border-radius: 4px !important;
        padding: 2px 8px !important;
        font-size: 0.875rem !important;
    }

    .choices__list--multiple .choices__item.is-highlighted {
        background-color: #2563eb !important;
    }

    .is-focused .choices__inner {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, .5) !important;
    }
</style>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">

    <div class="p-6 flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold">🏫 Edit Institution</h1>
            <p class="text-gray-600">Update institution information</p>
        </div>
        <a href="{{ route('admin.institution.index') }}"
            class="bg-gray-600 text-white px-4 py-2 rounded-lg">
            ← Back
        </a>
    </div>

    @if($errors->any())
    <div class="px-6 mb-4">
        <div class="bg-red-100 text-red-700 p-4 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="px-6 pb-6">
        <form action="{{ route('admin.institution.update', $institution) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Name -->
                <div>
                    <label class="block mb-1">Institution Name *</label>
                    <input type="text" name="name"
                        value="{{ old('name', $institution->name) }}"
                        class="w-full border rounded-lg px-4 py-2" required>
                </div>

                <!-- Type -->
                <div>
                    <label class="block mb-1">Institution Type *</label>
                    <select name="type" class="w-full border rounded-lg px-4 py-2" required>
                        <option value="">Select Type</option>
                        <option value="college" {{ old('type', $institution->type) == 'college' ? 'selected' : '' }}>College</option>
                        <option value="school" {{ old('type', $institution->type) == 'school' ? 'selected' : '' }}>School</option>
                    </select>
                </div>

                <!-- Affiliations -->
                <div class="md:col-span-2">
                    <label class="block mb-1">Affiliations</label>
                    <select name="affiliations[]" id="affiliations" multiple>
                        @foreach($affiliations as $affiliation)
                        <option value="{{ $affiliation->id }}"
                            {{ collect(old('affiliations', $institution->affiliations->pluck('id')))->contains($affiliation->id) ? 'selected' : '' }}>
                            {{ $affiliation->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Courses -->
                <div class="md:col-span-2">
                    <label class="block mb-2">Courses & Commission</label>

                    <div id="course-rows" class="space-y-4"></div>

                    <template id="course-row-template">
                        <div class="course-row flex gap-4 items-center">
                            <select name="courses[]" class="course-select border rounded-lg px-4 py-2" required>
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                <option value="{{ $course->id }}">
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                                @endforeach
                            </select>

                            <input type="number" name="commissions[]"
                                class="border rounded-lg px-4 py-2"
                                placeholder="Commission" required>

                            <button type="button" class="remove-course text-red-500 hover:text-red-700">
                                <x-lucide-trash class="w-5 h-5" />
                            </button>
                        </div>
                    </template>

                    <button type="button" id="add-course"
                        class="mt-3 text-blue-600 font-semibold">
                        + Add Course
                    </button>
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block mb-1">Address</label>
                    <textarea name="address" rows="3"
                        class="w-full border rounded-lg px-4 py-2">{{ old('address', $institution->address) }}</textarea>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block mb-1">Phone</label>
                    <input type="text" name="phone"
                        value="{{ old('phone', $institution->phone) }}"
                        class="w-full border rounded-lg px-4 py-2">
                </div>

                <!-- Email -->
                <div>
                    <label class="block mb-1">Email</label>
                    <input type="email" name="email"
                        value="{{ old('email', $institution->email) }}"
                        class="w-full border rounded-lg px-4 py-2">
                </div>

                <!-- Active -->
                <div class="md:col-span-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $institution->is_active) ? 'checked' : '' }}>
                        <span class="ml-2">Active Institution</span>
                    </label>
                </div>

            </div>

            <div class="flex justify-end gap-4 mt-8">
                <a href="{{ route('admin.institution.index') }}"
                    class="px-6 py-2 bg-gray-500 text-white rounded-lg">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                    Update Institution
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@section('page-specific-script')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@php
$existingCourses = [];
if (old('courses')) {
foreach (old('courses') as $i => $id) {
$existingCourses[] = [
'course_id' => $id,
'commission' => old('commissions')[$i] ?? ''
];
}
} else {
foreach ($institution->courses as $c) {
$existingCourses[] = [
'course_id' => $c->id,
'commission' => $c->pivot->commission_amount
];
}
}
@endphp

<script>
    const existingCourses = JSON.parse("{{ json_encode($existingCourses) }}".replace(/&quot;/g, '\"'));
    document.addEventListener('DOMContentLoaded', function() {

        new Choices('#affiliations', {
            removeItemButton: true,
            searchEnabled: true,
            shouldSort: false
        });

        const container = document.getElementById('course-rows');
        const template = document.getElementById('course-row-template');
        const addBtn = document.getElementById('add-course');

        function refresh() {
            const selected = [...container.querySelectorAll('.course-select')]
                .map(s => s.value).filter(Boolean);

            container.querySelectorAll('.course-select').forEach(select => {
                [...select.options].forEach(opt => {
                    if (!opt.value) return;
                    opt.disabled = selected.includes(opt.value) && opt.value !== select.value;
                });
            });
        }

        function addRow(courseId = '', commission = '') {
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('.course-row');
            const select = row.querySelector('.course-select');
            const commissionInput = row.querySelector('input');

            select.value = courseId;
            commissionInput.value = commission;

            select.addEventListener('change', refresh);
            row.querySelector('.remove-course').onclick = () => {
                row.remove();
                refresh();
            };

            container.appendChild(clone);
            refresh();
        }

        if (existingCourses.length) {
            existingCourses.forEach(c => addRow(c.course_id, c.commission));
        } else {
            addRow();
        }

        addBtn.onclick = () => addRow();
    });
</script>
@endsection