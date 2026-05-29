@extends('admin.layouts.app')
@section('title', 'Edit Vendor')
@section('page-title', 'Edit Vendor')

@section('page-specific-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
<style>
    .choices { margin-bottom: 0; }
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
        border: 1px solid #7c3aed !important;
        border-radius: 4px !important;
        padding: 2px 8px !important;
        font-size: 0.875rem !important;
    }
    .choices__list--multiple .choices__item.is-highlighted { background-color: #7c3aed !important; }
    .choices[data-type*="select-one"] .choices__inner { padding-bottom: 4px !important; }
    .choices__input { background-color: transparent !important; }
    .is-focused .choices__inner { border-color: #8b5cf6 !important; box-shadow: 0 0 0 2px rgba(139,92,246,0.5) !important; }
</style>
@endsection

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Edit Vendor" subtitle="Update vendor information"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Vendors','route'=>'admin.vendor.index'],['label'=>'Edit']]">
        <x-slot:actions>
            <a href="{{ route('admin.vendor.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back to Vendors
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @if($errors->any())
    <div class="alert alert-danger">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <x-admin.form-card title="Vendor Details">
        <form action="{{ route('admin.vendor.update', $vendor) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-input name="name" label="Vendor Name" :value="old('name', $vendor->name)" required />
                <x-admin.form-input name="email" label="Email Address" type="email" :value="old('email', $vendor->email)" required />
                <x-admin.form-input name="phone" label="Phone Number" :value="old('phone', $vendor->phone)" />

                <div class="md:col-span-2">
                    <label for="institutions" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Assign to Institutions
                    </label>
                    <select name="institutions[]" id="institutions" multiple
                        class="w-full px-3 py-2.5 border border-slate-300 rounded-lg @error('institutions') border-red-500 @enderror">
                        @foreach($institutions as $institution)
                        <option value="{{ $institution->id }}"
                            {{ (collect(old('institutions'))->contains($institution->id)) || $vendor->institutions->contains($institution->id) ? 'selected' : '' }}>
                            {{ $institution->name }} ({{ ucfirst($institution->type) }})
                        </option>
                        @endforeach
                    </select>
                    <p class="text-slate-500 text-xs mt-1">Select institutions this vendor can access</p>
                    @error('institutions')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                    @error('institutions.*')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-admin.form-input name="password" label="Password" type="password" />
                    <p class="text-slate-500 text-xs mt-1">Leave blank to keep current password</p>
                </div>
                <x-admin.form-input name="password_confirmation" label="Confirm Password" type="password" />
            </div>

            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.vendor.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Update Vendor
                </button>
            </div>
        </form>
    </x-admin.form-card>

</div>
@endsection

@section('page-specific-script')
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const institutionsElement = document.getElementById('institutions');
        if (institutionsElement) {
            new Choices(institutionsElement, {
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
