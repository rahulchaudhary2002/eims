@extends('layouts.app')

@section('title', 'Ask a Question')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-semibold text-gray-900 mb-6">Ask a new question</h1>

    <form method="POST" action="{{ route('forum.question.store') }}" class="space-y-6 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
            <select name="category"
                class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                required>
                <option value="">Select category</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->value }}" @selected(old('category')===$cat->value)>
                    {{ $cat->label() }}
                </option>
                @endforeach
            </select>
            @error('category')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input type="text" name="title"
                value="{{ old('title') }}"
                class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                required>
            @error('title')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Details</label>
            <textarea name="body" rows="6"
                class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                required>{{ old('body') }}</textarea>
            @error('body')
            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_anonymous" value="1"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    {{ old('is_anonymous') ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Post as anonymous</span>
            </label>

            <label class="inline-flex items-center">
                <input type="checkbox" name="is_draft" value="1"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    {{ old('is_draft') ? 'checked' : '' }}>
                <span class="ml-2 text-sm text-gray-700">Save as draft</span>
            </label>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('forum.question.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                Cancel
            </a>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700">
                Submit
            </button>
        </div>
    </form>
</div>
@endsection