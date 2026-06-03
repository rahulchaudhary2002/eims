@extends('institution.layouts.app')
@section('title', 'Referred Applications')
@section('page-title', 'Referred Applications')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Referred Applications"
        :subtitle="$activeInstitution->name"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'institution.dashboard'],
            ['label'=>'Referred Applications'],
        ]">
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('institution.referrals.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3 items-end">
            <div>
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    @foreach(\App\Models\ApplicationReferral::STATUSES as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('institution.referrals.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
            <h2 class="text-base font-semibold text-slate-800">Referred Applications</h2>
            <span class="text-sm text-slate-500">{{ $referrals->total() }} total</span>
        </div>
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Referral Number</th>
                        <th>Student</th>
                        <th>Program</th>
                        <th>Referred At</th>
                        <th>Status</th>
                        <th>Profile</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($referrals as $referral)
                        <tr>
                            <td>
                                <a href="{{ route('institution.referrals.show', $referral) }}" class="font-semibold text-blue-600 hover:underline">{{ $referral->referral_number }}</a>
                            </td>
                            <td>
                                {{-- Masked student name for institution --}}
                                @php
                                    $parts = explode(' ', $referral->student->name ?? '');
                                    $maskedName = (substr($parts[0] ?? '', 0, 1) . '***') . (isset($parts[1]) ? ' ' . substr($parts[1], 0, 1) : '');
                                @endphp
                                <div class="font-medium text-slate-800">
                                    @if($referral->is_profile_unlocked)
                                        {{ $referral->student->name ?? '-' }}
                                    @else
                                        {{ $maskedName }}
                                    @endif
                                </div>
                            </td>
                            <td class="text-sm text-slate-600">{{ $referral->institutionProgram?->title ?: ($referral->institutionProgram?->program?->name ?? '-') }}</td>
                            <td class="text-xs text-slate-500">{{ $referral->referred_at?->format('d M Y') ?? '-' }}</td>
                            <td><span class="badge">{{ \App\Models\ApplicationReferral::STATUSES[$referral->status] ?? $referral->status }}</span></td>
                            <td>
                                @if($referral->is_profile_unlocked)
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700 bg-green-50 border border-green-200 rounded-full px-2.5 py-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5V6.75a4.5 4.5 0 119 0v3.75M3.75 21.75h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H3.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                        Unlocked
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-xs font-medium text-slate-500 bg-slate-50 border border-slate-200 rounded-full px-2.5 py-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/></svg>
                                        Locked
                                    </span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('institution.referrals.show', $referral) }}" class="btn btn-secondary btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <x-admin.empty-state
                                    title="No referred applications"
                                    description="Applications referred to your institution will appear here." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($referrals->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $referrals->links() }}</div>
        @endif
    </div>
</div>
@endsection
