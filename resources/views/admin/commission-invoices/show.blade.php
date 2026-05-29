@extends('admin.layouts.app')
@section('title', 'Invoice — ' . $commissionInvoice->invoice_number)
@section('page-title', 'Commission Invoice Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="{{ $commissionInvoice->invoice_number }}"
        subtitle="Commission Invoice Details"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Commission Invoices', 'route' => 'admin.commission-invoices.index'],
            ['label' => $commissionInvoice->invoice_number],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.commission-invoices.edit', $commissionInvoice) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.commission-invoices.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- References --}}
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Invoice Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Invoice Number</dt>
                        <dd class="mt-1 font-mono font-medium text-slate-800">{{ $commissionInvoice->invoice_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Institution</dt>
                        <dd class="mt-1">
                            @if($commissionInvoice->institution)
                                <a href="{{ route('admin.institutions.show', $commissionInvoice->institution) }}" class="text-blue-600 hover:underline">
                                    {{ $commissionInvoice->institution->name }}
                                </a>
                            @else —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Admission</dt>
                        <dd class="mt-1">
                            @if($commissionInvoice->admission)
                                <a href="{{ route('admin.admissions.show', $commissionInvoice->admission) }}" class="text-blue-600 hover:underline font-mono">
                                    {{ $commissionInvoice->admission->admission_number }}
                                </a>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $commissionInvoice->admission->student->name ?? '' }}</div>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Referral Agreement</dt>
                        <dd class="mt-1">
                            @if($commissionInvoice->referralAgreement)
                                <a href="{{ route('admin.referral-agreements.show', $commissionInvoice->referralAgreement) }}" class="text-blue-600 hover:underline">
                                    Agreement #{{ $commissionInvoice->referralAgreement->id }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Invoice Date</dt>
                        <dd class="mt-1 text-slate-700">{{ $commissionInvoice->invoice_date?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Due Date</dt>
                        <dd class="mt-1 text-slate-700">{{ $commissionInvoice->due_date?->format('d M Y') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Paid At</dt>
                        <dd class="mt-1 text-slate-700">{{ $commissionInvoice->paid_at?->format('d M Y, H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $commissionInvoice->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Financials --}}
            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Financials</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Commission Type</dt>
                        <dd class="mt-1 text-slate-800">{{ \App\Models\CommissionInvoice::COMMISSION_TYPES[$commissionInvoice->commission_type] ?? $commissionInvoice->commission_type }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Admission Paid Amount</dt>
                        <dd class="mt-1 font-mono text-slate-800">{{ number_format((float) $commissionInvoice->admission_paid_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Commission Value</dt>
                        <dd class="mt-1 font-mono text-slate-800">{{ number_format((float) $commissionInvoice->commission_value, 4) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Commission Amount</dt>
                        <dd class="mt-1 font-mono font-semibold text-slate-800">{{ number_format((float) $commissionInvoice->commission_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Student Cashback Amount</dt>
                        <dd class="mt-1 font-mono text-slate-800">{{ number_format((float) $commissionInvoice->student_cashback_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Platform Revenue Amount</dt>
                        <dd class="mt-1 font-mono font-semibold text-slate-800">{{ number_format((float) $commissionInvoice->platform_revenue_amount, 2) }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Payments Summary + List --}}
            <div class="eims-card overflow-hidden">
                <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">Payments</h3>
                    <span class="ml-1 text-xs text-slate-400">{{ $commissionInvoice->payments->count() }} payment(s)</span>
                    <div class="ml-auto">
                        <a href="{{ route('admin.commission-payments.create', ['commission_invoice_id' => $commissionInvoice->id]) }}"
                           class="btn btn-primary text-xs py-1.5">Add Payment</a>
                    </div>
                </div>

                {{-- Payment summary bar --}}
                @php
                    $totalPaid = $commissionInvoice->payments->sum('amount');
                    $commissionAmt = (float) $commissionInvoice->commission_amount;
                    $remaining = max(0, $commissionAmt - $totalPaid);
                    $paidPct = $commissionAmt > 0 ? min(100, round($totalPaid / $commissionAmt * 100)) : 0;
                @endphp
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <div class="grid grid-cols-3 gap-4 text-sm mb-3">
                        <div>
                            <p class="text-xs text-slate-500 mb-0.5">Commission Due</p>
                            <p class="font-mono font-semibold text-slate-800">{{ number_format($commissionAmt, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-0.5">Total Paid</p>
                            <p class="font-mono font-semibold text-green-700">{{ number_format($totalPaid, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 mb-0.5">Remaining</p>
                            <p class="font-mono font-semibold {{ $remaining > 0 ? 'text-red-600' : 'text-green-600' }}">{{ number_format($remaining, 2) }}</p>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full transition-all" style="width: {{ $paidPct }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">{{ $paidPct }}% paid</p>
                </div>

                @if($commissionInvoice->payments->isEmpty())
                    <div class="text-center py-8 text-slate-400">
                        <p class="text-sm mb-3">No payments recorded yet.</p>
                        <a href="{{ route('admin.commission-payments.create', ['commission_invoice_id' => $commissionInvoice->id]) }}"
                           class="btn btn-primary text-xs">Record First Payment</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="eims-table w-full">
                            <thead>
                                <tr>
                                    <th>Payment Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Transaction Ref</th>
                                    <th>Proof</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commissionInvoice->payments as $payment)
                                    <tr>
                                        <td class="text-sm">{{ $payment->payment_date?->format('d M Y') ?? '—' }}</td>
                                        <td class="font-mono font-semibold text-sm">{{ number_format((float) $payment->amount, 2) }}</td>
                                        <td class="text-sm">{{ \App\Models\CommissionPayment::PAYMENT_METHODS[$payment->payment_method] ?? $payment->payment_method }}</td>
                                        <td class="font-mono text-xs text-slate-500">{{ $payment->transaction_reference ?? '—' }}</td>
                                        <td>
                                            @if($payment->payment_proof)
                                                <a href="{{ Storage::url($payment->payment_proof) }}" target="_blank" class="text-blue-600 hover:underline text-xs">View</a>
                                            @else
                                                <span class="text-slate-400 text-xs">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="flex items-center justify-center gap-1">
                                                <a href="{{ route('admin.commission-payments.show', $payment) }}" class="btn-icon btn-icon-view" title="View">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                </a>
                                                <a href="{{ route('admin.commission-payments.edit', $payment) }}" class="btn-icon btn-icon-edit" title="Edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                                </a>
                                                <form action="{{ route('admin.commission-payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Delete this payment?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

        </div>

        {{-- Status + Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Status</h3>
                <span class="badge text-sm">{{ \App\Models\CommissionInvoice::STATUSES[$commissionInvoice->status] ?? $commissionInvoice->status }}</span>

                <form action="{{ route('admin.commission-invoices.update-status', $commissionInvoice) }}" method="POST" class="mt-4 space-y-2">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control text-sm">
                        @foreach(\App\Models\CommissionInvoice::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $commissionInvoice->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-full text-sm">Update Status</button>
                </form>
            </div>

            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.commission-invoices.edit', $commissionInvoice) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Invoice
                    </a>
                    <form action="{{ route('admin.commission-invoices.destroy', $commissionInvoice) }}" method="POST" onsubmit="return confirm('Delete this commission invoice? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Invoice
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    {{-- Scholarship Cashback --}}
    <div class="eims-card overflow-hidden mt-5">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
            <div class="p-2 bg-emerald-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z"/></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Scholarship Cashback</h3>
            <div class="ml-auto">
                @if(! $commissionInvoice->scholarshipCashback)
                    <a href="{{ route('admin.scholarship-cashbacks.create', ['commission_invoice_id' => $commissionInvoice->id]) }}"
                       class="btn btn-primary text-xs py-1.5">Add Cashback</a>
                @else
                    <a href="{{ route('admin.scholarship-cashbacks.show', $commissionInvoice->scholarshipCashback) }}"
                       class="btn btn-secondary text-xs py-1.5">View Cashback</a>
                @endif
            </div>
        </div>

        @if($commissionInvoice->scholarshipCashback)
            @php $cashback = $commissionInvoice->scholarshipCashback; @endphp
            <div class="p-6">
                <dl class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Student</dt>
                        <dd class="mt-1">
                            @if($cashback->student)
                                <a href="{{ route('admin.students.show', $cashback->student) }}" class="text-blue-600 hover:underline">
                                    {{ $cashback->student->name }}
                                </a>
                            @else —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Commission Received</dt>
                        <dd class="mt-1 font-mono">{{ number_format((float) $cashback->commission_received_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Cashback %</dt>
                        <dd class="mt-1 font-mono">{{ number_format((float) $cashback->cashback_percentage, 4) }}%</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Cashback Amount</dt>
                        <dd class="mt-1 font-mono font-semibold text-green-700">{{ number_format((float) $cashback->cashback_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Paid At</dt>
                        <dd class="mt-1">{{ $cashback->paid_at?->format('d M Y, H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Status</dt>
                        <dd class="mt-1"><span class="badge">{{ \App\Models\ScholarshipCashback::STATUSES[$cashback->status] ?? $cashback->status }}</span></dd>
                    </div>
                </dl>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('admin.scholarship-cashbacks.show', $cashback) }}" class="btn btn-secondary text-xs">View Details</a>
                    <a href="{{ route('admin.scholarship-cashbacks.edit', $cashback) }}" class="btn btn-secondary text-xs">Edit</a>
                </div>
            </div>
        @else
            <div class="text-center py-8 text-slate-400">
                <p class="text-sm mb-3">No scholarship cashback linked to this invoice.</p>
                <a href="{{ route('admin.scholarship-cashbacks.create', ['commission_invoice_id' => $commissionInvoice->id]) }}"
                   class="btn btn-primary text-xs">Create Cashback</a>
            </div>
        @endif
    </div>

</div>
@endsection
