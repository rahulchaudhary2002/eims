@extends('admin.layouts.app')
@section('title', 'Edit Institution')
@section('page-title', 'Edit Institution')

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
<div class="space-y-5">

    <x-admin.page-header title="Edit Institution" subtitle="Update institution information"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Institutions','route'=>'admin.institution.index'],['label'=>'Edit']]">
        <x-slot:actions>
            <a href="{{ route('admin.institution.show', $institution) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                View
            </a>
            <a href="{{ route('admin.institution.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back to Institutions
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <x-admin.form-card title="Institution Details">
        <form action="{{ route('admin.institution.update', $institution) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="form-label">Institution Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name"
                        value="{{ old('name', $institution->name) }}"
                        class="form-control" required>
                </div>

                <div>
                    <label class="form-label">Institution Type <span class="text-red-500">*</span></label>
                    <select name="type" class="form-control" required>
                        <option value="">Select Type</option>
                        @foreach($institutionTypes as $institutionType)
                        <option value="{{ $institutionType->id }}" {{ old('type', $institution->institution_type_id) == $institutionType->id ? 'selected' : '' }}>
                            {{ $institutionType->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Institution Category <span class="text-red-500">*</span></label>
                    <select name="institution_category" class="form-control" required>
                        <option value="">Select Category</option>
                        @foreach($institutionCategories as $category)
                        <option value="{{ $category->id }}" {{ old('institution_category', $institution->institution_category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Affiliations</label>
                    <select name="affiliations[]" id="affiliations" multiple>
                        @foreach($affiliations as $affiliation)
                        <option value="{{ $affiliation->id }}"
                            {{ collect(old('affiliations', $institution->affiliations->pluck('id')))->contains($affiliation->id) ? 'selected' : '' }}>
                            {{ $affiliation->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Programs &amp; Commission</label>

                    <div id="program-rows" class="space-y-4"></div>

                    <template id="program-row-template">
                        <div class="program-row flex gap-4 items-center">
                            <select name="programs[]" class="program-select form-control" required>
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                <option value="{{ $program->id }}">
                                    {{ $program->name }} ({{ $program->code }})
                                </option>
                                @endforeach
                            </select>

                            <input type="number" name="commissions[]"
                                class="form-control"
                                placeholder="Commission" required>

                            <button type="button" class="remove-program text-red-500 hover:text-red-700 shrink-0">
                                <x-lucide-trash class="w-5 h-5" />
                            </button>
                        </div>
                    </template>

                    <button type="button" id="add-program"
                        class="mt-3 text-sm font-semibold text-blue-600 hover:text-blue-800">
                        + Add Program
                    </button>
                </div>

                <div class="md:col-span-2">
                    <label class="form-label">Address</label>
                    <textarea name="address" rows="3"
                        class="form-control">{{ old('address', $institution->address) }}</textarea>
                </div>

                <div>
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone"
                        value="{{ old('phone', $institution->phone) }}"
                        class="form-control">
                </div>

                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email"
                        value="{{ old('email', $institution->email) }}"
                        class="form-control">
                </div>

                <div class="md:col-span-2">
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $institution->is_active) ? 'checked' : '' }}
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <span class="form-label mb-0">Active Institution</span>
                    </label>
                </div>

            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.institution.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Update Institution
                </button>
            </div>

        </form>
    </x-admin.form-card>

</div>
@endsection

@section('page-specific-script')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

@php
$existingCourses = [];
if (old('programs')) {
foreach (old('programs') as $i => $id) {
$existingCourses[] = [
'course_id' => $id,
'commission' => old('commissions')[$i] ?? ''
];
}
} else {
foreach ($institution->programs as $c) {
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

        const container = document.getElementById('program-rows');
        const template = document.getElementById('program-row-template');
        const addBtn = document.getElementById('add-program');

        function refresh() {
            const selected = [...container.querySelectorAll('.program-select')]
                .map(s => s.value).filter(Boolean);

            container.querySelectorAll('.program-select').forEach(select => {
                [...select.options].forEach(opt => {
                    if (!opt.value) return;
                    opt.disabled = selected.includes(opt.value) && opt.value !== select.value;
                });
            });
        }

        function addRow(courseId = '', commission = '') {
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('.program-row');
            const select = row.querySelector('.program-select');
            const commissionInput = row.querySelector('input');

            select.value = courseId;
            commissionInput.value = commission;

            select.addEventListener('change', refresh);
            row.querySelector('.remove-program').onclick = () => {
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
