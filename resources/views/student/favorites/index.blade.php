@extends('layouts.student')

@section('title', 'Favorite Institutions')

@section('content')

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-20 text-white">
    <div class="container max-w-7xl mx-auto px-4 mt-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">Favorite Institutions</h1>
                <p class="text-white/70 text-sm mt-1">Institutions you've saved for later</p>
            </div>
            <a href="{{ route('website.institutions.index') }}"
               class="inline-flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white font-semibold px-5 py-2.5 rounded-xl transition text-sm no-underline shrink-0">
                Browse Institutions
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

        @forelse($favorites as $fav)
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 flex items-center justify-between px-5 py-4 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4 min-w-0">
                @if($fav->institution?->logo)
                    <img src="{{ Storage::url($fav->institution->logo) }}" class="w-12 h-12 rounded-xl object-cover shrink-0">
                @else
                    <div class="w-12 h-12 rounded-xl bg-[#ebf8ff] flex items-center justify-center shrink-0">
                        <span class="text-[#2c5aa0] text-lg font-bold">{{ strtoupper(substr($fav->institution?->name ?? 'I', 0, 1)) }}</span>
                    </div>
                @endif
                <div class="min-w-0">
                    <h3 class="text-sm font-bold text-gray-700 truncate">{{ $fav->institution?->name }}</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ \App\Models\Institution::TYPES[$fav->institution?->type] ?? $fav->institution?->type }}@if($fav->institution?->city) · {{ $fav->institution->city }}@endif</p>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0 ml-3">
                @if($fav->institution?->slug)
                <a href="{{ route('website.institutions.show', $fav->institution->slug) }}"
                   class="text-xs text-[#4299e1] px-3 py-1.5 border border-[#bee3f8] rounded-lg hover:bg-[#ebf8ff] transition no-underline">View</a>
                @endif
                <form method="POST" action="{{ route('student.favorites.destroy', $fav) }}" onsubmit="return confirm('Remove from favorites?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 px-3 py-1.5 border border-red-200 rounded-lg hover:bg-red-50 transition">Remove</button>
                </form>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 px-6 py-16 text-center">
            <i class="fas fa-heart text-5xl text-gray-200 mb-4 block"></i>
            <p class="text-gray-500 font-semibold">No favorites yet</p>
            <p class="text-gray-400 text-sm mt-1">Browse institutions and save the ones you like</p>
            <a href="{{ route('website.institutions.index') }}"
               class="mt-4 inline-flex items-center gap-2 bg-[#2c5aa0] text-white text-sm font-bold px-5 py-2.5 rounded-xl hover:bg-[#1a365d] transition no-underline">Browse Institutions</a>
        </div>
        @endforelse

        @if($favorites->hasPages())
        <div>{{ $favorites->links() }}</div>
        @endif
    </div>
</section>

@endsection
