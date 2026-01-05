@extends('admin.layouts.app')
@section('title', 'Create Level')
@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-6 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📊 Create New Level</h1>
            <p class="text-gray-600 mt-1">Add a new education level</p>
        </div>
        <a href="{{ route('admin.level.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" />
            Back to Levels
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
            <form action="{{ route('admin.level.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-bar-chart-3 class="w-5 h-5 mr-2 text-blue-500" />
                            Level Name *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                            placeholder="Enter level name (e.g., Bachelor's Degree)" required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-hash class="w-5 h-5 mr-2 text-blue-500" />
                            Code (Optional)
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('code') border-red-500 @enderror"
                            placeholder="Enter level code (e.g., BACHELOR)">
                        <p class="text-gray-500 text-sm mt-1">Leave empty if not needed</p>
                        @error('code')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-list-ordered class="w-5 h-5 mr-2 text-blue-500" />
                            Display Order *
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', 0) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('order') border-red-500 @enderror"
                            placeholder="Enter display order" min="0" required>
                        <p class="text-gray-500 text-sm mt-1">Lower numbers appear first</p>
                        @error('order')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-file-text class="w-5 h-5 mr-2 text-blue-500" />
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('description') border-red-500 @enderror"
                            placeholder="Enter level description">{{ old('description') }}</textarea>
                        @error('description')
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
                                Active Level
                            </label>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Uncheck to make this level inactive</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.level.index') }}"
                        class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-x class="w-5 h-5 mr-2" />
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-save class="w-5 h-5 mr-2" />
                        Create Level
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection