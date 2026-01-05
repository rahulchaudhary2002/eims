@extends('admin.layouts.app')

@section('title', 'Edit Vendor')

@section('page-specific-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
    /* Customizing Choices.js to match your Tailwind theme */
    .choices {
        margin-bottom: 0;
    }

    .choices__inner {
        background-color: #ffffff !important;
        border-radius: 0.5rem !important;
        border: 1px solid #d1d5db !important;
        padding: 4px 8px !important;
        min-height: 48px;
        display: flex;
        align-items: center;
    }

    .choices__list--multiple .choices__item {
        background-color: #8b5cf6 !important;
        /* Purple-500 */
        border: 1px solid #7c3aed !important;
        border-radius: 4px !important;
        padding: 2px 8px !important;
        font-size: 0.875rem !important;
    }

    .choices__list--multiple .choices__item.is-highlighted {
        background-color: #7c3aed !important;
        /* Purple-600 */
    }

    .choices[data-type*="select-one"] .choices__inner {
        padding-bottom: 4px !important;
    }

    .choices__input {
        background-color: transparent !important;
    }

    .is-focused .choices__inner {
        border-color: #8b5cf6 !important;
        box-shadow: 0 0 0 2px rgba(139, 92, 246, 0.5) !important;
    }
</style>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="p-6 flex justify-between items-center mb-6 bg-white bg-opacity-80 backdrop-blur-sm rounded-t-lg">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">🚚 Edit Vendor</h1>
            <p class="text-gray-600 mt-1">Update vendor information</p>
        </div>
        <a href="{{ route('admin.vendor.index') }}" class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
            <x-lucide-arrow-left class="w-5 h-5 mr-2" />
            Back to Vendors
        </a>
    </div>

    @if($errors->any())
    <div class="px-6 pb-4">
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="px-6 pb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('admin.vendor.update', $vendor) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-user class="w-5 h-5 mr-2 text-purple-500" />
                            Vendor Name *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $vendor->name) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                            placeholder="Enter vendor name" required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-mail class="w-5 h-5 mr-2 text-purple-500" />
                            Email Address *
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email', $vendor->email) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror"
                            placeholder="Enter email address" required>
                        @error('email')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-phone class="w-5 h-5 mr-2 text-purple-500" />
                            Phone Number
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $vendor->phone) }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('phone') border-red-500 @enderror"
                            placeholder="Enter phone number">
                        @error('phone')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="institutions" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-building class="w-5 h-5 mr-2 text-purple-500" />
                            Assign to Institutions
                        </label>
                        <select name="institutions[]" id="institutions" multiple
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('institutions') border-red-500 @enderror">
                            @foreach($institutions as $institution)
                            <option value="{{ $institution->id }}"
                                {{ (collect(old('institutions'))->contains($institution->id)) || $vendor->institutions->contains($institution->id) ? 'selected' : '' }}>
                                {{ $institution->name }} ({{ ucfirst($institution->type) }})
                            </option>
                            @endforeach
                        </select>
                        <p class="text-gray-500 text-sm mt-1">Select institutions this vendor can access</p>
                        @error('institutions')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                        @error('institutions.*')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-lock class="w-5 h-5 mr-2 text-purple-500" />
                            Password
                        </label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password') border-red-500 @enderror"
                            placeholder="Leave blank to keep current password">
                        <p class="text-xs text-gray-500 mt-1">Leave blank to keep current password</p>
                        @error('password')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-lock class="w-5 h-5 mr-2 text-purple-500" />
                            Confirm Password
                        </label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Confirm new password">
                        @error('password_confirmation')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.vendor.index') }}"
                        class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-x class="w-5 h-5 mr-2" />
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-save class="w-5 h-5 mr-2" />
                        Update Vendor
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('page-specific-script')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Institutions multi-select
        const institutionsElement = document.getElementById('institutions');
        if (institutionsElement) {
            const institutionsChoices = new Choices(institutionsElement, {
                removeItemButton: true,
                placeholderValue: 'Select institutions...',
                searchEnabled: true,
                shouldSort: false,
                itemSelectText: '',
                noResultsText: 'No institutions found',
                searchResultLimit: 10,
            });
        }
    });
</script>
@endsection