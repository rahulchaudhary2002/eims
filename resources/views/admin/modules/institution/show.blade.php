@extends('admin.layouts.app')
@section('title', 'Institution Details')
@section('page-title', 'Institution Details')
@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Institution Details" :subtitle="$institution->name"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Institutions','route'=>'admin.institution.index'],['label'=>'Details']]">
        <x-slot:actions>
            <a href="{{ route('admin.institution.edit', $institution) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit Institution
            </a>
            <a href="{{ route('admin.institution.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Cover & Logo --}}
    @php
        $coverImage = ($institution->cover_image && Storage::disk('public')->exists($institution->cover_image))
            ? Storage::url($institution->cover_image)
            : null;
    @endphp
    @if($coverImage || $institution->logo)
    <div class="eims-card p-0 overflow-hidden">
        @if($coverImage)
        <div class="h-48 bg-gradient-to-r from-indigo-700 to-blue-700 relative">
            <img src="{{ $coverImage }}" alt="Cover Image" class="h-full w-full object-cover">
        </div>
        @endif
        @if($institution->logo)
        <div class="px-6 py-4 flex items-center gap-4">
            <div class="h-20 w-20 rounded-xl bg-white shadow border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                <img src="{{ Storage::url($institution->logo) }}" alt="Institution Logo" class="h-full w-full object-contain p-1">
            </div>
            <div>
                <p class="text-xs font-medium text-slate-500 uppercase tracking-wider mb-1">Institution Logo</p>
                <p class="font-semibold text-slate-800">{{ $institution->name }}</p>
            </div>
        </div>
        @endif
    </div>
    @endif

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left Column --}}
        <div class="space-y-5">

            {{-- Basic Information Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-indigo-50 rounded-lg">
                        <x-lucide-info class="w-5 h-5 text-indigo-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Basic Information</h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Institution Name</label>
                        <p class="text-slate-900 font-medium">{{ $institution->name }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Status</label>
                        <div>
                            <x-admin.status-badge :status="$institution->is_active ? 'active' : 'inactive'" />
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Institution Type</label>
                        <div class="flex items-center gap-2">
                            <x-lucide-building class="w-4 h-4 text-slate-400" />
                            <p class="text-slate-700">{{ ucfirst($institution->type) }}</p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Established Year</label>
                        <div class="flex items-center gap-2">
                            <x-lucide-calendar class="w-4 h-4 text-slate-400" />
                            <p class="text-slate-700">
                                {{ $institution->established_year ?: 'Not specified' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Institution ID</label>
                        <code class="text-sm bg-slate-50 px-3 py-1.5 rounded-lg text-slate-700 font-mono border border-slate-200 inline-block">#{{ $institution->id }}</code>
                    </div>
                </div>
            </div>

            {{-- Contact Information Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <x-lucide-phone class="w-5 h-5 text-blue-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Contact Information</h3>
                </div>

                <div class="space-y-4">
                    @if($institution->email)
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Email Address</label>
                        <a href="mailto:{{ $institution->email }}"
                            class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors group">
                            <x-lucide-mail class="w-4 h-4" />
                            <span class="font-medium text-sm">{{ $institution->email }}</span>
                        </a>
                    </div>
                    @endif

                    @if($institution->phone)
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Phone Number</label>
                        <a href="tel:{{ $institution->phone }}"
                            class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors group">
                            <x-lucide-phone class="w-4 h-4" />
                            <span class="font-medium text-sm">{{ $institution->phone }}</span>
                        </a>
                    </div>
                    @endif

                    @if(!$institution->email && !$institution->phone)
                    <div class="text-center py-4">
                        <x-lucide-info class="w-8 h-8 text-slate-300 mx-auto mb-2" />
                        <p class="text-slate-400 text-sm">No contact information provided</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="rounded-xl bg-gradient-to-br from-indigo-500 to-blue-600 p-6 text-white shadow-sm">
                <h3 class="text-base font-semibold mb-4">Quick Stats</h3>

                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-1">{{ $institution->affiliations->count() }}</div>
                        <div class="text-sm text-indigo-100">Affiliations</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-1">{{ $institution->programs->count() }}</div>
                        <div class="text-sm text-indigo-100">Programs</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Address Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <x-lucide-map-pin class="w-5 h-5 text-green-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Location</h3>
                </div>

                @if($institution->address)
                <div class="flex items-start gap-3">
                    <div class="mt-1">
                        <x-lucide-map-pin class="w-5 h-5 text-slate-400" />
                    </div>
                    <div>
                        <p class="text-slate-700 leading-relaxed">{{ $institution->address }}</p>
                        <div class="mt-3">
                            <a href="https://maps.google.com/?q={{ urlencode($institution->address) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                <x-lucide-external-link class="w-4 h-4" />
                                View on Google Maps
                            </a>
                        </div>
                    </div>
                </div>
                @else
                <div class="text-center py-8">
                    <x-lucide-map-pin class="w-12 h-12 text-slate-300 mx-auto mb-3" />
                    <p class="text-slate-400">No address information provided</p>
                </div>
                @endif
            </div>

            {{-- Affiliations Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-purple-50 rounded-lg">
                            <x-lucide-layers class="w-5 h-5 text-purple-600" />
                        </div>
                        <h3 class="text-base font-semibold text-slate-900">Affiliations</h3>
                    </div>
                    <span class="badge">{{ $institution->affiliations->count() }} Total</span>
                </div>

                @if($institution->affiliations->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($institution->affiliations as $affiliation)
                    <div class="border border-slate-200 rounded-xl p-4 hover:border-purple-300 hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-slate-50 rounded-lg">
                                    <x-lucide-building class="w-4 h-4 text-slate-600" />
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-900 text-sm">{{ $affiliation->name }}</h4>
                                    @if($affiliation->code)
                                    <p class="text-xs text-slate-500 mt-0.5">Code: {{ $affiliation->code }}</p>
                                    @endif
                                </div>
                            </div>
                            <x-admin.status-badge :status="$affiliation->is_active ? 'active' : 'inactive'" />
                        </div>
                        <div class="text-xs text-slate-500">
                            Created {{ $affiliation->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 border-2 border-dashed border-slate-200 rounded-xl">
                    <x-lucide-building class="w-12 h-12 text-slate-300 mx-auto mb-3" />
                    <p class="text-slate-400 mb-2">No affiliations assigned yet</p>
                    <p class="text-sm text-slate-500">Add affiliations from the edit page</p>
                </div>
                @endif
            </div>

            {{-- Programs Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-5 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-green-50 rounded-lg">
                            <x-lucide-book-open class="w-5 h-5 text-green-600" />
                        </div>
                        <h3 class="text-base font-semibold text-slate-900">Programs</h3>
                    </div>
                    <span class="badge">{{ $institution->programs->count() }} Total</span>
                </div>

                @if($institution->programs->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($institution->programs as $program)
                    <div class="border border-slate-200 rounded-xl p-4 hover:border-green-300 hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-slate-50 rounded-lg">
                                    <x-lucide-book class="w-4 h-4 text-slate-600" />
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-900 text-sm">{{ $program->name }}</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Code: {{ $program->code }}</p>
                                    @php $durations = $program->pluck('duration')->filter()->unique(); @endphp
                                    @if($durations->isNotEmpty())
                                    <p class="text-xs text-slate-500">Duration: {{ $durations->join(', ') }}</p>
                                    @endif
                                </div>
                            </div>
                            <x-admin.status-badge :status="$program->is_active ? 'active' : 'inactive'" />
                        </div>
                        @if($program->description)
                        <p class="text-sm text-slate-600 mt-2">{{ Str::limit($program->description, 100) }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 border-2 border-dashed border-slate-200 rounded-xl">
                    <x-lucide-book class="w-12 h-12 text-slate-300 mx-auto mb-3" />
                    <p class="text-slate-400 mb-2">No programs assigned yet</p>
                    <p class="text-sm text-slate-500">Add programs from the edit page</p>
                </div>
                @endif
            </div>

            {{-- System Information Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-slate-100 rounded-lg">
                        <x-lucide-database class="w-5 h-5 text-slate-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">System Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Created At</label>
                            <div class="flex items-center gap-2">
                                <x-lucide-calendar-plus class="w-4 h-4 text-slate-400" />
                                <p class="text-slate-900 font-medium">{{ $institution->created_at->format('F d, Y') }}</p>
                            </div>
                            <p class="text-sm text-slate-500">{{ $institution->created_at->format('h:i A') }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Last Updated</label>
                            <div class="flex items-center gap-2">
                                <x-lucide-refresh-cw class="w-4 h-4 text-slate-400" />
                                <p class="text-slate-900 font-medium">{{ $institution->updated_at->format('F d, Y') }}</p>
                            </div>
                            <p class="text-sm text-slate-500">{{ $institution->updated_at->format('h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-100">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Institution ID</label>
                        <div class="flex items-center gap-2">
                            <x-lucide-hash class="w-4 h-4 text-slate-400" />
                            <code class="text-sm bg-slate-50 px-3 py-1 rounded-lg text-slate-700 border border-slate-200">
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
