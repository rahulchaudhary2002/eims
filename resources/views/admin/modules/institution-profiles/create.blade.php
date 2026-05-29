@extends('admin.layouts.app')
@section('title', 'Add Institution Profile')
@section('page-title', 'Add Institution Profile')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Add Institution Profile" subtitle="Create a new institution profile"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Institution Profiles','route' => 'admin.institution-profiles.index'],
            ['label'=>'Add Profile'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-profiles.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="danger" :message="session('error')" />

    <form action="{{ route('admin.institution-profiles.store') }}" method="POST" class="space-y-5">
        @csrf

        @include('admin.modules.institution-profiles.partials.form')

        <div class="flex justify-end gap-3 pb-4">
            <a href="{{ route('admin.institution-profiles.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Create Profile
            </button>
        </div>
    </form>

</div>
@endsection
