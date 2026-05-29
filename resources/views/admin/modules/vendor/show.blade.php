@extends('admin.layouts.app')
@section('title', 'Vendor Details')
@section('page-title', 'Vendor Details')
@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Vendor Details" :subtitle="$vendor->name"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Vendors','route'=>'admin.vendor.index'],['label'=>'Details']]">
        <x-slot:actions>
            <a href="{{ route('admin.vendor.edit', $vendor) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit Vendor
            </a>
            <a href="{{ route('admin.vendor.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left Column --}}
        <div class="space-y-5">

            {{-- Basic Information Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <x-lucide-user class="w-5 h-5 text-blue-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Basic Information</h3>
                </div>

                <div class="space-y-5">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Vendor Name</label>
                        <p class="text-slate-900 font-medium">{{ $vendor->name }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Account Status</label>
                        <div>
                            @if($vendor->is_active ?? true)
                            <x-admin.status-badge :status="'active'" />
                            @else
                            <x-admin.status-badge :status="'inactive'" />
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Registered Since</label>
                        <p class="text-slate-700">{{ $vendor->created_at->format('F d, Y') }}</p>
                    </div>

                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Vendor ID</label>
                        <code class="text-sm bg-slate-50 px-3 py-1.5 rounded-lg text-slate-700 font-mono border border-slate-200 inline-block">#{{ $vendor->id }}</code>
                    </div>
                </div>
            </div>

            {{-- Contact Information Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-purple-50 rounded-lg">
                        <x-lucide-contact class="w-5 h-5 text-purple-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Contact Details</h3>
                </div>

                <div class="space-y-5">
                    @if($vendor->email)
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Email Address</label>
                        <div class="flex items-center justify-between">
                            <a href="mailto:{{ $vendor->email }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors">
                                <x-lucide-mail class="w-4 h-4" />
                                <span class="font-medium text-sm">{{ $vendor->email }}</span>
                            </a>
                            @if($vendor->email_verified_at)
                            <x-admin.status-badge :status="'active'" />
                            @else
                            <x-admin.status-badge :status="'inactive'" />
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($vendor->phone)
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Phone Number</label>
                        <div class="flex items-center justify-between">
                            <a href="tel:{{ $vendor->phone }}" class="flex items-center gap-2 text-blue-600 hover:text-blue-800 transition-colors">
                                <x-lucide-phone class="w-4 h-4" />
                                <span class="font-medium text-sm">{{ $vendor->phone }}</span>
                            </a>
                            @if($vendor->phone_verified_at)
                            <x-admin.status-badge :status="'active'" />
                            @else
                            <x-admin.status-badge :status="'inactive'" />
                            @endif
                        </div>
                    </div>
                    @endif

                    @if(!$vendor->email && !$vendor->phone)
                    <p class="text-slate-400 text-sm text-center py-4">No contact information provided</p>
                    @endif
                </div>
            </div>

            {{-- Quick Stats --}}
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-900 mb-4">Vendor Stats</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="text-center p-3 bg-slate-50 rounded-lg">
                        <div class="text-2xl font-bold text-slate-800 mb-1">{{ $vendor->institutions->count() }}</div>
                        <div class="text-xs text-slate-500">Institutions</div>
                    </div>
                    <div class="text-center p-3 bg-slate-50 rounded-lg">
                        <div class="text-2xl font-bold text-slate-800 mb-1">{{ $vendor->login_count ?? 0 }}</div>
                        <div class="text-xs text-slate-500">Logins</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Right Column --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Associated Institutions Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-50 rounded-lg">
                            <x-lucide-building class="w-5 h-5 text-indigo-600" />
                        </div>
                        <h3 class="text-base font-semibold text-slate-900">Associated Institutions</h3>
                    </div>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ $vendor->institutions->count() }} Total
                    </span>
                </div>

                @if($vendor->institutions->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($vendor->institutions as $institution)
                    <div class="border border-slate-200 rounded-xl p-4 hover:border-indigo-300 hover:shadow-sm transition-all duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-slate-50 rounded-lg">
                                    @if($institution->logo)
                                    <img src="{{ Storage::url($institution->logo) }}" alt="Institution Logo" class="w-8 h-8 object-contain">
                                    @else
                                    <x-lucide-building class="w-4 h-4 text-slate-600" />
                                    @endif
                                </div>
                                <div>
                                    <h4 class="font-semibold text-slate-900 text-sm">{{ $institution->name }}</h4>
                                    @if($institution->type)
                                    <p class="text-xs text-slate-500 mt-0.5">{{ ucfirst($institution->type) }}</p>
                                    @endif
                                </div>
                            </div>
                            <x-admin.status-badge :status="$institution->is_active ? 'active' : 'inactive'" />
                        </div>
                        <div class="text-xs text-slate-500">
                            Added {{ $institution->pivot->created_at?->diffForHumans() ?? 'recently' }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-10 border-2 border-dashed border-slate-200 rounded-xl">
                    <x-lucide-building class="w-12 h-12 text-slate-300 mx-auto mb-3" />
                    <p class="text-slate-400 mb-2">No institutions associated yet</p>
                    <p class="text-sm text-slate-500">Add institutions from the edit page</p>
                </div>
                @endif
            </div>

            {{-- System Information Card --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-slate-100 rounded-lg">
                        <x-lucide-database class="w-5 h-5 text-slate-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">System Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Created At</label>
                            <p class="text-slate-900 font-medium">{{ $vendor->created_at->format('F d, Y') }}</p>
                            <p class="text-sm text-slate-500">{{ $vendor->created_at->format('h:i A') }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Email Verified</label>
                            @if($vendor->email_verified_at)
                            <p class="text-green-700 font-medium">{{ $vendor->email_verified_at->format('F d, Y') }}</p>
                            @else
                            <p class="text-red-600 font-medium">Not Verified</p>
                            @endif
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Last Updated</label>
                            <p class="text-slate-900 font-medium">{{ $vendor->updated_at->format('F d, Y') }}</p>
                            <p class="text-sm text-slate-500">{{ $vendor->updated_at->format('h:i A') }}</p>
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Phone Verified</label>
                            @if($vendor->phone_verified_at)
                            <p class="text-green-700 font-medium">{{ $vendor->phone_verified_at->format('F d, Y') }}</p>
                            @elseif($vendor->phone)
                            <p class="text-red-600 font-medium">Not Verified</p>
                            @else
                            <p class="text-slate-500 font-medium">No Phone</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Additional Information --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <x-lucide-info class="w-5 h-5 text-amber-600" />
                    </div>
                    <h3 class="text-base font-semibold text-slate-900">Additional Information</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Last Login</label>
                        <p class="text-slate-700">{{ $vendor->last_login_at ? $vendor->last_login_at->diffForHumans() : 'Never' }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Login Count</label>
                        <p class="text-slate-700">{{ $vendor->login_count ?? 0 }}</p>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wider">Account Type</label>
                        <p class="text-slate-700">Vendor Account</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
