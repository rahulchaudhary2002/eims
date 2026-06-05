@extends('layouts.student')

@section('title', 'My Reward Claims')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">My Reward Claims</h1>
                <p class="text-white/70 text-sm mt-1">Track your cashback and reward claims</p>
            </div>
            <a href="{{ route('student.reward-claims.create') }}"
               class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm no-underline shrink-0">
                <i class="fas fa-plus"></i> Claim Reward
            </a>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-4">

        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif

        {{-- CTA Card --}}
        <div class="bg-gradient-to-r from-[#2c5aa0] to-[#4299e1] rounded-xl shadow-md overflow-hidden">
            <div class="flex items-center gap-5 px-6 py-5">
                <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <i class="fas fa-gift text-white text-2xl"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white font-bold text-lg">Got Admitted? Claim Your Reward</h3>
                    <p class="text-white/80 text-sm mt-0.5">Submit proof of your admission and claim your cashback reward from the platform.</p>
                </div>
                <a href="{{ route('student.reward-claims.create') }}"
                   class="shrink-0 inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-50 transition text-sm no-underline">
                    Claim Now
                </a>
            </div>
        </div>

        @php
        $sc = [
            'pending' => 'bg-yellow-100 text-yellow-700',
            'under_review' => 'bg-blue-100 text-blue-700',
            'approved' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700',
            'paid' => 'bg-purple-100 text-purple-700',
        ];
        @endphp

        @forelse($claims as $claim)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4 px-5 py-4">
                <div class="w-12 h-12 rounded-xl bg-[#ebf8ff] flex items-center justify-center shrink-0">
                    <i class="fas fa-gift text-[#2c5aa0] text-lg"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">{{ $claim->institution?->name ?? 'Unknown Institution' }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $claim->applicable_label }}</p>
                        </div>
                        <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc[$claim->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ \App\Models\StudentRewardClaim::STATUSES[$claim->status] ?? $claim->status }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-xs text-gray-400">
                        <span>{{ $claim->claim_number }}</span>
                        @if($claim->admission_date)
                            <span>Admitted: {{ $claim->admission_date->format('M d, Y') }}</span>
                        @endif
                        @if($claim->claimed_reward_amount)
                            <span class="font-semibold text-gray-600">Claimed: {{ number_format((float) $claim->claimed_reward_amount, 2) }}</span>
                        @endif
                        @if($claim->approved_reward_amount)
                            <span class="font-semibold text-green-600">Approved: {{ number_format((float) $claim->approved_reward_amount, 2) }}</span>
                        @endif
                        <span>{{ $claim->submitted_at?->diffForHumans() ?? $claim->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 px-5 py-3 border-t border-gray-50 bg-gray-50/50">
                <a href="{{ route('student.reward-claims.show', $claim) }}"
                   class="text-xs font-semibold text-[#4299e1] px-3 py-1.5 border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">View Details</a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-gift text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No reward claims yet</p>
            <p class="text-gray-400 text-sm mt-1">Got admitted? Submit your proof and claim your cashback reward.</p>
            <a href="{{ route('student.reward-claims.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">
                <i class="fas fa-plus"></i> Claim Your Reward
            </a>
        </div>
        @endforelse

        @if($claims->hasPages())
        <div>{{ $claims->links() }}</div>
        @endif

    </div>
</section>

@endsection
