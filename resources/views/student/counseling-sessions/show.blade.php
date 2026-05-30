@extends('layouts.student')

@section('title', 'Session Details')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.counseling-sessions.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">{{ $counselingSession->institution?->name ?? 'Counseling Session' }}</h1>
                <p class="text-white/70 text-sm mt-1"><i class="fas fa-clock mr-1"></i>{{ $counselingSession->scheduled_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="max-w-2xl">
            @php $sc = ['scheduled' => 'bg-blue-100 text-blue-700', 'completed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-gray-100 text-gray-500', 'no_show' => 'bg-red-100 text-red-700', 'rescheduled' => 'bg-yellow-100 text-yellow-700']; @endphp
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-700">Session Details</h2>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc[$counselingSession->status] ?? '' }}">
                        {{ \App\Models\CounselingSession::STATUSES[$counselingSession->status] ?? $counselingSession->status }}
                    </span>
                </div>
                <dl class="divide-y divide-gray-50">
                    @foreach([
                        ['Mode', \App\Models\CounselingSession::MODES[$counselingSession->mode] ?? $counselingSession->mode],
                        ['Counselor', $counselingSession->counselor?->name],
                        ['Scheduled At', $counselingSession->scheduled_at->format('M d, Y H:i')],
                    ] as [$label, $value])
                    @if($value)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-36 shrink-0">{{ $label }}</dt>
                        <dd class="text-sm font-semibold text-gray-700">{{ $value }}</dd>
                    </div>
                    @endif
                    @endforeach
                    @if($counselingSession->student_message)
                    <div class="px-6 py-3">
                        <dt class="text-sm text-gray-500 mb-1">Your Message</dt>
                        <dd class="text-sm text-gray-700">{{ $counselingSession->student_message }}</dd>
                    </div>
                    @endif
                </dl>
                @if(in_array($counselingSession->status, ['scheduled', 'rescheduled']))
                <div class="flex gap-3 justify-end px-6 py-4 border-t border-gray-100">
                    <a href="{{ route('student.counseling-sessions.edit', $counselingSession) }}"
                       class="px-5 py-2 text-sm font-semibold text-gray-600 border border-gray-200 rounded-xl hover:bg-gray-50 no-underline">Reschedule</a>
                    <form method="POST" action="{{ route('student.counseling-sessions.cancel', $counselingSession) }}" onsubmit="return confirm('Cancel this session?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="px-5 py-2 text-sm font-bold text-red-600 border border-red-200 rounded-xl hover:bg-red-50 transition">Cancel Session</button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

@endsection
