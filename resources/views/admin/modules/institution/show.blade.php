@extends('admin.layouts.app')
@section('title', 'Institution Details')
@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-6 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">🏫 Institution Details</h1>
            <p class="text-gray-600 mt-1">View complete institution information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.institution.edit', $institution) }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                <x-lucide-edit class="w-5 h-5 mr-2" />
                Edit Institution
            </a>
            <a href="{{ route('admin.institution.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                <x-lucide-arrow-left class="w-5 h-5 mr-2" />
                Back to Institutions
            </a>
        </div>
    </div>

    <div class="px-6 pb-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <x-lucide-info class="w-6 h-6 mr-2" />
                    {{ $institution->name }}
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center mb-2">
                            <x-lucide-school class="w-6 h-6 text-blue-500 mr-3" />
                            <h3 class="text-lg font-semibold text-gray-800">Basic Information</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Institute Name:</span>
                                <p class="text-gray-900 font-medium">{{ $institution->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Institute Type:</span>
                                <p class="text-gray-900 font-medium">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $institution->type === 'school' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $institution->type === 'school' ? "School" : "College" }}
                                    </span>
                                </p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Established Year:</span>
                                <p class="text-gray-900">{{ $institution->established_year ?: 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center mb-2">
                            <x-lucide-contact class="w-6 h-6 text-blue-500 mr-3" />
                            <h3 class="text-lg font-semibold text-gray-800">Contact Details</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Email:</span>
                                <p class="text-gray-900">
                                    @if($institution->email)
                                    <a href="mailto:{{ $institution->email }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                                        <x-lucide-mail class="w-4 h-4 mr-1" />
                                        {{ $institution->email }}
                                    </a>
                                    @else
                                    <span class="text-gray-500">Not provided</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Phone:</span>
                                <p class="text-gray-900">
                                    @if($institution->phone)
                                    <a href="tel:{{ $institution->phone }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                                        <x-lucide-phone class="w-4 h-4 mr-1" />
                                        {{ $institution->phone }}
                                    </a>
                                    @else
                                    <span class="text-gray-500">Not provided</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Affiliations Section -->
                    <div class="md:col-span-2 bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <x-lucide-layers class="w-6 h-6 text-blue-500 mr-3" />
                                <h3 class="text-lg font-semibold text-gray-800">Affiliations</h3>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $institution->affiliations->count() > 0 ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ $institution->affiliations->count() }} affiliation{{ $institution->affiliations->count() !== 1 ? 's' : '' }}
                            </span>
                        </div>

                        @if($institution->affiliations->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach($institution->affiliations as $affiliation)
                            <div class="bg-white rounded-lg border border-gray-200 p-3 hover:shadow-md transition-shadow duration-200">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $affiliation->name }}</h4>
                                        @if($affiliation->code)
                                        <p class="text-sm text-gray-500 mt-1">
                                            <x-lucide-hash class="w-3 h-3 inline mr-1" />
                                            Code: {{ $affiliation->code }}
                                        </p>
                                        @endif
                                        @if($affiliation->description)
                                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($affiliation->description, 80) }}</p>
                                        @endif
                                    </div>
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium {{ $affiliation->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        <x-lucide-circle class="w-2 h-2 mr-1 {{ $affiliation->is_active ? 'text-green-500' : 'text-red-500' }}" />
                                        {{ $affiliation->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-6">
                            <x-lucide-layers class="w-12 h-12 mx-auto text-gray-300 mb-3" />
                            <p class="text-gray-500">No affiliations assigned to this institution</p>
                            <a href="{{ route('admin.institution.edit', $institution) }}" class="mt-3 inline-flex items-center text-sm text-blue-600 hover:text-blue-800">
                                <x-lucide-plus class="w-4 h-4 mr-1" />
                                Add affiliations
                            </a>
                        </div>
                        @endif
                    </div>

                    <div class="md:col-span-2 bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center mb-2">
                            <x-lucide-map-pin class="w-6 h-6 text-blue-500 mr-3" />
                            <h3 class="text-lg font-semibold text-gray-800">Location</h3>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-500">Address:</span>
                            <p class="text-gray-900 mt-1">
                                @if($institution->address)
                                {{ $institution->address }}
                                @else
                                <span class="text-gray-500">Address not provided</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center mb-2">
                            <x-lucide-calendar class="w-6 h-6 text-blue-500 mr-3" />
                            <h3 class="text-lg font-semibold text-gray-800">Timestamps</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Created:</span>
                                <p class="text-gray-900 text-sm">{{ $institution->created_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Last Updated:</span>
                                <p class="text-gray-900 text-sm">{{ $institution->updated_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center mb-2">
                            <x-lucide-hash class="w-6 h-6 text-blue-500 mr-3" />
                            <h3 class="text-lg font-semibold text-gray-800">System Info</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">ID:</span>
                                <p class="text-gray-900 font-mono">#{{ $institution->id }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <x-lucide-check-circle class="w-3 h-3 mr-1" />
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.institution.index') }}"
                        class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-arrow-left class="w-5 h-5 mr-2" />
                        Back to Institutions
                    </a>
                    <a href="{{ route('admin.institution.edit', $institution) }}"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-edit class="w-5 h-5 mr-2" />
                        Edit Institution
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection