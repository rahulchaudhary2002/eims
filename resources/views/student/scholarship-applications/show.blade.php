@extends('layouts.student')

@section('title', 'Scholarship Application')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.scholarship-applications.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">{{ $scholarshipApplication->scholarship?->title }}</h1>
                @if($scholarshipApplication->application)
                <p class="text-white/70 text-sm mt-1">via {{ $scholarshipApplication->application->institution?->name }}</p>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl">
            @php $sc = ['pending' => 'bg-gray-100 text-gray-600', 'under_review' => 'bg-yellow-100 text-yellow-700', 'approved' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', 'withdrawn' => 'bg-gray-100 text-gray-500']; @endphp
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-700">Application Details</h2>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc[$scholarshipApplication->status] ?? '' }}">
                        {{ \App\Models\ScholarshipApplication::STATUSES[$scholarshipApplication->status] ?? $scholarshipApplication->status }}
                    </span>
                </div>
                <dl class="divide-y divide-gray-50">
                    @if($scholarshipApplication->approved_amount)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-44 shrink-0">Approved Amount</dt>
                        <dd class="text-sm font-bold text-green-600">NPR {{ number_format($scholarshipApplication->approved_amount) }}</dd>
                    </div>
                    @endif
                    @if($scholarshipApplication->remarks)
                    <div class="px-6 py-3">
                        <dt class="text-sm text-gray-500 mb-1">Your Remarks</dt>
                        <dd class="text-sm text-gray-700">{{ $scholarshipApplication->remarks }}</dd>
                    </div>
                    @endif
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-44 shrink-0">Applied On</dt>
                        <dd class="text-sm font-semibold text-gray-700">{{ $scholarshipApplication->created_at->format('M d, Y') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</section>

@endsection
