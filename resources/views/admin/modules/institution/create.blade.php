@extends('admin.layouts.app')
@section('title', 'Create Institution')
@section('page-title', 'Create Institution')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Create Institution" subtitle="Add a new educational institution"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Institutions','route'=>'admin.institutions.index'],['label'=>'Create']]">
        <x-slot:actions>
            <a href="{{ route('admin.institutions.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @if($errors->any())
    <div class="eims-card p-4 border border-red-200 bg-red-50">
        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.institutions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        @include('admin.modules.institution.partials.form')

        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('admin.institutions.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Create Institution
            </button>
        </div>
    </form>

</div>
@endsection

@section('page-specific-script')
<script>
    // Auto-generate slug preview from name (not submitted unless modified)
    document.getElementById('name')?.addEventListener('input', function() {
        const slugField = document.getElementById('slug');
        if (slugField && !slugField.dataset.modified) {
            slugField.placeholder = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .trim();
        }
    });
    document.getElementById('slug')?.addEventListener('input', function() {
        this.dataset.modified = '1';
    });
</script>
@endsection
