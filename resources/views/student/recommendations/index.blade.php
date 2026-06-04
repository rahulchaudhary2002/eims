@extends('layouts.student')

@section('title', 'Recommendations')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <h1 class="text-2xl md:text-3xl font-bold">Recommendations</h1>
        <p class="text-white/70 text-sm mt-1">Programs recommended based on your profile and preferences</p>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @forelse($recommendations as $rec)
            <a href="{{ route('student.recommendations.show', $rec) }}"
               class="block bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border {{ !$rec->is_viewed ? 'border-[#bee3f8]' : 'border-gray-200' }} overflow-hidden hover:shadow-md transition-all no-underline group">
                <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-50">
                    @if($rec->institution?->logo)
                        <img src="{{ Storage::url($rec->institution->logo) }}" class="w-10 h-10 rounded-lg object-cover shrink-0">
                    @else
                        <div class="w-10 h-10 rounded-lg bg-yellow-50 flex items-center justify-center shrink-0">
                            <span class="text-yellow-600 font-bold">{{ strtoupper(substr($rec->institution?->name ?? 'I', 0, 1)) }}</span>
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-800 truncate group-hover:text-[#2c5aa0] transition-colors">{{ $rec->institution?->name }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ $rec->institutionProgram?->program?->name }}</p>
                    </div>
                    @if(!$rec->is_viewed)
                        <span class="w-2 h-2 rounded-full bg-[#4299e1] shrink-0"></span>
                    @endif
                </div>
                <div class="px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-yellow-50 border-2 border-yellow-200 flex items-center justify-center">
                            <span class="text-xs font-bold text-yellow-600">{{ number_format($rec->score, 0) }}%</span>
                        </div>
                        <span class="text-xs text-gray-500">Match score</span>
                    </div>
                    @if($rec->reasons && count($rec->reasons))
                    <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ count($rec->reasons) }} reasons</span>
                    @endif
                </div>
            </a>
            @empty
            <div class="col-span-full bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
                <i class="fas fa-star text-5xl text-gray-200 mb-4 block"></i>
                <p class="text-gray-500 font-semibold">No recommendations yet</p>
                <p class="text-gray-400 text-sm mt-1">Complete your profile to receive personalized recommendations</p>
                <a href="{{ route('student.profile.index') }}"
                   class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">Complete Profile</a>
            </div>
            @endforelse
        </div>

        @if($recommendations->hasPages())
        <div class="mt-6">{{ $recommendations->links() }}</div>
        @endif
    </div>
</section>

@endsection
