@extends('admin.layouts.app')
@section('title', 'Vendor Details')
@section('content')
<div class="space-y-8">

    {{-- Header / Cover --}}
    <div class="relative rounded-2xl overflow-hidden border border-gray-200 shadow-lg">
        <div class="h-80 bg-gradient-to-r from-blue-700 to-cyan-600 relative">
            {{-- You can add cover image functionality for vendors if needed --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
        </div>

        <div class="relative -mt-40 px-8 pb-6">
            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-6">

                {{-- Avatar Container --}}
                <div class="h-32 w-32 rounded-2xl bg-white shadow-xl border-4 border-white flex items-center justify-center overflow-hidden">
                    @if($vendor->avatar)
                    <img src="{{ Storage::url($vendor->avatar) }}"
                        alt="Vendor Avatar"
                        class="h-full w-full object-cover">
                    @else
                    <div class="h-full w-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center">
                        <span class="text-white text-4xl font-bold">
                            {{ substr($vendor->name, 0, 1) }}
                        </span>
                    </div>
                    @endif
                </div>

                {{-- Title & Info --}}
                <div class="flex-1 text-white">
                    <div class="flex flex-wrap items-center gap-3 mb-2">
                        {{-- Email Verification Status --}}
                        @if($vendor->email_verified_at)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-500/30 text-blue-100 border border-blue-400/30">
                            <x-lucide-mail-check class="w-4 h-4 mr-1.5" />
                            Email Verified
                        </span>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-500/30 text-yellow-100 border border-yellow-400/30">
                            <x-lucide-mail-warning class="w-4 h-4 mr-1.5" />
                            Email Unverified
                        </span>
                        @endif

                        {{-- Phone Verification Status --}}
                        @if($vendor->phone_verified_at)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-500/30 text-green-100 border border-green-400/30">
                            <x-lucide-phone-call class="w-4 h-4 mr-1.5" />
                            Phone Verified
                        </span>
                        @elseif($vendor->phone)
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-500/30 text-yellow-100 border border-yellow-400/30">
                            <x-lucide-phone-off class="w-4 h-4 mr-1.5" />
                            Phone Unverified
                        </span>
                        @endif
                    </div>

                    <h1 class="text-4xl font-bold mb-3">{{ $vendor->name }}</h1>

                    <div class="flex flex-wrap gap-4 text-sm text-white/90">
                        @if($vendor->email)
                        <a href="mailto:{{ $vendor->email }}"
                            class="inline-flex items-center gap-2 hover:text-white transition hover:scale-105">
                            <x-lucide-mail class="w-4 h-4" />
                            {{ $vendor->email }}
                        </a>
                        @endif
                        @if($vendor->phone)
                        <a href="tel:{{ $vendor->phone }}"
                            class="inline-flex items-center gap-2 hover:text-white transition hover:scale-105">
                            <x-lucide-phone class="w-4 h-4" />
                            {{ $vendor->phone }}
                        </a>
                        @endif
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex gap-3 mt-4 sm:mt-0">
                    <a href="{{ route('admin.vendor.edit', $vendor) }}"
                        class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-800 px-5 py-2.5 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <x-lucide-edit class="w-4 h-4" />
                        Edit Vendor
                    </a>

                    <a href="{{ route('admin.vendor.index') }}"
                        class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white px-5 py-2.5 rounded-lg font-medium border border-white/20 hover:border-white/30 transition-all duration-200 transform hover:-translate-y-0.5">
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
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <x-lucide-user class="w-5 h-5 text-blue-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>

                <div class="space-y-5">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor Name</label>
                        <p class="text-gray-900 font-medium">{{ $vendor->name }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Account Status</label>
                        <div class="flex items-center gap-2">
                            @if($vendor->is_active ?? true)
                            <x-lucide-check-circle class="w-4 h-4 text-green-500" />
                            <p class="text-green-700 font-medium">Active Account</p>
                            @else
                            <x-lucide-x-circle class="w-4 h-4 text-red-500" />
                            <p class="text-red-700 font-medium">Inactive Account</p>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Registered Since</label>
                        <div class="flex items-center gap-2">
                            <x-lucide-calendar class="w-4 h-4 text-gray-400" />
                            <p class="text-gray-900">
                                {{ $vendor->created_at->format('F d, Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Information Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <x-lucide-contact class="w-5 h-5 text-purple-600" />
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Contact Details</h3>
                </div>

                <div class="space-y-5">
                    @if($vendor->email)
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email Address</label>
                        <div class="flex items-center justify-between">
                            <a href="mailto:{{ $vendor->email }}"
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors group">
                                <x-lucide-mail class="w-4 h-4" />
                                <span class="font-medium">{{ $vendor->email }}</span>
                            </a>
                            @if($vendor->email_verified_at)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <x-lucide-check class="w-3 h-3 mr-1" />
                                Verified
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($vendor->phone)
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</label>
                        <div class="flex items-center justify-between">
                            <a href="tel:{{ $vendor->phone }}"
                                class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors group">
                                <x-lucide-phone class="w-4 h-4" />
                                <span class="font-medium">{{ $vendor->phone }}</span>
                            </a>
                            @if($vendor->phone_verified_at)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <x-lucide-check class="w-3 h-3 mr-1" />
                                Verified
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(!$vendor->email && !$vendor->phone)
                    <div class="text-center py-4">
                        <x-lucide-info class="w-8 h-8 text-gray-300 mx-auto mb-2" />
                        <p class="text-gray-400 text-sm">No contact information provided</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-6 flex items-center">
                    <x-lucide-bar-chart class="w-5 h-5 mr-2" />
                    Vendor Stats
                </h3>

                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-1">{{ $vendor->institutions->count() }}</div>
                        <div class="text-sm text-blue-100">Associated Institutions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold mb-1">0</div>
                        <div class="text-sm text-blue-100">Total Services</div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-white/20">
                    <div class="flex items-center justify-center gap-2">
                        <x-lucide-shield class="w-4 h-4" />
                        <span class="text-sm">Vendor ID: #{{ $vendor->id }}</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Associated Institutions Card --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-50 rounded-lg">
                            <x-lucide-building class="w-5 h-5 text-indigo-600" />
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Associated Institutions</h3>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                        {{ $vendor->institutions->count() }} Total
                    </span>
                </div>

                @if($vendor->institutions->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($vendor->institutions as $institution)
                    <div class="border border-gray-200 rounded-xl p-4 hover:border-indigo-300 hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-50 rounded-lg">
                                    @if($institution->logo)
                                    <img src="{{ Storage::url($institution->logo) }}"
                                        alt="Institution Logo"
                                        class="w-8 h-8 object-contain">
                                    @else
                                    <x-lucide-building class="w-4 h-4 text-gray-600" />
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">{{ $institution->name }}</h4>
                                    @if($institution->type)
                                    <p class="text-xs text-gray-500 mt-0.5">{{ ucfirst($institution->type) }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $institution->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $institution->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">
                            Added {{ $institution->pivot->created_at?->diffForHumans() ?? 'recently' }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 border-2 border-dashed border-gray-200 rounded-xl">
                    <x-lucide-building class="w-12 h-12 text-gray-300 mx-auto mb-3" />
                    <p class="text-gray-400 mb-2">No institutions associated yet</p>
                    <p class="text-sm text-gray-500">Add institutions from the edit page</p>
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
                                <p class="text-gray-900 font-medium">{{ $vendor->created_at->format('F d, Y') }}</p>
                            </div>
                            <p class="text-sm text-gray-500">{{ $vendor->created_at->format('h:i A') }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Email Verified</label>
                            <div class="flex items-center gap-2">
                                @if($vendor->email_verified_at)
                                <x-lucide-check-circle class="w-4 h-4 text-green-500" />
                                <p class="text-green-700 font-medium">{{ $vendor->email_verified_at->format('F d, Y') }}</p>
                                @else
                                <x-lucide-x-circle class="w-4 h-4 text-red-500" />
                                <p class="text-red-700 font-medium">Not Verified</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</label>
                            <div class="flex items-center gap-2">
                                <x-lucide-refresh-cw class="w-4 h-4 text-gray-400" />
                                <p class="text-gray-900 font-medium">{{ $vendor->updated_at->format('F d, Y') }}</p>
                            </div>
                            <p class="text-sm text-gray-500">{{ $vendor->updated_at->format('h:i A') }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Verified</label>
                            <div class="flex items-center gap-2">
                                @if($vendor->phone_verified_at)
                                <x-lucide-check-circle class="w-4 h-4 text-green-500" />
                                <p class="text-green-700 font-medium">{{ $vendor->phone_verified_at->format('F d, Y') }}</p>
                                @elseif($vendor->phone)
                                <x-lucide-x-circle class="w-4 h-4 text-red-500" />
                                <p class="text-red-700 font-medium">Not Verified</p>
                                @else
                                <x-lucide-info class="w-4 h-4 text-gray-400" />
                                <p class="text-gray-700 font-medium">No Phone</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-100">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor ID</label>
                        <div class="flex items-center gap-2">
                            <x-lucide-hash class="w-4 h-4 text-gray-400" />
                            <code class="text-sm bg-gray-50 px-3 py-1.5 rounded-lg text-gray-700 font-mono border border-gray-200">
                                {{ $vendor->id }}
                            </code>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Additional Information --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
            <div class="p-2 bg-amber-50 rounded-lg">
                <x-lucide-info class="w-5 h-5 text-amber-600" />
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Additional Information</h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="space-y-2">
                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Last Login</label>
                <div class="flex items-center gap-2">
                    <x-lucide-log-in class="w-4 h-4 text-gray-400" />
                    <p class="text-gray-700">
                        {{ $vendor->last_login_at ? $vendor->last_login_at->diffForHumans() : 'Never' }}
                    </p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Login Count</label>
                <div class="flex items-center gap-2">
                    <x-lucide-bar-chart class="w-4 h-4 text-gray-400" />
                    <p class="text-gray-700">{{ $vendor->login_count ?? 0 }}</p>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Account Type</label>
                <div class="flex items-center gap-2">
                    <x-lucide-shield class="w-4 h-4 text-gray-400" />
                    <p class="text-gray-700">Vendor Account</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Action Buttons at Bottom --}}
    <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
        <a href="{{ route('admin.vendor.index') }}"
            class="inline-flex items-center gap-2 bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
            <x-lucide-arrow-left class="w-5 h-5" />
            Back to Vendors
        </a>

        <a href="{{ route('admin.vendor.edit', $vendor) }}"
            class="inline-flex items-center gap-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
            <x-lucide-edit class="w-5 h-5" />
            Edit Vendor Details
        </a>
    </div>

</div>
@endsection