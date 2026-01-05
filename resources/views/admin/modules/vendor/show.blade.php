@extends('admin.layouts.app')
@section('title', 'Vendor Details')
@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-6 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">🚚 Vendor Details</h1>
            <p class="text-gray-600 mt-1">View complete vendor information</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.vendor.edit', $vendor) }}" class="bg-gradient-to-r from-yellow-500 to-yellow-600 hover:from-yellow-600 hover:to-yellow-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                <x-lucide-edit class="w-5 h-5 mr-2" />
                Edit Vendor
            </a>
            <a href="{{ route('admin.vendor.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                <x-lucide-arrow-left class="w-5 h-5 mr-2" />
                Back to Vendors
            </a>
        </div>
    </div>

    <div class="px-6 pb-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h2 class="text-xl font-semibold text-white flex items-center">
                    <x-lucide-info class="w-6 h-6 mr-2" />
                    {{ $vendor->name }}
                </h2>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center mb-2">
                            <x-lucide-user class="w-6 h-6 text-blue-500 mr-3" />
                            <h3 class="text-lg font-semibold text-gray-800">Basic Information</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Vendor Name:</span>
                                <p class="text-gray-900 font-medium">{{ $vendor->name }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Account Status:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <x-lucide-check-circle class="w-3 h-3 mr-1" />
                                    Active
                                </span>
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
                                    <a href="mailto:{{ $vendor->email }}" class="text-purple-600 hover:text-purple-800 flex items-center">
                                        <x-lucide-mail class="w-4 h-4 mr-1" />
                                        {{ $vendor->email }}
                                    </a>
                                </p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Phone:</span>
                                <p class="text-gray-900">
                                    @if($vendor->phone)
                                    <a href="tel:{{ $vendor->phone }}" class="text-purple-600 hover:text-purple-800 flex items-center">
                                        <x-lucide-phone class="w-4 h-4 mr-1" />
                                        {{ $vendor->phone }}
                                    </a>
                                    @else
                                    <span class="text-gray-500">Not provided</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center mb-2">
                            <x-lucide-shield class="w-6 h-6 text-blue-500 mr-3" />
                            <h3 class="text-lg font-semibold text-gray-800">Verification Status</h3>
                        </div>
                        <div class="space-y-3">
                            <div>
                                <span class="text-sm font-medium text-gray-500">Email Verified:</span>
                                @if($vendor->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <x-lucide-check-circle class="w-3 h-3 mr-1" />
                                    Verified
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <x-lucide-x-circle class="w-3 h-3 mr-1" />
                                    Unverified
                                </span>
                                @endif
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Phone Verified:</span>
                                @if($vendor->phone_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <x-lucide-check-circle class="w-3 h-3 mr-1" />
                                    Verified
                                </span>
                                @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <x-lucide-x-circle class="w-3 h-3 mr-1" />
                                    Unverified
                                </span>
                                @endif
                            </div>
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
                                <p class="text-gray-900 text-sm">{{ $vendor->created_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">Last Updated:</span>
                                <p class="text-gray-900 text-sm">{{ $vendor->updated_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">ID:</span>
                                <p class="text-gray-900 font-mono">#{{ $vendor->id }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.vendor.index') }}"
                        class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-arrow-left class="w-5 h-5 mr-2" />
                        Back to Vendors
                    </a>
                    <a href="{{ route('admin.vendor.edit', $vendor) }}"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-edit class="w-5 h-5 mr-2" />
                        Edit Vendor
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection