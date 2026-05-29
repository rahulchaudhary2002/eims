@extends('admin.layouts.app')
@section('title', 'Create Institution Type')
@section('page-title', 'Create Type')
@section('content')
<div class="space-y-5">
    <x-admin.page-header title="Create Institution Type" subtitle="Add a new type to classify institutions"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Institution Types','route'=>'admin.institution-type.index'],['label'=>'Create']]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-type.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>
    @if($errors->any())
    <div class="alert alert-danger"><svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        <ul class="list-disc list-inside text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif
    <x-admin.form-card title="Type Details">
        <form action="{{ route('admin.institution-type.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-input name="name" label="Type Name" placeholder="Enter institution type name" required />
                <x-admin.form-input name="slug" label="Slug" placeholder="Auto-generated if left empty" />
            </div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.institution-type.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Create Type
                </button>
            </div>
        </form>
    </x-admin.form-card>
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
            <form action="{{ route('admin.institution-type.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-building-2 class="w-5 h-5 mr-2 text-blue-500" />
                            Institution Type Name *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror"
                            placeholder="Enter institution type name" required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                            <x-lucide-link class="w-5 h-5 mr-2 text-blue-500" />
                            Slug
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 @error('slug') border-red-500 @enderror"
                            placeholder="Auto-generated from name if left empty">
                        @error('slug')
                        <p class="text-red-500 text-sm mt-1 flex items-center">
                            <x-lucide-alert-circle class="w-4 h-4 mr-1" />
                            {{ $message }}
                        </p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.institution-type.index') }}"
                        class="bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-x class="w-5 h-5 mr-2" />
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 flex items-center">
                        <x-lucide-save class="w-5 h-5 mr-2" />
                        Create Institution Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
