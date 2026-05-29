@extends('admin.layouts.app')
@section('title', 'Cashback #' . $scholarshipCashback->id)
@section('page-title', 'Scholarship Cashback Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Cashback #{{ $scholarshipCashback->id }}"
        subtitle="{{ $scholarshipCashback->student->name ?? 'Scholarship Cashback' }}"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Scholarship Cashbacks', 'route' => 'admin.scholarship-cashbacks.index'],
            ['label' => 'Cashback #' . $scholarshipCashback->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.scholarship-cashbacks.edit', $scholarshipCashback) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.scholarship-cashbacks.index') }}" class="btn btn-secondary">
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
                <h3 class="text-base font-semibold text-slate-800 mb-4">Cashback Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Student</dt>
                        <dd class="mt-1">
                            @if($scholarshipCashback->student)
                                <a href="{{ route('admin.students.show', $scholarshipCashback->student) }}" class="text-blue-600 hover:underline">
                                    {{ $scholarshipCashback->student->name }}
                                </a>
                            @else —
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Commission Invoice</dt>
                        <dd class="mt-1">
                            @if($scholarshipCashback->commissionInvoice)
                                <a href="{{ route('admin.commission-invoices.show', $scholarshipCashback->commissionInvoice) }}" class="text-blue-600 hover:underline font-mono">
                                    {{ $scholarshipCashback->commissionInvoice->invoice_number }}
                                </a>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $scholarshipCashback->commissionInvoice->institution->name ?? '' }}</div>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Application</dt>
                        <dd class="mt-1">
                            @if($scholarshipCashback->application)
                                <a href="{{ route('admin.applications.show', $scholarshipCashback->application) }}" class="text-blue-600 hover:underline font-mono text-sm">
                                    {{ $scholarshipCashback->application->application_number }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Payment Method</dt>
                        <dd class="mt-1 text-slate-800">
                            {{ \App\Models\ScholarshipCashback::PAYMENT_METHODS[$scholarshipCashback->payment_method] ?? ($scholarshipCashback->payment_method ?: '—') }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Transaction Reference</dt>
                        <dd class="mt-1 font-mono text-slate-700">{{ $scholarshipCashback->transaction_reference ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Paid At</dt>
                        <dd class="mt-1 text-slate-800">{{ $scholarshipCashback->paid_at?->format('d M Y, H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $scholarshipCashback->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                    @if($scholarshipCashback->remarks)
                        <div class="sm:col-span-2">
                            <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Remarks</dt>
                            <dd class="mt-1 text-slate-700 whitespace-pre-line">{{ $scholarshipCashback->remarks }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Financials</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-3 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Commission Received</dt>
                        <dd class="mt-1 font-mono text-slate-800">{{ number_format((float) $scholarshipCashback->commission_received_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Cashback %</dt>
                        <dd class="mt-1 font-mono text-slate-800">{{ number_format((float) $scholarshipCashback->cashback_percentage, 4) }}%</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Cashback Amount</dt>
                        <dd class="mt-1 font-mono font-semibold text-green-700 text-base">{{ number_format((float) $scholarshipCashback->cashback_amount, 2) }}</dd>
                    </div>
                </dl>
            </div>

        </div>

        {{-- Status + Actions --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Status</h3>
                <span class="badge text-sm">{{ \App\Models\ScholarshipCashback::STATUSES[$scholarshipCashback->status] ?? $scholarshipCashback->status }}</span>

                <form action="{{ route('admin.scholarship-cashbacks.update-status', $scholarshipCashback) }}" method="POST" class="mt-4 space-y-2">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control text-sm">
                        @foreach(\App\Models\ScholarshipCashback::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $scholarshipCashback->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-full text-sm">Update Status</button>
                </form>
            </div>

            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.scholarship-cashbacks.edit', $scholarshipCashback) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Cashback
                    </a>
                    <form action="{{ route('admin.scholarship-cashbacks.destroy', $scholarshipCashback) }}" method="POST" onsubmit="return confirm('Delete this cashback? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Cashback
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
