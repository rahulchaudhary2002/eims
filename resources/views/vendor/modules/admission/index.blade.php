@extends('vendor.layouts.app')
@section('title', 'Admissions')

@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-4 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">🎓 Admissions</h1>
            <p class="text-gray-600 mt-1">Manage institution admissions</p>
        </div>
        <a href="{{ route('vendor.admission.create') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-lg shadow-md flex items-center">
            <x-lucide-plus class="w-5 h-5 mr-2" /> Add Admission
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
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Title</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Start Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">End Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Applications</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($admissions as $admission)
                        <tr class="hover:bg-blue-50 transition-colors duration-150">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $admission->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $admission->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($admission->start_date)->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ \Carbon\Carbon::parse($admission->end_date)->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $admission->applications->count() }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($admission->is_open)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <x-lucide-check-circle class="w-3 h-3 mr-1" /> Open
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <x-lucide-x-circle class="w-3 h-3 mr-1" /> Closed
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('vendor.admission.application.index', $admission) }}" class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded-md text-xs flex items-center">
                                        <x-lucide-file-text class="w-4 h-4 mr-1" /> Applications
                                    </a>
                                    <a href="{{ route('vendor.admission.show', $admission) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-xs flex items-center">
                                        <x-lucide-eye class="w-4 h-4 mr-1" /> View
                                    </a>
                                    <a href="{{ route('vendor.admission.edit', $admission) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded-md text-xs flex items-center">
                                        <x-lucide-edit class="w-4 h-4 mr-1" /> Edit
                                    </a>
                                    <form action="{{ route('vendor.admission.destroy', $admission) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs flex items-center" onclick="return confirm('Are you sure?')">
                                            <x-lucide-trash-2 class="w-4 h-4 mr-1" /> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <x-lucide-inbox class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                                <p class="text-lg">No admissions found</p>
                                <p class="text-sm">Create a new admission to get started.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4">
            {{ $admissions->links() }}
        </div>
    </div>
</div>
@endsection