@extends('layouts.app')

@section('title', $program->name . ' - Sikuna.com')

@section('content')
<section class="pt-[120px] pb-[30px] bg-[#f7fafc]/80">
    <div class="max-w-7xl mx-auto px-5">
        <div class="flex items-center gap-2 text-[#718096] text-[0.95rem]">
            <a href="{{ route('home') }}" class="text-[#4299e1] hover:underline">Home</a>
            <span class="text-[#718096] px-1">/</span>
            <a href="{{ route('program.index') }}" class="text-[#4299e1] hover:underline">Programs</a>
            <span class="text-[#718096] px-1">/</span>
            <span>{{ $program->name }}</span>
        </div>
    </div>
</section>

<section class="py-10 bg-white">
    <div class="max-w-7xl mx-auto px-5 grid grid-cols-1 lg:grid-cols-12 gap-8">
        <main class="lg:col-span-8 space-y-6">
            <div class="bg-white border border-gray-200 rounded-xl p-7 shadow-sm">
                <span class="inline-block px-3 py-1 bg-[#4299e1]/10 text-[#4299e1] rounded-full text-xs font-semibold mb-3">{{ $program->category?->name ?? 'Uncategorized' }}</span>
                <h1 class="text-4xl max-sm:text-3xl font-bold text-[#2c5aa0] mb-3">{{ $program->name }}</h1>
                <div class="flex flex-wrap gap-4 text-gray-600 text-sm">
                    <span><i class="fas fa-graduation-cap"></i> {{ $program->level?->name ?? '-' }}</span>
                    <span><i class="fas fa-clock"></i> {{ $program->duration ?? '-' }}</span>
                    <span><i class="fas fa-university"></i> {{ $program->affiliation?->name ?? '-' }}</span>
                    <span><i class="fas fa-book"></i> {{ $program->courses->count() }} Courses</span>
                </div>
                <div class="mt-4 text-2xl font-bold text-[#2c5aa0] whitespace-nowrap">NPR {{ number_format($program->fee) }}<small class="text-sm text-gray-600 font-normal">/total</small></div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-7 shadow-sm">
                <h2 class="text-xl font-bold text-[#2c5aa0] mb-3">About This Program</h2>
                <div class="text-gray-700 leading-relaxed prose max-w-none">
                    {!! $program->description ?: '<p>No description available.</p>' !!}
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl p-7 shadow-sm">
                <h2 class="text-xl font-bold text-[#2c5aa0] mb-4">Courses In This Program</h2>
                <div class="space-y-3">
                    @forelse($program->courses as $course)
                    <a href="{{ route('course.show', $course) }}" class="block p-4 rounded-lg border border-gray-200 hover:border-[#4299e1] hover:bg-[#4299e1]/5 transition">
                        <div class="font-semibold text-gray-900">{{ $course->name }}</div>
                        <div class="text-sm text-gray-500">{{ $course->code }}</div>
                    </a>
                    @empty
                    <p class="text-gray-500">No active courses mapped to this program yet.</p>
                    @endforelse
                </div>
            </div>
        </main>

        <aside class="lg:col-span-4 space-y-6">
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-bold text-[#2c5aa0] mb-4">Related Programs</h3>
                <div class="space-y-3">
                    @forelse($relatedPrograms as $related)
                    <a href="{{ route('program.show', $related->slug) }}" class="block p-3 rounded-lg border border-gray-200 hover:border-[#4299e1] hover:bg-[#4299e1]/5 transition">
                        <div class="font-semibold text-gray-900">{{ $related->name }}</div>
                        <div class="text-sm text-gray-500">{{ $related->level?->name ?? '-' }}</div>
                    </a>
                    @empty
                    <p class="text-sm text-gray-500">No related programs available.</p>
                    @endforelse
                </div>
            </div>

            <a href="{{ route('program.index') }}" class="block text-center px-4 py-3 rounded-lg border-2 border-[#4299e1] text-[#4299e1] font-semibold hover:bg-[#4299e1]/10 transition">View All Programs</a>
        </aside>
    </div>
</section>
@endsection
