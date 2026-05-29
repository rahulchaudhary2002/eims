@extends('admin.layouts.app')
@section('title', 'Edit User — ' . $user->name)
@section('page-title', 'Edit User')

@section('content')
<div class="space-y-5">

    <x-admin.page-header :title="'Edit: ' . $user->name" subtitle="Update user account details"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Users','route'=>'admin.users.index'],['label'=>$user->name,'route'=>'admin.users.show','param'=>$user],['label'=>'Edit']]">
        <x-slot:actions>
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-2 space-y-5">
                @include('admin.modules.user.partials.form')
            </div>

            <div class="space-y-5">
                <div class="eims-card p-5 space-y-3">
                    <p class="text-sm font-semibold text-slate-700">Save</p>
                    <button type="submit" class="btn btn-primary w-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Update User
                    </button>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary w-full text-center">Cancel</a>
                </div>
            </div>
        </div>

    </form>

</div>
@endsection
