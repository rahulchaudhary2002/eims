@extends('admin.layouts.app')
@section('title', 'Commission Payment #' . $commissionPayment->id)
@section('page-title', 'Commission Payment Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Payment #{{ $commissionPayment->id }}"
        subtitle="Commission Payment Details"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Commission Payments', 'route' => 'admin.commission-payments.index'],
            ['label' => 'Payment #' . $commissionPayment->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.commission-payments.edit', $commissionPayment) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.commission-payments.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main Details --}}
        <div class="lg:col-span-2">
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Payment Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Commission Invoice</dt>
                        <dd class="mt-1">
                            @if($commissionPayment->commissionInvoice)
                                <a href="{{ route('admin.commission-invoices.show', $commissionPayment->commissionInvoice) }}" class="text-blue-600 hover:underline font-mono">
                                    {{ $commissionPayment->commissionInvoice->invoice_number }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Institution</dt>
                        <dd class="mt-1 text-slate-800">
                            @if($commissionPayment->commissionInvoice?->institution)
                                <a href="{{ route('admin.institutions.show', $commissionPayment->commissionInvoice->institution) }}" class="text-blue-600 hover:underline">
                                    {{ $commissionPayment->commissionInvoice->institution->name }}
                                </a>
                            @else
                                —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Amount</dt>
                        <dd class="mt-1 font-mono font-semibold text-slate-800 text-base">{{ number_format((float) $commissionPayment->amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Payment Method</dt>
                        <dd class="mt-1 text-slate-800">{{ \App\Models\CommissionPayment::PAYMENT_METHODS[$commissionPayment->payment_method] ?? $commissionPayment->payment_method }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Payment Date</dt>
                        <dd class="mt-1 text-slate-800">{{ $commissionPayment->payment_date?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Transaction Reference</dt>
                        <dd class="mt-1 font-mono text-slate-700">{{ $commissionPayment->transaction_reference ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Payment Proof</dt>
                        <dd class="mt-1">
                            @if($commissionPayment->payment_proof)
                                <a href="{{ Storage::url($commissionPayment->payment_proof) }}" target="_blank" class="text-blue-600 hover:underline text-sm inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                                    {{ basename($commissionPayment->payment_proof) }}
                                </a>
                            @else
                                <span class="text-slate-400">No proof uploaded</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $commissionPayment->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($commissionPayment->remarks)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Remarks</dt>
                            <dd class="mt-1 text-slate-700 whitespace-pre-line">{{ $commissionPayment->remarks }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>

        {{-- Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.commission-payments.edit', $commissionPayment) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Payment
                    </a>
                    @if($commissionPayment->commissionInvoice)
                        <a href="{{ route('admin.commission-invoices.show', $commissionPayment->commissionInvoice) }}" class="btn btn-secondary w-full text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
                            View Invoice
                        </a>
                    @endif
                    <form action="{{ route('admin.commission-payments.destroy', $commissionPayment) }}" method="POST" onsubmit="return confirm('Delete this payment? Invoice status will be recalculated.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Payment
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
