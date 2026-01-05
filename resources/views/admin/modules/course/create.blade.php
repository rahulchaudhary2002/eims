@extends('admin.layouts.app')
@section('title', 'Create Course')
@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-6 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📚 Create New Course</h1>
            <p class="text-gray-600 mt-1">Add a new course/program</p>
        </div>
        <a href="{{ route('admin.course.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" />
            Back to Courses
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
            <form action="{{ route('admin.course.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-book class="w-5 h-5 mr-2 text-blue-500" />
                            Course Name *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                            placeholder="Enter course name" required>
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
                            Course Code *
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('code') border-red-500 @enderror"
                            placeholder="Enter course code (e.g., BSC001)" required>
                        @error('code')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="level_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-graduation-cap class="w-5 h-5 mr-2 text-blue-500" />
                            Level *
                        </label>
                        <select name="level_id" id="level_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('level_id') border-red-500 @enderror"
                            required>
                            <option value="">Select Level</option>
                            @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ old('level_id') == $level->id ? 'selected' : '' }}>
                                {{ $level->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('level_id')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="affiliation_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-building class="w-5 h-5 mr-2 text-blue-500" />
                            Affiliation
                        </label>
                        <select name="affiliation_id" id="affiliation_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('affiliation_id') border-red-500 @enderror">
                            <option value="">Select Affiliation (Optional)</option>
                            @foreach($affiliations as $affiliation)
                            <option value="{{ $affiliation->id }}" {{ old('affiliation_id') == $affiliation->id ? 'selected' : '' }}>
                                {{ $affiliation->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('affiliation_id')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-calendar class="w-5 h-5 mr-2 text-blue-500" />
                            Duration
                        </label>
                        <input type="text" name="duration" id="duration" value="{{ old('duration') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('duration') border-red-500 @enderror"
                            placeholder="e.g., 3 years, 6 months, etc.">
                        @error('duration')
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
                        <textarea name="description" id="description" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('description') border-red-500 @enderror"
                            placeholder="Enter course description">{{ old('description') }}</textarea>
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
                                Active Course
                            </label>
                        </div>
                        <p class="text-gray-500 text-sm mt-1">Uncheck to make this course inactive</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.course.index') }}"
                        class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-x class="w-5 h-5 mr-2" />
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-save class="w-5 h-5 mr-2" />
                        Create Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection