@extends('admin.layouts.app')

@section('title', 'Create Institution')

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
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-6 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">🏫 Create New Institution</h1>
            <p class="text-gray-600 mt-1">Add a new educational institution</p>
        </div>
        <a href="{{ route('admin.institution.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" />
            Back to Institutions
        </a>
    </div>

    @if($errors->any())
    <div class="px-6 pb-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="px-6 pb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.institution.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-school class="w-5 h-5 mr-2 text-blue-500" />
                            Institution Name *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                            placeholder="Enter institution name" required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-school class="w-5 h-5 mr-2 text-blue-500" />
                            Institution Type *
                        </label>
                        <select name="type" id="type"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('type') border-red-500 @enderror"
                            required>
                            <option value="">Select Type</option>
                            @foreach($institutionTypes as $institutionType)
                            <option value="{{ $institutionType->id }}" {{ old('type') == $institutionType->id ? 'selected' : '' }}>
                                {{ $institutionType->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('type')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="institution_category" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-chart-bar-stacked class="w-5 h-5 mr-2 text-blue-500" />
                            Institution Category *
                        </label>
                        <select name="institution_category" id="institution_category"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('institution_category') border-red-500 @enderror"
                            required>
                            <option value="">Select Category</option>
                            @foreach($institutionCategories as $category)
                            <option value="{{ $category->id }}" {{ old('institution_category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('institution_category')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="affiliations" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-layers class="w-5 h-5 mr-2 text-blue-500" />
                            Affiliations
                        </label>
                        <select name="affiliations[]" id="affiliations" multiple
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('affiliations') border-red-500 @enderror">
                            @foreach($affiliations as $affiliation)
                            <option value="{{ $affiliation->id }}" {{ (collect(old('affiliations'))->contains($affiliation->id)) ? 'selected' : '' }}>
                                {{ $affiliation->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('affiliations')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Programs & Commission
                        </label>

                        <div id="program-rows" class="w-full space-y-4"></div>

                        <!-- Program Row Template -->
                        <template id="program-row-template">
                            <div class="w-full program-row flex items-center space-x-4">
                                <select name="programs[]" class="program-select px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" required>
                                    <option value="">Select Program</option>
                                    @foreach($programs as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }} ({{ $program->code }})</option>
                                    @endforeach
                                </select>
                                <input type="number" name="commissions[]" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" placeholder="Commission Amount" required>
                                <button type="button" class="remove-program text-red-500 hover:text-red-700">
                                    <x-lucide-trash class="w-5 h-5" />
                                </button>
                            </div>
                        </template>

                        <button type="button" id="add-program"
                            class="mt-3 text-sm font-semibold text-blue-600 hover:text-blue-800">
                            Add Program
                        </button>
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-map-pin class="w-5 h-5 mr-2 text-blue-500" />
                            Address
                        </label>
                        <textarea name="address" id="address" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('address') border-red-500 @enderror"
                            placeholder="Enter complete address">{{ old('address') }}</textarea>
                        @error('address')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-phone class="w-5 h-5 mr-2 text-blue-500" />
                            Phone Number
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                            placeholder="Enter phone number">
                        @error('phone')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-mail class="w-5 h-5 mr-2 text-blue-500" />
                            Email Address
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                            placeholder="Enter email address">
                        @error('email')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="established_year" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-calendar class="w-5 h-5 mr-2 text-blue-500" />
                            Established Year
                        </label>
                        <input type="number" name="established_year" id="established_year" value="{{ old('established_year') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('established_year') border-red-500 @enderror"
                            placeholder="YYYY" min="1800" max="{{ date('Y') + 1 }}">
                        @error('established_year')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1"
                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label for="is_active" class="ml-2 text-sm font-medium text-gray-700">
                                Active Institution
                            </label>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Uncheck to make this institution inactive</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.institution.index') }}"
                        class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-x class="w-5 h-5 mr-2" />
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-save class="w-5 h-5 mr-2" />
                        Create Institution
                    </button>
                </div>
            </form>
        </div>
    </div>
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
