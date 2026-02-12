@extends('vendor.layouts.app')

@section('title', 'Edit Institution Profile')

@section('content')
<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Institution Profile</h1>
            <p class="text-gray-600 mt-2">Update your institution's information and media</p>
        </div>
        <a href="{{ route('vendor.institution.profile') }}"
            class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
            <x-lucide-arrow-left class="w-5 h-5" />
            Back to Profile
        </a>
    </div>
</div>

<!-- Main Form -->
<form action="{{ route('vendor.institution.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
    @csrf
    @method('PUT')

    <!-- Basic Information Card -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center mb-6">
            <div class="p-2 rounded-lg bg-blue-100 mr-4">
                <x-lucide-building class="w-6 h-6 text-blue-600" />
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Basic Information</h2>
                <p class="text-gray-600 text-sm">Core details about your institution</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="flex items-center">
                        <x-lucide-briefcase class="w-4 h-4 mr-1" />
                        Institution Name *
                    </span>
                </label>
                <input type="text"
                    name="name"
                    value="{{ old('name', $institution->name) }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                    required>
                @error('name')
                <p class="mt-1 text-sm text-red-600 flex items-center">
                    <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Type -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="flex items-center">
                        <x-lucide-school class="w-4 h-4 mr-1" />
                        Institution Type *
                    </span>
                </label>
                <select name="type"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition">
                    @foreach($institutionTypes as $institutionType)
                    <option value="{{ $institutionType->slug }}" {{ old('type', $institution->type) == $institutionType->slug ? 'selected' : '' }}>
                        {{ $institutionType->name }}
                    </option>
                    @endforeach
                </select>
                @error('type')
                <p class="mt-1 text-sm text-red-600 flex items-center">
                    <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Established Year -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="flex items-center">
                        <x-lucide-calendar class="w-4 h-4 mr-1" />
                        Established Year
                    </span>
                </label>
                <input type="number"
                    name="established_year"
                    min="1900"
                    max="{{ date('Y') }}"
                    value="{{ old('established_year', $institution->established_year) }}"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                    placeholder="e.g., 1995">
                @error('established_year')
                <p class="mt-1 text-sm text-red-600 flex items-center">
                    <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                    {{ $message }}
                </p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="flex items-center">
                        <x-lucide-toggle-right class="w-4 h-4 mr-1" />
                        Status
                    </span>
                </label>
                <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <input type="radio"
                            id="active"
                            name="is_active"
                            value="1"
                            {{ old('is_active', $institution->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                        <label for="active" class="ml-2 flex items-center">
                            <x-lucide-check-circle class="w-4 h-4 text-green-600 mr-1" />
                            <span class="text-gray-700">Active</span>
                        </label>
                    </div>
                    <div class="flex items-center">
                        <input type="radio"
                            id="inactive"
                            name="is_active"
                            value="0"
                            {{ !old('is_active', $institution->is_active) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                        <label for="inactive" class="ml-2 flex items-center">
                            <x-lucide-x-circle class="w-4 h-4 text-red-600 mr-1" />
                            <span class="text-gray-700">Inactive</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Information Card -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center mb-6">
            <div class="p-2 rounded-lg bg-green-100 mr-4">
                <x-lucide-contact class="w-6 h-6 text-green-600" />
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Contact Information</h2>
                <p class="text-gray-600 text-sm">How people can reach your institution</p>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Address -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="flex items-center">
                        <x-lucide-map-pin class="w-4 h-4 mr-1" />
                        Address
                    </span>
                </label>
                <textarea name="address"
                    rows="3"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                    placeholder="Enter full address">{{ old('address', $institution->address) }}</textarea>
                @error('address')
                <p class="mt-1 text-sm text-red-600 flex items-center">
                    <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                    {{ $message }}
                </p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Phone -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <x-lucide-phone class="w-4 h-4 mr-1" />
                            Phone Number
                        </span>
                    </label>
                    <input type="tel"
                        name="phone"
                        value="{{ old('phone', $institution->phone) }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        placeholder="+1 (555) 123-4567">
                    @error('phone')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                        {{ $message }}
                    </p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <span class="flex items-center">
                            <x-lucide-mail class="w-4 h-4 mr-1" />
                            Email Address
                        </span>
                    </label>
                    <input type="email"
                        name="email"
                        value="{{ old('email', $institution->email) }}"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition"
                        placeholder="contact@institution.com">
                    @error('email')
                    <p class="mt-1 text-sm text-red-600 flex items-center">
                        <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                        {{ $message }}
                    </p>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Media Upload Card -->
    <div id="media" class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center mb-6">
            <div class="p-2 rounded-lg bg-purple-100 mr-4">
                <x-lucide-image class="w-6 h-6 text-purple-600" />
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-800">Media & Branding</h2>
                <p class="text-gray-600 text-sm">Upload your logo and cover image</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Logo Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">
                    <span class="flex items-center">
                        <x-lucide-image class="w-4 h-4 mr-1" />
                        Institution Logo
                    </span>
                </label>

                <div class="space-y-4">
                    <!-- Current Logo Preview -->
                    @if($institution->logo)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current Logo:</p>
                        <div class="w-32 h-32 rounded-lg overflow-hidden border border-gray-200">
                            <img src="{{ Storage::url($institution->logo) }}"
                                alt="Current Logo"
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endif

                    <!-- Logo Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                        <div class="mb-4">
                            <x-lucide-upload class="w-12 h-12 text-gray-400 mx-auto" />
                        </div>
                        <p class="text-gray-600 mb-2">Upload new logo</p>
                        <p class="text-sm text-gray-500 mb-4">PNG, JPG up to 2MB</p>
                        <input type="file"
                            name="logo"
                            id="logo"
                            accept="image/*"
                            class="hidden"
                            onchange="previewLogo(event)">
                        <label for="logo"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer transition">
                            <x-lucide-upload class="w-4 h-4 mr-2" />
                            Choose File
                        </label>
                    </div>

                    <!-- Logo Preview -->
                    <div id="logoPreview" class="hidden">
                        <p class="text-sm text-gray-600 mb-2">New Logo Preview:</p>
                        <div class="w-32 h-32 rounded-lg overflow-hidden border border-gray-200">
                            <img id="logoPreviewImg" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cover Image Upload -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-4">
                    <span class="flex items-center">
                        <x-lucide-image class="w-4 h-4 mr-1" />
                        Cover Image
                    </span>
                </label>

                <div class="space-y-4">
                    <!-- Current Cover Preview -->
                    @if($institution->cover_image)
                    <div class="mb-4">
                        <p class="text-sm text-gray-600 mb-2">Current Cover:</p>
                        <div class="h-32 rounded-lg overflow-hidden border border-gray-200">
                            <img src="{{ Storage::url($institution->cover_image) }}"
                                alt="Current Cover"
                                class="w-full h-full object-cover">
                        </div>
                    </div>
                    @endif

                    <!-- Cover Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-blue-400 transition-colors">
                        <div class="mb-4">
                            <x-lucide-image-plus class="w-12 h-12 text-gray-400 mx-auto" />
                        </div>
                        <p class="text-gray-600 mb-2">Upload new cover image</p>
                        <p class="text-sm text-gray-500 mb-4">PNG, JPG up to 5MB</p>
                        <input type="file"
                            name="cover_image"
                            id="cover_image"
                            accept="image/*"
                            class="hidden"
                            onchange="previewCover(event)">
                        <label for="cover_image"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 cursor-pointer transition">
                            <x-lucide-upload class="w-4 h-4 mr-2" />
                            Choose File
                        </label>
                    </div>

                    <!-- Cover Preview -->
                    <div id="coverPreview" class="hidden">
                        <p class="text-sm text-gray-600 mb-2">New Cover Preview:</p>
                        <div class="h-32 rounded-lg overflow-hidden border border-gray-200">
                            <img id="coverPreviewImg" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center text-gray-600">
                <x-lucide-info class="w-5 h-5 mr-2" />
                <p class="text-sm">Fields marked with * are required</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('vendor.institution.profile') }}"
                    class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center">
                    <x-lucide-x class="w-5 h-5 mr-2" />
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                    <x-lucide-save class="w-5 h-5 mr-2" />
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</form>

<!-- Preview Scripts -->
<script>
    function previewLogo(event) {
        const input = event.target;
        const preview = document.getElementById('logoPreview');
        const previewImg = document.getElementById('logoPreviewImg');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function previewCover(event) {
        const input = event.target;
        const preview = document.getElementById('coverPreview');
        const previewImg = document.getElementById('coverPreviewImg');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Auto-hide validation errors
    document.addEventListener('DOMContentLoaded', function() {
        const errorMessages = document.querySelectorAll('.text-red-600');
        errorMessages.forEach(error => {
            setTimeout(() => {
                error.style.opacity = '0';
                error.style.transition = 'opacity 0.5s';
                setTimeout(() => error.remove(), 500);
            }, 8000);
        });
    });
</script>

<!-- Success Toast -->
@if(session('success'))
<div class="fixed bottom-4 right-4 z-50">
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-lg flex items-center animate-slide-in">
        <x-lucide-check-circle class="w-6 h-6 mr-3 flex-shrink-0" />
        <div>
            <p class="font-semibold">Success!</p>
            <p>{{ session('success') }}</p>
        </div>
    </div>
</div>

<style>
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
</style>

<script>
    setTimeout(() => {
        const toast = document.querySelector('.fixed.bottom-4.right-4');
        if (toast) {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.5s';
            setTimeout(() => toast.remove(), 500);
        }
    }, 5000);
</script>
@endif
@endsection
