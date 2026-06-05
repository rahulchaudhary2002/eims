@extends('layouts.student')

@section('title', 'Reward Claim Details')

@section('content')

@php
$sc = [
    'pending' => 'bg-yellow-100 text-yellow-700',
    'under_review' => 'bg-blue-100 text-blue-700',
    'approved' => 'bg-green-100 text-green-700',
    'rejected' => 'bg-red-100 text-red-700',
    'paid' => 'bg-purple-100 text-purple-700',
];
@endphp

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.reward-claims.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">{{ $rewardClaim->institution?->name ?? 'Reward Claim' }}</h1>
                <p class="text-white/70 text-sm mt-1">{{ $rewardClaim->claim_number }}</p>
            </div>
            <span class="ml-auto shrink-0 text-sm font-bold px-3 py-1.5 rounded-full bg-white/20 border border-white/30">
                {{ \App\Models\StudentRewardClaim::STATUSES[$rewardClaim->status] ?? $rewardClaim->status }}
            </span>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-5">
        <div class="max-w-2xl space-y-5">

            {{-- Rejection Notice --}}
            @if($rewardClaim->status === 'rejected' && $rewardClaim->rejection_reason)
            <div class="bg-red-50 border border-red-200 rounded-xl px-6 py-4 flex items-start gap-3">
                <i class="fas fa-times-circle text-red-500 mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-red-700">Claim Rejected</p>
                    <p class="text-sm text-red-600 mt-1">{{ $rewardClaim->rejection_reason }}</p>
                </div>
            </div>
            @endif

            {{-- Admin Note --}}
            @if($rewardClaim->admin_note)
            <div class="bg-blue-50 border border-blue-200 rounded-xl px-6 py-4 flex items-start gap-3">
                <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
                <div>
                    <p class="text-sm font-bold text-blue-700">Note from Platform</p>
                    <p class="text-sm text-blue-600 mt-1">{{ $rewardClaim->admin_note }}</p>
                </div>
            </div>
            @endif

            {{-- Claim Info Card --}}
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700">Claim Details</h3>
                </div>
                <dl class="divide-y divide-gray-50">
                    @foreach([
                        ['Claim Number', $rewardClaim->claim_number],
                        ['Institution', $rewardClaim->institution?->name],
                        ['Applied For', $rewardClaim->applicable_label ?: null],
                        ['Admission Date', $rewardClaim->admission_date?->format('M d, Y')],
                        ['Admission Number', $rewardClaim->admission_number],
                        ['Intake', $rewardClaim->intake],
                    ] as [$label, $value])
                    @if($value)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-44 shrink-0">{{ $label }}</dt>
                        <dd class="text-sm font-semibold text-gray-700">{{ $value }}</dd>
                    </div>
                    @endif
                    @endforeach

                    @if($rewardClaim->claimed_reward_amount !== null)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-44 shrink-0">Claimed Amount</dt>
                        <dd class="text-sm font-semibold text-gray-700 font-mono">{{ number_format((float) $rewardClaim->claimed_reward_amount, 2) }}</dd>
                    </div>
                    @endif

                    @if($rewardClaim->approved_reward_amount !== null)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-44 shrink-0">Approved Amount</dt>
                        <dd class="text-sm font-bold text-green-700 font-mono">{{ number_format((float) $rewardClaim->approved_reward_amount, 2) }}</dd>
                    </div>
                    @endif

                    @if($rewardClaim->payment_method)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-44 shrink-0">Payment Method</dt>
                        <dd class="text-sm font-semibold text-gray-700">{{ \App\Models\StudentRewardClaim::PAYMENT_METHODS[$rewardClaim->payment_method] ?? $rewardClaim->payment_method }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Documents Card --}}
            @if($rewardClaim->documents->count())
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700">Documents</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @foreach($rewardClaim->documents as $document)
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center shrink-0">
                                <i class="fas fa-file text-blue-500 text-xs"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate">{{ \App\Models\StudentRewardClaim::DOCUMENT_TYPES[$document->document_type] ?? $document->document_type }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $document->original_name }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            @if($document->is_verified)
                                <span class="text-xs font-semibold text-green-700 bg-green-50 border border-green-200 rounded-full px-2 py-0.5">
                                    <i class="fas fa-check mr-1"></i>Verified
                                </span>
                            @else
                                <span class="text-xs font-medium text-gray-500 bg-gray-50 border border-gray-200 rounded-full px-2 py-0.5">Pending</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Status Timeline --}}
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700">Status Timeline</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @foreach([
                        ['fas fa-paper-plane', 'Submitted', $rewardClaim->submitted_at, 'text-blue-500'],
                        ['fas fa-search', 'Under Review', $rewardClaim->verified_at, 'text-yellow-500'],
                        ['fas fa-check-circle', 'Approved', $rewardClaim->approved_at, 'text-green-500'],
                        ['fas fa-money-bill-wave', 'Paid', $rewardClaim->paid_at, 'text-purple-500'],
                    ] as [$icon, $label, $timestamp, $color])
                    @if($timestamp)
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-50 border border-gray-100 flex items-center justify-center shrink-0">
                            <i class="{{ $icon }} text-xs {{ $color }}"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ $label }}</p>
                            <p class="text-xs text-gray-400">{{ $timestamp->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endif
                    @endforeach

                    @if(!$rewardClaim->submitted_at && !$rewardClaim->verified_at && !$rewardClaim->approved_at && !$rewardClaim->paid_at)
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 rounded-full bg-[#4299e1] shrink-0 mt-1.5"></div>
                            <div>
                                <p class="text-sm font-semibold text-gray-700">Claim Submitted</p>
                                <p class="text-xs text-gray-400">{{ $rewardClaim->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Payment History --}}
            @if($rewardClaim->payments->count())
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700">Payment History</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @foreach($rewardClaim->payments as $payment)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-gray-700 font-mono">{{ number_format((float) $payment->amount, 2) }}</p>
                            <p class="text-xs text-gray-400">{{ $payment->paid_at?->format('M d, Y') ?? '-' }}{{ $payment->transaction_reference ? ' · ' . $payment->transaction_reference : '' }}</p>
                        </div>
                        <span class="text-xs text-gray-500">{{ $payment->payment_method ?? '-' }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</section>

@endsection
