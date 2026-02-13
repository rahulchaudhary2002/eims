@extends('vendor.layouts.app')

@section('title', 'Institution Profile')

@section('content')
<!-- Cover Photo Section -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
    <div class="relative h-64 md:h-80 bg-gradient-to-r from-blue-500 to-indigo-600">
        @if($institution->cover_image)
        <img src="{{ Storage::url($institution->cover_image) }}"
            alt="Cover Image"
            class="w-full h-full object-cover">
        @endif

        <!-- Edit Button -->
        <div class="absolute top-4 right-4">
            <a href="{{ route('vendor.institution.edit') }}"
                class="bg-white text-gray-800 hover:bg-gray-100 px-4 py-2 rounded-lg font-semibold flex items-center gap-2 shadow-md">
                <x-lucide-edit class="w-5 h-5" />
                Edit Profile
            </a>
        </div>

        <!-- Logo Section -->
        <div class="absolute bottom-0 left-8 transform translate-y-1/2">
            <div class="bg-white p-2 rounded-2xl shadow-2xl">
                <div class="w-32 h-32 rounded-xl overflow-hidden border-4 border-white">
                    @if($institution->logo)
                    <img src="{{ Storage::url($institution->logo) }}"
                        alt="Logo"
                        class="w-full h-full object-cover">
                    @else
                    <div class="w-full h-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                        <x-lucide-building class="w-12 h-12 text-blue-600" />
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Institution Info -->
    <div class="pt-20 pb-8 px-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $institution->name }}</h2>
                <div class="flex items-center gap-2 mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $institution->type == 'college' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                        <x-lucide-school class="w-4 h-4 mr-1" />
                        {{ ucfirst($institution->type) }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                            {{ $institution->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        <x-lucide-circle class="w-4 h-4 mr-1" />
                        {{ $institution->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    @if($institution->established_year)
                    <span class="text-gray-600 inline-flex items-center">
                        <x-lucide-calendar class="w-4 h-4 mr-1" />
                        Established {{ $institution->established_year }}
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-xl border border-blue-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-blue-100 mr-4">
                        <x-lucide-building class="w-6 h-6 text-blue-600" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Type</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ ucfirst($institution->type) }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-r from-green-50 to-emerald-50 p-6 rounded-xl border border-green-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-green-100 mr-4">
                        <x-lucide-check-circle class="w-6 h-6 text-green-600" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ $institution->is_active ? 'Active' : 'Inactive' }}
                        </p>
                    </div>
                </div>
            </div>

            @if($institution->established_year)
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 p-6 rounded-xl border border-purple-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-purple-100 mr-4">
                        <x-lucide-calendar class="w-6 h-6 text-purple-600" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Established</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ $institution->established_year }}
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-gradient-to-r from-amber-50 to-orange-50 p-6 rounded-xl border border-amber-100">
                <div class="flex items-center">
                    <div class="p-3 rounded-lg bg-amber-100 mr-4">
                        <x-lucide-info class="w-6 h-6 text-amber-600" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="text-xl font-bold text-gray-800">
                            {{ $institution->updated_at->format('M d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Left Column -->
            <div class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <x-lucide-contact class="w-5 h-5 mr-2 text-blue-600" />
                        Contact Information
                    </h3>
                    <div class="space-y-4">
                        @if($institution->address)
                        <div class="flex items-start">
                            <x-lucide-map-pin class="w-5 h-5 text-gray-400 mr-3 mt-1 flex-shrink-0" />
                            <div>
                                <p class="text-sm text-gray-600">Address</p>
                                <p class="text-gray-800">{{ $institution->address }}</p>
                            </div>
                        </div>
                        @endif

                        @if($institution->phone)
                        <div class="flex items-center">
                            <x-lucide-phone class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" />
                            <div>
                                <p class="text-sm text-gray-600">Phone</p>
                                <a href="tel:{{ $institution->phone }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $institution->phone }}
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($institution->email)
                        <div class="flex items-center">
                            <x-lucide-mail class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0" />
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <a href="mailto:{{ $institution->email }}" class="text-blue-600 hover:text-blue-800">
                                    {{ $institution->email }}
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <x-lucide-settings class="w-5 h-5 mr-2 text-blue-600" />
                        Quick Actions
                    </h3>
                    <div class="space-y-3">
                        <a href="{{ route('vendor.institution.edit') }}"
                            class="flex items-center justify-between p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition-all">
                            <div class="flex items-center">
                                <x-lucide-edit class="w-5 h-5 text-blue-600 mr-3" />
                                <span>Edit Profile Information</span>
                            </div>
                            <x-lucide-chevron-right class="w-5 h-5 text-gray-400" />
                        </a>

                        @if($institution->logo || $institution->cover_image)
                        <a href="{{ route('vendor.institution.edit') }}#media"
                            class="flex items-center justify-between p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition-all">
                            <div class="flex items-center">
                                <x-lucide-image class="w-5 h-5 text-blue-600 mr-3" />
                                <span>Update Logo & Cover</span>
                            </div>
                            <x-lucide-chevron-right class="w-5 h-5 text-gray-400" />
                        </a>
                        @endif

                        <a href="#"
                            class="flex items-center justify-between p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-200 transition-all">
                            <div class="flex items-center">
                                <x-lucide-users class="w-5 h-5 text-blue-600 mr-3" />
                                <span>View Institution Members</span>
                            </div>
                            <x-lucide-chevron-right class="w-5 h-5 text-gray-400" />
                        </a>
                    </div>
                </div>

                <!-- Status Toggle -->
                <div class="bg-gray-50 p-6 rounded-xl">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <x-lucide-toggle-right class="w-5 h-5 mr-2 text-blue-600" />
                        Institution Status
                    </h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600">Current Status</p>
                            <p class="text-lg font-semibold {{ $institution->is_active ? 'text-green-600' : 'text-red-600' }}">
                                {{ $institution->is_active ? 'Active and Visible' : 'Inactive and Hidden' }}
                            </p>
                        </div>
                        <button
                            class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors 
                                               {{ $institution->is_active ? 'bg-green-500' : 'bg-gray-300' }}">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform 
                                          {{ $institution->is_active ? 'translate-x-6' : 'translate-x-1' }}" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="fixed top-4 right-4 z-50">
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg flex items-center">
        <x-lucide-check-circle class="w-6 h-6 mr-3" />
        <div>
            <p class="font-semibold">Success!</p>
            <p>{{ session('success') }}</p>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div class="fixed top-4 right-4 z-50">
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg flex items-center">
        <x-lucide-alert-circle class="w-6 h-6 mr-3" />
        <div>
            <p class="font-semibold">Error!</p>
            <p>{{ session('error') }}</p>
        </div>
    </div>
</div>
@endif

<!-- Auto-hide success/error messages -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const messages = document.querySelectorAll('[class*="fixed top-4 right-4"]');
        messages.forEach(message => {
            setTimeout(() => {
                message.style.opacity = '0';
                message.style.transition = 'opacity 0.5s';
                setTimeout(() => message.remove(), 500);
            }, 5000);
        });
    });
</script>
@endsection