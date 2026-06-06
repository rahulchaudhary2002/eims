@extends('admin.layouts.app')
@section('title', 'Referral Agreement - ' . ($referralAgreement->institution->name ?? 'Details'))
@section('page-title', 'Referral Agreement Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="{{ $referralAgreement->institution->name ?? 'Agreement #' . $referralAgreement->id }}"
        subtitle="Referral Agreement Details"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Referral Agreements', 'route' => 'admin.referral-agreements.index'],
            ['label' => $referralAgreement->institution->name ?? 'Agreement #' . $referralAgreement->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.referral-agreements.edit', $referralAgreement) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.referral-agreements.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Agreement Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Institution</dt>
                        <dd class="mt-1 text-slate-800 font-medium">
                            @if($referralAgreement->institution)
                                <a href="{{ route('admin.institutions.show', $referralAgreement->institution) }}" class="text-blue-600 hover:underline">
                                    {{ $referralAgreement->institution->name }}
                                </a>
                            @else
                                -
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Commission Type</dt>
                        <dd class="mt-1 text-slate-800">{{ \App\Models\ReferralAgreement::COMMISSION_TYPES[$referralAgreement->commission_type] ?? $referralAgreement->commission_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Commission Value</dt>
                        <dd class="mt-1 text-slate-800 font-mono">{{ number_format((float) $referralAgreement->commission_value, 4) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Student Cashback %</dt>
                        <dd class="mt-1 text-slate-800 font-mono">{{ number_format((float) $referralAgreement->student_cashback_percentage, 4) }}%</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Start Date</dt>
                        <dd class="mt-1 text-slate-800">{{ $referralAgreement->start_date?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">End Date</dt>
                        <dd class="mt-1 text-slate-800">{{ $referralAgreement->end_date?->format('d M Y') ?? '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Agreement File</dt>
                        <dd class="mt-1">
                            @if(storage_exists($referralAgreement->agreement_file))
                                <a href="{{ storage_url($referralAgreement->agreement_file) }}" target="_blank" class="text-blue-600 hover:underline text-sm inline-flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18.375 12.739l-7.693 7.693a4.5 4.5 0 01-6.364-6.364l10.94-10.94A3 3 0 1119.5 7.372L8.552 18.32m.009-.01l-.01.01m5.699-9.941l-7.81 7.81a1.5 1.5 0 002.112 2.13"/></svg>
                                    {{ basename($referralAgreement->agreement_file) }}
                                </a>
                            @else
                                <span class="text-slate-400">No file uploaded</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</dt>
                        <dd class="mt-1 text-slate-600 text-xs">{{ $referralAgreement->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Last Updated</dt>
                        <dd class="mt-1 text-slate-600 text-xs">{{ $referralAgreement->updated_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Status + Actions sidebar --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Status</h3>
                <span class="badge text-sm">{{ \App\Models\ReferralAgreement::STATUSES[$referralAgreement->status] ?? $referralAgreement->status }}</span>

                <form action="{{ route('admin.referral-agreements.update-status', $referralAgreement) }}" method="POST" class="mt-4 space-y-2">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control text-sm">
                        @foreach(\App\Models\ReferralAgreement::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $referralAgreement->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-full text-sm">Update Status</button>
                </form>
            </div>

            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.referral-agreements.edit', $referralAgreement) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Agreement
                    </a>
                    <form action="{{ route('admin.referral-agreements.destroy', $referralAgreement) }}" method="POST" onsubmit="return confirm('Delete this referral agreement? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Agreement
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- Commission Invoices --}}
    <div class="eims-card overflow-hidden mt-5">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
            <div class="p-2 bg-amber-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Commission Invoices</h3>
            <span class="ml-1 text-xs text-slate-400">{{ $referralAgreement->commissionInvoices->count() }} invoice(s)</span>
            <div class="ml-auto">
                <a href="{{ route('admin.commission-invoices.create', ['referral_agreement_id' => $referralAgreement->id, 'institution_id' => $referralAgreement->institution_id]) }}"
                   class="btn btn-primary text-xs py-1.5">Add Invoice</a>
            </div>
        </div>

        @if($referralAgreement->commissionInvoices->isEmpty())
            <div class="text-center py-8 text-slate-400">
                <p class="text-sm mb-3">No commission invoices linked to this agreement yet.</p>
                <a href="{{ route('admin.commission-invoices.create', ['referral_agreement_id' => $referralAgreement->id, 'institution_id' => $referralAgreement->institution_id]) }}"
                   class="btn btn-primary text-xs">Add First Invoice</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Commission Amt</th>
                            <th>Invoice Date</th>
                            <th>Due Date</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($referralAgreement->commissionInvoices as $invoice)
                            <tr>
                                <td class="font-mono text-sm">
                                    <a href="{{ route('admin.commission-invoices.show', $invoice) }}" class="text-blue-600 hover:underline">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td class="font-mono text-sm">{{ number_format((float) $invoice->commission_amount, 2) }}</td>
                                <td class="text-xs text-slate-500">{{ $invoice->invoice_date?->format('d M Y') ?? '-' }}</td>
                                <td class="text-xs text-slate-500">{{ $invoice->due_date?->format('d M Y') ?? '-' }}</td>
                                <td><span class="badge">{{ \App\Models\CommissionInvoice::STATUSES[$invoice->status] ?? $invoice->status }}</span></td>
                                <td>
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.commission-invoices.show', $invoice) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.commission-invoices.edit', $invoice) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-slate-100 text-right">
                <a href="{{ route('admin.commission-invoices.index', ['referral_agreement_id' => $referralAgreement->id]) }}"
                   class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    View all invoices for this agreement →
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
