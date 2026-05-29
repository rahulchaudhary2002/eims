@extends('admin.layouts.app')
@section('title', 'Add User')
@section('page-title', 'Add User')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Add User" subtitle="Create a new web guard user account"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Users','route'=>'admin.users.index'],['label'=>'Add User']]">
        <x-slot:actions>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 space-y-5">
                @include('admin.modules.user.partials.form')
            </div>

            <div class="space-y-5">
                <div class="eims-card p-5 space-y-3">
                    <p class="text-sm font-semibold text-slate-700">Save</p>
                    <button type="submit" class="btn btn-primary w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Create User
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary w-full text-center">Cancel</a>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
