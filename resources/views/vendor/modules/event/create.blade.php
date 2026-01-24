@extends('vendor.layouts.app')

@section('title', 'Create Event')

@section('page-specific-style')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">

    {{-- Header --}}
    <div class="p-6 flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📅 Create New Event</h1>
            <p class="text-gray-600 mt-1">Set up a new institutional event</p>
        </div>
        <a href="{{ route('vendor.event.index') }}"
            class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" /> Back
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
            <form method="POST" action="{{ route('vendor.event.store') }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    {{-- Title --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-calendar class="w-5 h-5 mr-2 text-purple-500" />
                            Event Title *
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                            placeholder="Enter event title" required>
                        @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dates --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-calendar-days class="w-5 h-5 mr-2 text-purple-500" />
                            Start Date *
                        </label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                            class="w-full px-4 py-3 border rounded-lg" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-calendar-days class="w-5 h-5 mr-2 text-purple-500" />
                            End Date *
                        </label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
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
                        class="w-full px-4 py-3 border rounded-lg">{{ old('description') }}</textarea>
                </div>

                {{-- Buttons --}}
                <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                    <a href="{{ route('vendor.event.index') }}"
                        class="bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center">
                        <x-lucide-x class="w-5 h-5 mr-2" /> Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center">
                        <x-lucide-save class="w-5 h-5 mr-2" /> Create Event
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('page-specific-script')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#description',
                height: 300,
                menubar: false,
                plugins: 'lists link image table code',
                toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code',
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave();
                    });
                }
            });
        }
    });
</script>
@endsection
