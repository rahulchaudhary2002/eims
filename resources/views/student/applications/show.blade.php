@extends('layouts.student')

@section('title', 'Application Details')

@section('content')

@php
$sc = ['draft' => 'bg-gray-100 text-gray-600', 'submitted' => 'bg-blue-100 text-blue-700', 'under_review' => 'bg-yellow-100 text-yellow-700', 'referred' => 'bg-purple-100 text-purple-700', 'admitted' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700', 'withdrawn' => 'bg-gray-100 text-gray-500'];
@endphp

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('student.applications.index') }}" class="text-white/70 hover:text-white no-underline"><i class="fas fa-arrow-left"></i></a>
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">{{ $application->institution?->name }}</h1>
                <p class="text-white/70 text-sm mt-1">{{ $application->applicable_label }} · {{ $application->application_number }}</p>
            </div>
            <span class="ml-auto shrink-0 text-sm font-bold px-3 py-1.5 rounded-full bg-white/20 border border-white/30">
                {{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}
            </span>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-5">
        <div class="max-w-2xl space-y-5">

            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <dl class="divide-y divide-gray-50">
                    @foreach([
                        ['Application No.', $application->application_number],
                        ['Source', \App\Models\Application::SOURCES[$application->source] ?? $application->source],
                        ['Scholarship', $application->scholarship?->title],
                        ['Applied On', $application->created_at->format('M d, Y')],
                        ['Submitted At', $application->submitted_at?->format('M d, Y H:i')],
                        ['Reviewed At', $application->reviewed_at?->format('M d, Y H:i')],
                        ['Admitted At', $application->admitted_at?->format('M d, Y H:i')],
                    ] as [$label, $value])
                    @if($value)
                    <div class="flex px-6 py-3">
                        <dt class="text-sm text-gray-500 w-40 shrink-0">{{ $label }}</dt>
                        <dd class="text-sm font-semibold text-gray-700">{{ $value }}</dd>
                    </div>
                    @endif
                    @endforeach
                    @if($application->student_message)
                    <div class="px-6 py-3">
                        <dt class="text-sm text-gray-500 mb-1">Your Message</dt>
                        <dd class="text-sm text-gray-700">{{ $application->student_message }}</dd>
                    </div>
                    @endif
                </dl>
                @if(in_array($application->status, ['draft', 'submitted']))
                <div class="px-6 py-4 border-t border-gray-100 flex justify-end">
                    <form method="POST" action="{{ route('student.applications.cancel', $application) }}" onsubmit="return confirm('Withdraw this application?')">
                        @csrf @method('PATCH')
                        <button type="submit" class="inline-flex items-center gap-2 text-sm font-semibold text-red-600 border border-red-200 px-5 py-2 rounded-xl hover:bg-red-50 transition">
                            <i class="fas fa-times"></i> Withdraw Application
                        </button>
                    </form>
                </div>
                @endif
            </div>

            @if($application->statusLogs->count())
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-bold text-gray-700">Status History</h3>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @foreach($application->statusLogs->sortByDesc('created_at') as $log)
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-[#4299e1] shrink-0 mt-1.5"></div>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">{{ \App\Models\Application::STATUSES[$log->status] ?? $log->status }}</p>
                            <p class="text-xs text-gray-400">{{ $log->created_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>
</section>

@endsection
