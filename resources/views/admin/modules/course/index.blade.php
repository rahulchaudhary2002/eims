@extends('admin.layouts.app')
@section('title', 'Courses')
@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-4 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📚 Courses Management</h1>
            <p class="text-gray-600 mt-1">Manage all courses/programs</p>
        </div>
        <a href="{{ route('admin.course.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
            <x-lucide-plus class="w-5 h-5 mr-2" />
            Add Course
        </a>
    </div>

    @if(session('success'))
    <div class="px-6 pb-4">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <x-lucide-check-circle class="w-5 h-5 mr-2 text-green-600" />
                {{ session('success') }}
            </div>
        </div>
    </div>
    @endif

    <div class="px-6 pb-6">
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Level</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Affiliation</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Duration</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($courses as $course)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $course->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $course->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($course->code)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <x-lucide-hash class="w-3 h-3 mr-1" />
                                    {{ $course->code }}
                                </span>
                                @else
                                <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($course->level)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                    {{ $course->level->name }}
                                </span>
                                @else
                                <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $course->affiliation->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($course->duration)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <x-lucide-calendar class="w-3 h-3 mr-1" />
                                    {{ $course->duration }}
                                </span>
                                @else
                                <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <x-lucide-circle class="w-2 h-2 mr-1 {{ $course->is_active ? 'text-green-500' : 'text-red-500' }}" />
                                    {{ $course->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.course.show', $course) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-xs font-medium transition-colors duration-150 flex items-center">
                                        <x-lucide-eye class="w-4 h-4 mr-1" />
                                        View
                                    </a>
                                    <a href="{{ route('admin.course.edit', $course) }}"
                                        class="bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-white px-3 py-1 rounded-md text-xs font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center shadow-sm hover:shadow">
                                        <x-lucide-edit class="w-4 h-4 mr-1" />
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.course.destroy', $course) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center shadow-sm hover:shadow"
                                            onclick="return confirm('Are you sure you want to delete this course?')">
                                            <x-lucide-trash-2 class="w-4 h-4 mr-1" />
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <x-lucide-book class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                                <p class="text-lg font-medium">No courses found</p>
                                <p class="text-sm mt-1">Get started by adding your first course.</p>
                                <a href="{{ route('admin.course.create') }}"
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors duration-150">
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Create First Course
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($courses->count() > 0)
        <div class="mt-6">
            {{ $courses->links() }}
        </div>
        @endif
    </div>
</div>
@endsection