@extends('admin.layouts.app')
@section('title', 'Institution Details')

@section('content')
<div class="space-y-8">

    {{-- Header / Cover --}}
    <div class="relative rounded-2xl overflow-hidden border border-gray-200 shadow-lg">
        <div class="h-80 bg-gradient-to-r from-indigo-700 to-blue-700 relative">
            @if($institution->cover_image)
            <img
                src="{{ Storage::url($institution->cover_image) }}"
                alt="Cover Image"
                class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
            @endif
        </div>

        <div class="relative -mt-40 px-8 pb-6">
            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-6">

                {{-- Logo Container --}}
                <div class="h-32 w-32 rounded-2xl bg-white shadow-xl border-4 border-white flex items-center justify-center overflow-hidden">
                    @if($institution->logo)
                    <img
                        src="{{ Storage::url($institution->logo) }}"
                        alt="Institution Logo"
                        class="h-full w-full object-contain p-2">
                    @else
                    <x-lucide-school class="w-16 h-16 text-gray-400" />
                    @endif
                </div>

                {{-- Title & Info --}}
                <div class="flex-1 text-white">
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $institution->is_active ? 'bg-green-500/30 text-green-100 border border-green-400/30' : 'bg-red-500/30 text-red-100 border border-red-400/30' }}">
                            {{ $institution->is_active ? 'Active' : 'Inactive' }}
                        </span>

                        <span class="text-white/80 text-sm bg-white/10 px-3 py-1 rounded-full">
                            {{ ucfirst($institution->type) }}
                        </span>

                        @if($institution->established_year)
                        <span class="text-white/80 text-sm bg-white/10 px-3 py-1 rounded-full">
                            Est. {{ $institution->established_year }}
                        </span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-bold mb-3">{{ $institution->name }}</h1>

                    @if($institution->email || $institution->phone)
                    <div class="flex flex-wrap gap-4 text-sm text-white/90">
                        @if($institution->email)
                        <a href="mailto:{{ $institution->email }}"
                            class="inline-flex items-center gap-2 hover:text-white transition">
                            <x-lucide-mail class="w-4 h-4" />
                            {{ $institution->email }}
                        </a>
                        @endif
                        @if($institution->phone)
                        <a href="tel:{{ $institution->phone }}"
                            class="inline-flex items-center gap-2 hover:text-white transition">
                            <x-lucide-phone class="w-4 h-4" />
                            {{ $institution->phone }}
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3 mt-4 sm:mt-0">
                    <a href="{{ route('admin.institution.edit', $institution) }}"
                        class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-800 px-5 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transition-all">
                        <x-lucide-edit class="w-4 h-4" />
                        Edit Institution
                    </a>

                    <a href="{{ route('admin.institution.index') }}"
                        class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-5 py-2.5 rounded-lg font-medium border border-white/20 hover:border-white/30 transition-all">
                        <x-lucide-arrow-left class="w-4 h-4" />
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Left Column --}}
        <div class="space-y-6">

            {{-- Basic Information Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="p-2 bg-indigo-50 rounded-lg">
                        <x-lucide-info class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>

                <div class="space-y-5">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Institution Name</label>
                        <p class="text-gray-900 font-medium">{{ $institution->name }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Institution Type</label>
                        <div class="flex items-center gap-2">
                            <x-lucide-building class="w-4 h-4 text-gray-400" />
                            <p class="text-gray-900">{{ ucfirst($institution->type) }}</p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Established Year</label>
                        <div class="flex items-center gap-2">
                            <x-lucide-calendar class="w-4 h-4 text-gray-400" />
                            <p class="text-gray-900">
                                {{ $institution->established_year ?: 'Not specified' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Information Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <x-lucide-phone class="w-5 h-5 text-blue-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Contact Information</h3>
                </div>

                <div class="space-y-5">
                    @if($institution->email)
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email Address</label>
                        <a href="mailto:{{ $institution->email }}"
                            class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors group">
                            <x-lucide-mail class="w-4 h-4" />
                            <span class="font-medium">{{ $institution->email }}</span>
                        </a>
                    </div>
                    @endif

                    @if($institution->phone)
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</label>
                        <a href="tel:{{ $institution->phone }}"
                            class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors group">
                            <x-lucide-phone class="w-4 h-4" />
                            <span class="font-medium">{{ $institution->phone }}</span>
                        </a>
                    </div>
                    @endif

                    @if(!$institution->email && !$institution->phone)
                    <div class="text-center py-4">
                        <x-lucide-info class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                        <p class="text-gray-400 text-sm">No contact information provided</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="bg-gradient-to-br from-indigo-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-6">Quick Stats</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-1">{{ $institution->affiliations->count() }}</div>
                        <div class="text-sm text-indigo-100">Affiliations</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-1">{{ $institution->courses->count() }}</div>
                        <div class="text-sm text-indigo-100">Courses</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Address Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <x-lucide-map-pin class="w-5 h-5 text-green-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Location</h3>
                </div>

                @if($institution->address)
                <div class="flex items-start gap-3">
                    <div class="mt-1">
                        <x-lucide-map-pin class="w-5 h-5 text-gray-400" />
                    </div>
                    <div>
                        <p class="text-gray-700 leading-relaxed">{{ $institution->address }}</p>
                        <div class="mt-3">
                            <a href="https://maps.google.com/?q={{ urlencode($institution->address) }}"
                                target="_blank"
                                class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <x-lucide-external-link class="w-4 h-4" />
                                View on Google Maps
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <x-lucide-map-pin class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                    <p class="text-gray-400">No address information provided</p>
                </div>
                @endif
            </div>

            {{-- Affiliations Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-50 rounded-lg">
                            <x-lucide-layers class="w-5 h-5 text-purple-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Affiliations</h3>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        {{ $institution->affiliations->count() }} Total
                    </span>
                </div>

                @if($institution->affiliations->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($institution->affiliations as $affiliation)
                    <div class="border border-gray-200 rounded-xl p-4 hover:border-purple-300 hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-50 rounded-lg">
                                    <x-lucide-building class="w-4 h-4 text-gray-600" />
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $affiliation->name }}</h4>
                                    @if($affiliation->code)
                                    <p class="text-xs text-gray-500 mt-0.5">Code: {{ $affiliation->code }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $affiliation->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $affiliation->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">
                            Created {{ $affiliation->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-xl">
                    <x-lucide-building class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                    <p class="text-gray-400 mb-2">No affiliations assigned yet</p>
                    <p class="text-sm text-gray-500">Add affiliations from the edit page</p>
                </div>
                @endif
            </div>

            {{-- Courses Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <x-lucide-book-open class="w-5 h-5 text-green-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Courses</h3>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {{ $institution->courses->count() }} Total
                    </span>
                </div>

                @if($institution->courses->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($institution->courses as $course)
                    <div class="border border-gray-200 rounded-xl p-4 hover:border-green-300 hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-50 rounded-lg">
                                    <x-lucide-book class="w-4 h-4 text-gray-600" />
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $course->name }}</h4>
                                    <p class="text-xs text-gray-500 mt-0.5">Code: {{ $course->code }}</p>
                                    @if($course->duration)
                                    <p class="text-xs text-gray-500">Duration: {{ $course->duration }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $course->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        @if($course->description)
                        <p class="text-sm text-gray-600 mt-2">{{ Str::limit($course->description, 100) }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-xl">
                    <x-lucide-book class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                    <p class="text-gray-400 mb-2">No courses assigned yet</p>
                    <p class="text-sm text-gray-500">Add courses from the edit page</p>
                </div>
                @endif
            </div>

            {{-- System Information Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="p-2 bg-gray-50 rounded-lg">
                        <x-lucide-database class="w-5 h-5 text-gray-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">System Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</label>
                            <div class="flex items-center gap-2">
                                <x-lucide-calendar-plus class="w-4 h-4 text-gray-400" />
                                <p class="text-gray-900 font-medium">{{ $institution->created_at->format('F d, Y') }}</p>
                            </div>
                            <p class="text-sm text-gray-500">{{ $institution->created_at->format('h:i A') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</label>
                            <div class="flex items-center gap-2">
                                <x-lucide-refresh-cw class="w-4 h-4 text-gray-400" />
                                <p class="text-gray-900 font-medium">{{ $institution->updated_at->format('F d, Y') }}</p>
                            </div>
                            <p class="text-sm text-gray-500">{{ $institution->updated_at->format('h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Institution ID</label>
                        <div class="flex items-center gap-2">
                            <x-lucide-hash class="w-4 h-4 text-gray-400" />
                            <code class="text-sm bg-gray-50 px-3 py-1 rounded-lg text-gray-700">
                                {{ $institution->id }}
                            </code>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection