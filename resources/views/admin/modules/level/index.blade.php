@extends('admin.layouts.app')
@section('title', 'Levels')
@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-4 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">📊 Levels Management</h1>
            <p class="text-gray-600 mt-1">Manage education levels</p>
        </div>
        <a href="{{ route('admin.level.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
            <x-lucide-plus class="w-5 h-5 mr-2" />
            Add Level
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
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Order</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Code</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($levels as $level)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-100 text-blue-800 font-bold">
                                    {{ $level->order }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $level->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($level->code)
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                    <x-lucide-hash class="w-3 h-3 mr-1" />
                                    {{ $level->code }}
                                </span>
                                @else
                                <span class="text-gray-400 text-sm">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ Str::limit($level->description, 50) ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $level->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    <x-lucide-circle class="w-2 h-2 mr-1 {{ $level->is_active ? 'text-green-500' : 'text-red-500' }}" />
                                    {{ $level->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.level.edit', $level) }}"
                                        class="bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-white px-3 py-1 rounded-md text-xs font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center shadow-sm hover:shadow">
                                        <x-lucide-edit class="w-4 h-4 mr-1" />
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.level.destroy', $level) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center shadow-sm hover:shadow"
                                            onclick="return confirm('Are you sure you want to delete this level?')">
                                            <x-lucide-trash-2 class="w-4 h-4 mr-1" />
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <x-lucide-bar-chart-3 class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                                <p class="text-lg font-medium">No levels found</p>
                                <p class="text-sm mt-1">Get started by adding your first level.</p>
                                <a href="{{ route('admin.level.create') }}"
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors duration-150">
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Create First Level
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($levels->count() > 0)
        <div class="mt-6">
            {{ $levels->links() }}
        </div>
        @endif
    </div>
</div>
@endsection