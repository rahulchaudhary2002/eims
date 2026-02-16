@extends('admin.layouts.app')
@section('title', 'Institution Types')
@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-4 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">🏫 Institution Types Management</h1>
            <p class="text-gray-600 mt-1">Manage institution types</p>
        </div>
        <a href="{{ route('admin.institution-type.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
            <x-lucide-plus class="w-5 h-5 mr-2" />
            Add Institution Type
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

    @if(session('error'))
    <div class="px-6 pb-4">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-r-lg shadow-sm">
            <div class="flex items-center">
                <x-lucide-alert-circle class="w-5 h-5 mr-2 text-red-600" />
                {{ session('error') }}
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
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Slug</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Institutions</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($institutionTypes as $institutionType)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $institutionType->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $institutionType->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-indigo-100 text-indigo-800">{{ $institutionType->slug }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $institutionType->institutions_count }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.institution-type.edit', $institutionType) }}"
                                        class="bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-white px-3 py-1 rounded-md text-xs font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center shadow-sm hover:shadow">
                                        <x-lucide-edit class="w-4 h-4 mr-1" />
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.institution-type.destroy', $institutionType) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-3 py-1 rounded-md text-xs font-medium transition-all duration-200 transform hover:-translate-y-0.5 flex items-center shadow-sm hover:shadow"
                                            onclick="return confirm('Are you sure you want to delete this institution type?')">
                                            <x-lucide-trash-2 class="w-4 h-4 mr-1" />
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <x-lucide-building-2 class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                                <p class="text-lg font-medium">No institution types found</p>
                                <p class="text-sm mt-1">Get started by adding your first institution type.</p>
                                <a href="{{ route('admin.institution-type.create') }}"
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-md transition-colors duration-150">
                                    <x-lucide-plus class="w-4 h-4 mr-2" />
                                    Create First Institution Type
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($institutionTypes->count() > 0)
        <div class="mt-6">
            {{ $institutionTypes->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
