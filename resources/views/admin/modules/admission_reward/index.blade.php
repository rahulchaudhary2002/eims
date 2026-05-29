@extends('admin.layouts.app')
@section('title', 'Admission Rewards')
@section('page-title', 'Admission Rewards')
@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Admission Rewards" subtitle="Manage admission reward requests"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Admission Rewards']]">
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
                        <th>Reward</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rewards as $index => $reward)
                    <tr x-data="{ open: false, reward: '{{ $reward->reward ?? '' }}' }">
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <a href="{{ asset('storage/' . $reward->admission_receipt) }}" target="_blank" class="text-blue-600 hover:text-blue-800 hover:underline text-sm">
                                View Receipt
                            </a>
                        </td>
                        <td>{{ $reward->admissionApplication->program->name ?? 'N/A' }}</td>
                        <td>{{ $reward->admissionApplication->admission->institution->name ?? 'N/A' }}</td>
                        <td>{{ $reward->reward ?? '-' }}</td>
                        <td>
                            <x-admin.status-badge :status="$reward->status" />
                        </td>
                        <td class="actions-cell">
                            @if($reward->status === 'pending')
                            <div class="flex items-center justify-end gap-1.5">
                                <!-- Approve Button -->
                                <button @click="open = true" class="btn btn-primary" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                    Approve
                                </button>

                                <!-- Reject Form -->
                                <form action="{{ route('admin.admission.reward.reject', $reward->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.75rem; font-size: 0.75rem;">
                                        Reject
                                    </button>
                                </form>
                            </div>

                            <!-- Approve Modal -->
                            <div x-show="open" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                <div class="bg-white rounded-xl shadow-xl w-96 p-6 relative">
                                    <h2 class="text-lg font-semibold text-slate-900 mb-4">Approve Reward</h2>
                                    <form action="{{ route('admin.admission.reward.approve', $reward->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="mb-5">
                                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Reward Amount</label>
                                            <input type="number" name="reward" x-model="reward"
                                                class="w-full px-3 py-2.5 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                required>
                                        </div>
                                        <div class="flex justify-end gap-3">
                                            <button type="button" @click="open = false" class="btn btn-secondary">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Approve</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">
                            <x-admin.empty-state title="No reward requests" description="There are no admission reward requests yet." />
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($rewards,'links') && $rewards->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $rewards->links() }}</div>
        @endif
    </div>

</div>
@endsection
