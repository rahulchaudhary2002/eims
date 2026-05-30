@extends('layouts.student')

@section('title', 'Inquiry Details')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.inquiries.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">{{ $inquiry->institution?->name ?? 'General Inquiry' }}</h1>
                <p class="text-white/70 text-sm mt-1">Submitted {{ $inquiry->created_at->diffForHumans() }}</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl">
            @php $sc = ['new' => 'bg-blue-100 text-blue-700', 'contacted' => 'bg-yellow-100 text-yellow-700', 'qualified' => 'bg-green-100 text-green-700', 'not_qualified' => 'bg-red-100 text-red-700', 'converted' => 'bg-emerald-100 text-emerald-700', 'closed' => 'bg-gray-100 text-gray-500']; @endphp
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-700">Inquiry Details</h2>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc[$inquiry->status] ?? '' }}">
                        {{ \App\Models\Inquiry::STATUSES[$inquiry->status] ?? $inquiry->status }}
                    </span>
                </div>
                <dl class="divide-y divide-gray-50">
                    @foreach([
                        ['Name', $inquiry->name],
                        ['Email', $inquiry->email],
                        ['Phone', $inquiry->phone],
                        ['Source', \App\Models\Inquiry::SOURCES[$inquiry->source] ?? $inquiry->source],
                        ['Submitted', $inquiry->created_at->format('M d, Y H:i')],
                    ] as [$label, $value])
                    @if($value)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-36 shrink-0">{{ $label }}</dt>
                        <dd class="text-sm font-semibold text-gray-700">{{ $value }}</dd>
                    </div>
                    @endif
                    @endforeach
                    <div class="px-6 py-3">
                        <dt class="text-sm text-gray-500 mb-1">Message</dt>
                        <dd class="text-sm text-gray-700 leading-relaxed">{{ $inquiry->message }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</section>

@endsection
