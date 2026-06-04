@extends('layouts.student')

@section('title', 'Cashbacks')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Cashbacks</h1>
                <p class="text-white/70 text-sm mt-1">Your scholarship cashback payments</p>
            </div>
            @if($totalPaid > 0)
            <div class="bg-white/15 border border-white/20 rounded-xl px-5 py-3 shrink-0">
                <p class="text-xs text-white/70">Total Received</p>
                <p class="text-lg font-bold">NPR {{ number_format($totalPaid) }}</p>
            </div>
            @endif
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-4">

        @php
        $sc = ['pending' => 'bg-yellow-100 text-yellow-700', 'processing' => 'bg-blue-100 text-blue-700', 'paid' => 'bg-green-100 text-green-700', 'failed' => 'bg-red-100 text-red-700', 'cancelled' => 'bg-gray-100 text-gray-500'];
        @endphp

        @forelse($cashbacks as $cb)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="flex items-start gap-4 px-5 py-4">
                <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-coins text-green-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">NPR {{ number_format($cb->cashback_amount) }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $cb->cashback_percentage }}% cashback · {{ $cb->application?->institution?->name }}</p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc[$cb->status] ?? '' }}">
                            {{ \App\Models\ScholarshipCashback::STATUSES[$cb->status] ?? $cb->status }}
                        </span>
                    </div>
                    @if($cb->paid_at)
                    <p class="text-xs text-gray-400 mt-1">Paid {{ $cb->paid_at->format('M d, Y') }} via {{ \App\Models\ScholarshipCashback::PAYMENT_METHODS[$cb->payment_method] ?? $cb->payment_method }}</p>
                    @endif
                </div>
            </div>
            <div class="flex justify-end px-5 py-3 border-t border-gray-50 bg-gray-50/50">
                <a href="{{ route('student.cashbacks.show', $cb) }}"
                   class="text-xs font-semibold text-[#4299e1] px-3 py-1.5 border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">View Details</a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-coins text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No cashbacks yet</p>
            <p class="text-gray-400 text-sm mt-1">Cashbacks are issued once your scholarship application is processed</p>
        </div>
        @endforelse

        @if($cashbacks->hasPages())
        <div>{{ $cashbacks->links() }}</div>
        @endif
    </div>
</section>

@endsection
