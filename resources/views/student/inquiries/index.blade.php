@extends('layouts.student')

@section('title', 'My Inquiries')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">My Inquiries</h1>
                <p class="text-white/70 text-sm mt-1">Questions and inquiries you've submitted</p>
            </div>
            <a href="{{ route('student.inquiries.create') }}"
               class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm no-underline shrink-0">
                <i class="fas fa-plus"></i> New Inquiry
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

        @php
        $sc = ['new' => 'bg-blue-100 text-blue-700', 'contacted' => 'bg-yellow-100 text-yellow-700', 'qualified' => 'bg-green-100 text-green-700', 'not_qualified' => 'bg-red-100 text-red-700', 'converted' => 'bg-emerald-100 text-emerald-700', 'closed' => 'bg-gray-100 text-gray-500'];
        @endphp

        @forelse($inquiries as $inquiry)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4 px-5 py-4">
                <div class="w-10 h-10 rounded-xl bg-sky-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-comments text-sky-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-gray-800">{{ $inquiry->institution?->name ?? 'General Inquiry' }}</h3>
                            @if($inquiry->institutionProgram)
                            <p class="text-xs text-gray-500 mt-0.5">{{ $inquiry->institutionProgram?->program?->name }}</p>
                            @endif
                        </div>
                        <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc[$inquiry->status] ?? '' }}">
                            {{ \App\Models\Inquiry::STATUSES[$inquiry->status] ?? $inquiry->status }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $inquiry->message }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $inquiry->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="flex justify-end px-5 py-3 border-t border-gray-50 bg-gray-50/50">
                <a href="{{ route('student.inquiries.show', $inquiry) }}"
                   class="text-xs font-semibold text-[#4299e1] px-3 py-1.5 border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">View</a>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-comments text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No inquiries yet</p>
            <a href="{{ route('student.inquiries.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">Submit Inquiry</a>
        </div>
        @endforelse

        @if($inquiries->hasPages())
        <div>{{ $inquiries->links() }}</div>
        @endif
    </div>
</section>

@endsection
