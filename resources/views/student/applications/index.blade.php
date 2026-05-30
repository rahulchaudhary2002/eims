@extends('layouts.student')

@section('title', 'My Applications')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">My Applications</h1>
                <p class="text-white/70 text-sm mt-1">Track all your institution applications</p>
            </div>
            <a href="{{ route('student.applications.create') }}"
               class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-bold px-5 py-2.5 rounded-xl hover:bg-gray-100 transition text-sm no-underline shrink-0">
                <i class="fas fa-plus"></i> New Application
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
        $sc = ['draft' => 'bg-gray-100 text-gray-600', 'submitted' => 'bg-blue-100 text-blue-700', 'under_review' => 'bg-yellow-100 text-yellow-700', 'referred' => 'bg-purple-100 text-purple-700', 'admitted' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', 'withdrawn' => 'bg-gray-100 text-gray-500'];
        @endphp

        @forelse($applications as $app)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
            <div class="flex items-start gap-4 px-5 py-4">
                @if($app->institution?->logo)
                    <img src="{{ Storage::url($app->institution->logo) }}" class="w-12 h-12 rounded-xl object-cover shrink-0">
                @else
                    <div class="w-12 h-12 rounded-xl bg-[#ebf8ff] flex items-center justify-center shrink-0">
                        <span class="text-[#2c5aa0] text-lg font-bold">{{ strtoupper(substr($app->institution?->name ?? 'I', 0, 1)) }}</span>
                    </div>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div>
                            <h3 class="text-sm font-bold text-gray-800">{{ $app->institution?->name ?? 'Unknown Institution' }}</h3>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $app->institutionProgram?->program?->name ?? '—' }}</p>
                        </div>
                        <span class="shrink-0 text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc[$app->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ \App\Models\Application::STATUSES[$app->status] ?? $app->status }}
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-xs text-gray-400">
                        <span>{{ $app->application_number }}</span>
                        <span>{{ $app->created_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 px-5 py-3 border-t border-gray-50 bg-gray-50/50">
                <a href="{{ route('student.applications.show', $app) }}"
                   class="text-xs font-semibold text-[#4299e1] px-3 py-1.5 border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">View Details</a>
                @if(in_array($app->status, ['draft', 'submitted']))
                <form method="POST" action="{{ route('student.applications.cancel', $app) }}" onsubmit="return confirm('Withdraw this application?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="text-xs font-semibold text-red-500 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">Withdraw</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-file-alt text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No applications yet</p>
            <p class="text-gray-400 text-sm mt-1">Start your journey by applying to institutions and programs</p>
            <a href="{{ route('student.applications.create') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">Browse Programs</a>
        </div>
        @endforelse

        @if($applications->hasPages())
        <div>{{ $applications->links() }}</div>
        @endif

    </div>
</section>

@endsection
