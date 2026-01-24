@extends('vendor.layouts.app')

@section('title', 'Edit Event')

@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">

    {{-- Header --}}
    <div class="p-6 flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">✏️ Edit Event</h1>
            <p class="text-gray-600 mt-1">Update event details</p>
        </div>
        <a href="{{ route('vendor.event.index') }}"
            class="bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-2 rounded-lg flex items-center">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" /> Back
        </a>
    </div>

    <div class="px-6 pb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="{{ route('vendor.event.update', $event) }}">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Event Title *
                        </label>
                        <input type="text" name="title"
                            value="{{ old('title', $event->title) }}"
                            class="w-full px-4 py-3 border rounded-lg" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Start Date *
                        </label>
                        <input type="date" name="start_date"
                            value="{{ old('start_date', $event->start_date) }}"
                            class="w-full px-4 py-3 border rounded-lg" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            End Date *
                        </label>
                        <input type="date" name="end_date"
                            value="{{ old('end_date', $event->end_date) }}"
                            class="w-full px-4 py-3 border rounded-lg" required>
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description"
                        class="w-full px-4 py-3 border rounded-lg">
                    {{ old('description', $event->description) }}
                    </textarea>
                </div>

                <div class="flex justify-end gap-4 mt-8 pt-6 border-t">
                    <a href="{{ route('vendor.event.index') }}"
                        class="bg-gray-600 text-white px-6 py-3 rounded-lg flex items-center">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-blue-600 text-white px-6 py-3 rounded-lg flex items-center">
                        Update Event
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
                toolbar: 'undo redo | bold italic underline | bullist numlist | link image | code'
            });
        }
    });
</script>
@endsection
