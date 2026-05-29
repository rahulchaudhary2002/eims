@extends('admin.layouts.app')
@section('title', 'Edit Institution Category')
@section('page-title', 'Edit Category')
@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="Edit Institution Category"
        subtitle="Update category details for: {{ $institutionCategory->name }}"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Categories','route'=>'admin.institution-category.index'],['label'=>'Edit']]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-category.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @if($errors->any())
    <div class="alert alert-danger">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
        <ul class="list-disc list-inside text-sm space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    <x-admin.form-card title="Category Details">
        <form action="{{ route('admin.institution-category.update', $institutionCategory) }}" method="POST">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-input name="name" label="Category Name" :value="old('name', $institutionCategory->name)" placeholder="Enter category name" required />
                <x-admin.form-input name="slug" label="Slug" :value="old('slug', $institutionCategory->slug)" placeholder="Auto-generated if left empty" help="Lowercase letters, numbers and hyphens only." />
            </div>
            <div class="flex items-center justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.institution-category.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </x-admin.form-card>

</div>
        </div>
    </div>
</div>
@endsection
