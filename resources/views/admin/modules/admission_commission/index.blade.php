@extends('admin.layouts.app')
@section('title', 'Admission Commissions')
@section('page-title', 'Admission Commissions')
@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Admission Commissions" subtitle="Manage admission commissions"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Admission Commissions']]">
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    <div class="eims-card p-0 overflow-hidden">
        <div class="eims-table-wrapper">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Admission Receipt</th>
                        <th>Program</th>
                        <th>Applied Institution</th>
                        <th>Commission</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commissions as $index => $commission)
                    <tr x-data="{ open: false, commission: '{{ $commission->commission_amount ?? '' }}' }">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <a href="{{ asset('storage/' . $commission->admissionReward->admission_receipt) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline text-sm">
                                View Receipt
                            </a>
                        </td>
                        <td>{{ $commission->admissionReward->admissionApplication->program->name ?? 'N/A' }}</td>
                        <td>{{ $commission->institution->name ?? 'N/A' }}</td>
                        <td>{{ $commission->commission_amount ?? '-' }}</td>
                        <td>
                            <x-admin.status-badge :status="$commission->is_paid ? 'active' : 'inactive'" />
                        </td>
                        <td class="actions-cell">
                            @if(!$commission->is_paid)
                            <div class="flex items-center justify-end">
                                <form method="POST" action="{{ route('admin.admission.commission.markPaid', $commission) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                        Mark as Paid
                                    </button>
                                </form>
                            </div>
                            @else
                            <span class="text-slate-400 text-sm text-right block">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <x-admin.empty-state title="No commissions" description="There are no admission commissions yet." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($commissions,'links') && $commissions->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $commissions->links() }}</div>
        @endif
    </div>

</div>
@endsection
