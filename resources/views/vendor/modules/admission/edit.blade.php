@extends('vendor.layouts.app')

@section('title', 'Edit Admission')

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
        min-height: 48px;
        display: flex;
        align-items: center;
        padding: 4px 8px !important;
    }

    .choices__list--multiple .choices__item {
        background-color: #8b5cf6 !important;
        border-radius: 4px !important;
        font-size: 0.875rem !important;
    }

    .is-focused .choices__inner {
        border-color: #8b5cf6 !important;
        box-shadow: 0 0 0 2px rgba(139, 92, 246, .4);
    }

    [x-cloak] {
        display: none !important;
    }

    .handle {
        cursor: move;
    }
</style>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">

    {{-- Header --}}
    <div class="p-6 flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">✏️ Edit Admission</h1>
            <p class="text-gray-600 mt-1">Update admission details</p>
        </div>
        <a href="{{ route('vendor.admission.index') }}"
            class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" />
            Back
        </a>
    </div>

    {{-- Errors --}}
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

    {{-- Form --}}
    <div class="px-6 pb-6">
        <div class="bg-white rounded-lg shadow-md p-6">

            <form
                x-data="admissionForm()"
                x-init="init()"
                method="POST"
                action="{{ route('vendor.admission.update', $admission->id) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-book class="w-5 h-5 mr-2 text-purple-500" />
                            Admission Title *
                        </label>
                        <input type="text" name="title"
                            value="{{ old('title', $admission->title) }}"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500"
                            required>
                    </div>

                    {{-- Dates --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-calendar class="w-5 h-5 mr-2 text-purple-500" />
                            Start Date *
                        </label>
                        <input type="date" name="start_date"
                            value="{{ old('start_date', $admission->start_date) }}"
                            class="w-full px-4 py-3 border rounded-lg" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-calendar class="w-5 h-5 mr-2 text-purple-500" />
                            End Date *
                        </label>
                        <input type="date" name="end_date"
                            value="{{ old('end_date', $admission->end_date) }}"
                            class="w-full px-4 py-3 border rounded-lg" required>
                    </div>

                </div>

                {{-- Description --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <x-lucide-file-text class="w-5 h-5 mr-2 text-purple-500" />
                        Description
                    </label>
                    <textarea id="description" name="description"
                        class="w-full px-4 py-3 border rounded-lg">
                    {{ old('description', $admission->description) }}
                    </textarea>
                </div>

                {{-- PROGRAMS --}}
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                        <x-lucide-book-open class="w-5 h-5 mr-2 text-purple-500" />
                        Select Programs *
                    </label>
                    <select id="programs" name="programs[]" multiple class="w-full">
                        @foreach($programs as $program)
                        <option value="{{ $program->id }}"
                            {{ in_array($program->id, old('programs', $selectedProgramIds)) ? 'selected' : '' }}>
                            {{ $program->name }} ({{ $program->code }})
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                    <a href="{{ route('vendor.admission.index') }}"
                        class="bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center">
                        <x-lucide-x class="w-5 h-5 mr-2" /> Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center">
                        <x-lucide-save class="w-5 h-5 mr-2" /> Update Admission
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
    document.addEventListener('DOMContentLoaded', () => {
        if (document.getElementById('programs')) {
            new Choices('#programs', {
                removeItemButton: true,
                placeholderValue: 'Select programs...',
                searchEnabled: true,
                shouldSort: false,
                itemSelectText: '',
                noResultsText: 'No programs found',
                searchResultLimit: 10,
            });
        }

        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#description',
                height: 300,
                menubar: false,
                plugins: 'lists link image table code',
                toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code'
            });
        }
    });
</script>
@endsection