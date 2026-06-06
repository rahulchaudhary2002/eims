@extends('layouts.student')

@section('title', 'Counseling Sessions')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Counseling Sessions</h1>
                <p class="text-white/70 text-sm mt-1">Your scheduled and past sessions</p>
            </div>
            <a href="{{ route('student.counseling-sessions.create') }}"
               class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm no-underline shrink-0">
                <i class="fas fa-plus"></i> Book Session
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
        $sc = ['pending' => 'bg-orange-100 text-orange-700', 'scheduled' => 'bg-blue-100 text-blue-700', 'completed' => 'bg-green-100 text-green-700', 'cancelled' => 'bg-gray-100 text-gray-500', 'no_show' => 'bg-red-100 text-red-700', 'rescheduled' => 'bg-yellow-100 text-yellow-700'];
        @endphp

        @forelse($sessions as $session)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
            <div class="flex items-start gap-4 px-5 py-4">
                <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center shrink-0">
                    <i class="fas fa-calendar text-teal-500"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">{{ $session->institution?->name ?? 'General Session' }}</h3>
                            <div class="flex items-center gap-3 mt-0.5 text-xs text-gray-400">
                                <span><i class="fas fa-clock mr-1"></i>{{ $session->scheduled_at->format('M d, Y H:i') }}</span>
                                <span>{{ \App\Models\CounselingSession::MODES[$session->mode] ?? $session->mode }}</span>
                            </div>
                        </div>
                        <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc[$session->status] ?? '' }}">
                            {{ \App\Models\CounselingSession::STATUSES[$session->status] ?? $session->status }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 px-5 py-3 border-t border-gray-50 bg-gray-50/50">
                <a href="{{ route('student.counseling-sessions.show', $session) }}"
                   class="text-xs font-semibold text-[#4299e1] px-3 py-1.5 border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">View</a>
                @if($session->status === 'pending')
                <span class="text-xs text-orange-600 font-medium px-2 py-1">Awaiting approval</span>
                <a href="{{ route('student.counseling-sessions.edit', $session) }}"
                   class="text-xs font-semibold text-gray-600 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition no-underline">Edit Request</a>
                <form method="POST" action="{{ route('student.counseling-sessions.cancel', $session) }}" onsubmit="return confirm('Cancel this request?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs font-semibold text-red-500 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">Cancel</button>
                </form>
                @elseif(in_array($session->status, ['scheduled', 'rescheduled']))
                <a href="{{ route('student.counseling-sessions.edit', $session) }}"
                   class="text-xs font-semibold text-gray-600 px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 transition no-underline">Reschedule</a>
                <form method="POST" action="{{ route('student.counseling-sessions.cancel', $session) }}" onsubmit="return confirm('Cancel this session?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs font-semibold text-red-500 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">Cancel</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-calendar text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No counseling sessions yet</p>
            <a href="{{ route('student.counseling-sessions.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">Book Session</a>
        </div>
        @endforelse

        @if($sessions->hasPages())
        <div>{{ $sessions->links() }}</div>
        @endif
    </div>
</section>

@endsection
