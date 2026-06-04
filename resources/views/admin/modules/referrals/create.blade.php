@extends('admin.layouts.app')
@section('title', 'Add Referral')
@section('page-title', 'Add Referral')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Add Referral"
        subtitle="Create a new referral record."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Referrals', 'route' => 'admin.referrals.index'],
            ['label' => 'Add'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.referrals.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('admin.referrals.store') }}" method="POST">
        @csrf
        <div class="eims-card p-6">
            <div class="space-y-5">
                @include('admin.modules.referrals.partials.form')
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.referrals.index') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Referral</button>
            </div>
        </div>
    </form>
</div>
@endsection
