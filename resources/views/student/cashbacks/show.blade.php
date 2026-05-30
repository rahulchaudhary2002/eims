@extends('layouts.student')

@section('title', 'Cashback Details')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.cashbacks.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">NPR {{ number_format($cashback->cashback_amount) }}</h1>
                <p class="text-white/70 text-sm mt-1">{{ $cashback->cashback_percentage }}% cashback</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <dl class="divide-y divide-gray-50">
                    @foreach([
                        ['Institution', $cashback->application?->institution?->name],
                        ['Program', $cashback->application?->institutionProgram?->program?->name],
                        ['Status', \App\Models\ScholarshipCashback::STATUSES[$cashback->status] ?? $cashback->status],
                        ['Payment Method', $cashback->payment_method ? (\App\Models\ScholarshipCashback::PAYMENT_METHODS[$cashback->payment_method] ?? $cashback->payment_method) : null],
                        ['Transaction Ref', $cashback->transaction_reference],
                        ['Paid On', $cashback->paid_at?->format('M d, Y')],
                        ['Commission Amount', $cashback->commission_received_amount ? 'NPR ' . number_format($cashback->commission_received_amount) : null],
                    ] as [$label, $value])
                    @if($value)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-44 shrink-0">{{ $label }}</dt>
                        <dd class="text-sm font-semibold text-gray-700">{{ $value }}</dd>
                    </div>
                    @endif
                    @endforeach
                    @if($cashback->remarks)
                    <div class="px-6 py-3">
                        <dt class="text-sm text-gray-500 mb-1">Remarks</dt>
                        <dd class="text-sm text-gray-700">{{ $cashback->remarks }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>
</section>

@endsection
