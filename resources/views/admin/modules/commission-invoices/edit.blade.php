@extends('admin.layouts.app')
@section('title', 'Edit Invoice - ' . $commissionInvoice->invoice_number)
@section('page-title', 'Edit Commission Invoice')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Edit Commission Invoice"
        subtitle="{{ $commissionInvoice->invoice_number }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Commission Invoices', 'route' => 'admin.commission-invoices.index'],
            ['label' => $commissionInvoice->invoice_number, 'route' => 'admin.commission-invoices.show', 'routeParam' => $commissionInvoice],
            ['label' => 'Edit'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.commission-invoices.show', $commissionInvoice) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <form action="{{ route('admin.commission-invoices.update', $commissionInvoice) }}" method="POST">
        @csrf @method('PUT')
        <div class="eims-card p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @include('admin.modules.commission-invoices.partials.form')
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-5 border-t border-slate-100">
                <a href="{{ route('admin.commission-invoices.show', $commissionInvoice) }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection
