@extends('admin.layouts.app')
@section('title', 'Add Program')
@section('page-title', 'Add Program')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Add Program" subtitle="Create a new academic program"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Programs','route' => 'admin.programs.index'],
            ['label'=>'Add Program'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="danger" :message="session('error')" />

    <form action="{{ route('admin.programs.store') }}" method="POST" class="space-y-5">
        @csrf

        @include('admin.modules.programs.partials.form')

        <div class="flex justify-end gap-3 pb-4">
            <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                Save Program
            </button>
        </div>
    </form>

</div>
@endsection
