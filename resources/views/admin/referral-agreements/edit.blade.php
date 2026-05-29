@extends('admin.layouts.app')
@section('title', 'Edit Referral Agreement')
@section('page-title', 'Edit Referral Agreement')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Edit Referral Agreement"
        subtitle="Update the commission agreement details."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Referral Agreements', 'route' => 'admin.referral-agreements.index'],
            ['label' => $referralAgreement->institution->name ?? 'Agreement', 'route' => 'admin.referral-agreements.show', 'routeParam' => $referralAgreement],
            ['label' => 'Edit'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.referral-agreements.show', $referralAgreement) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('admin.referral-agreements.update', $referralAgreement) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="eims-card p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @include('admin.referral-agreements.partials.form')
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.referral-agreements.show', $referralAgreement) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection
