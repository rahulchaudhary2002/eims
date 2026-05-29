@extends('admin.layouts.app')
@section('title', 'Create Institution')
@section('page-title', 'Create Institution')

@section('page-specific-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
    /* Customizing Choices.js to match your Tailwind theme */
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

    .choices[data-type*="select-one"] .choices__inner {
        padding-bottom: 4px !important;
    }

    .choices__input {
        background-color: transparent !important;
    }

    .is-focused .choices__inner {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
    }
</style>
@endsection

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Create Institution" subtitle="Add a new educational institution"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Institutions','route'=>'admin.institution.index'],['label'=>'Create']]">
        <x-slot:actions>
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
        <form action="{{ route('admin.institution.store') }}" method="POST">
            @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label for="name" class="form-label">Institution Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="form-control @error('name') border-red-500 @enderror"
                            placeholder="Enter institution name" required>
                        @error('name')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="form-label">Institution Type <span class="text-red-500">*</span></label>
                        <select name="type" id="type"
                            class="form-control @error('type') border-red-500 @enderror"
                            required>
                            <option value="">Select Type</option>
                            @foreach($institutionTypes as $institutionType)
                            <option value="{{ $institutionType->id }}" {{ old('type') == $institutionType->id ? 'selected' : '' }}>
                                {{ $institutionType->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('type')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="institution_category" class="form-label">Institution Category <span class="text-red-500">*</span></label>
                        <select name="institution_category" id="institution_category"
                            class="form-control @error('institution_category') border-red-500 @enderror"
                            required>
                            <option value="">Select Category</option>
                            @foreach($institutionCategories as $category)
                            <option value="{{ $category->id }}" {{ old('institution_category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('institution_category')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="affiliations" class="form-label">Affiliations</label>
                        <select name="affiliations[]" id="affiliations" multiple
                            class="w-full @error('affiliations') border-red-500 @enderror">
                            @foreach($affiliations as $affiliation)
                            <option value="{{ $affiliation->id }}" {{ (collect(old('affiliations'))->contains($affiliation->id)) ? 'selected' : '' }}>
                                {{ $affiliation->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('affiliations')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="form-label">Programs &amp; Commission</label>

                        <div id="program-rows" class="w-full space-y-4"></div>

                        <!-- Program Row Template -->
                        <template id="program-row-template">
                            <div class="w-full program-row flex items-center space-x-4">
                                <select name="programs[]" class="program-select form-control" required>
                                    <option value="">Select Program</option>
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }} ({{ $program->code }})</option>
                                    @endforeach
                                </select>
                                <input type="number" name="commissions[]" class="form-control" placeholder="Commission Amount" required>
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
                        <label for="address" class="form-label">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="form-control @error('address') border-red-500 @enderror"
                            placeholder="Enter complete address">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="form-control @error('phone') border-red-500 @enderror"
                            placeholder="Enter phone number">
                        @error('phone')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="form-control @error('email') border-red-500 @enderror"
                            placeholder="Enter email address">
                        @error('email')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="established_year" class="form-label">Established Year</label>
                        <input type="number" name="established_year" id="established_year" value="{{ old('established_year') }}"
                            class="form-control @error('established_year') border-red-500 @enderror"
                            placeholder="YYYY" min="1800" max="{{ date('Y') + 1 }}">
                        @error('established_year')
                        <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="form-label mb-0">Active Institution</span>
                        </label>
                        <p class="text-slate-500 text-xs mt-1">Uncheck to make this institution inactive</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                    <a href="{{ route('admin.institution.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                        Create Institution
                    </button>
                </div>
            </form>
    </x-admin.form-card>

</div>
@endsection

@section('page-specific-script')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize affiliations select
        const affiliationsElement = document.getElementById('affiliations');
        if (affiliationsElement) {
            const affiliationsChoices = new Choices(affiliationsElement, {
                removeItemButton: true,
                placeholderValue: 'Select affiliations...',
                searchEnabled: true,
                shouldSort: false,
                itemSelectText: '',
                noResultsText: 'No matches found',
            });
        }

        const container = document.getElementById('program-rows');
        const addBtn = document.getElementById('add-program');
        const template = document.getElementById('program-row-template');

        function refresh() {
            const selected = Array.from(container.querySelectorAll('.program-select'))
                .map(s => s.value)
                .filter(Boolean);

            container.querySelectorAll('.program-select').forEach(select => {
                Array.from(select.options).forEach(option => {
                    if (!option.value) return;
                    option.disabled = selected.includes(option.value) && option.value !== select.value;
                });
            });
        }

        function addRow() {
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('.program-row');
            const select = row.querySelector('.program-select');

            select.addEventListener('change', refresh);

            row.querySelector('.remove-program').addEventListener('click', function() {
                row.remove();
                refresh();
            });

            container.appendChild(clone);
            refresh();
        }

        addBtn.addEventListener('click', addRow);
        addRow();
    });
</script>
@endsection
