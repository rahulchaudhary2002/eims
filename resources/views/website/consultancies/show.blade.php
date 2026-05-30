@extends('website.layouts.app')

@section('meta-title', $institution->name . ' - Consultancy - ' . config('app.name'))
@section('meta-description', $institution->short_description ?? 'Learn about ' . $institution->name . ' consultancy services.')

@section('content')
@php
    $logo = $institution->logo && Storage::disk('public')->exists($institution->logo)
        ? Storage::url($institution->logo)
        : null;
    $cover = $institution->cover_image && Storage::disk('public')->exists($institution->cover_image)
        ? Storage::url($institution->cover_image)
        : null;
@endphp

<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Consultancies', 'url' => route('website.consultancies.index')],
                ['label' => $institution->name],
            ],
        ])

        <div class="grid lg:grid-cols-[minmax(0,1fr)_360px] gap-10 items-center mt-12">
            <div>
                <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                    <i class="fas fa-handshake text-[#4299e1]"></i>
                    Consultancy
                </span>
                <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-4">{{ $institution->name }}</h1>

                @if ($institution->city || $institution->province)
                    <p class="flex items-center gap-2 text-white/80 text-[1rem] mb-4">
                        <i class="fas fa-map-marker-alt text-[#4299e1]"></i>
                        {{ collect([$institution->city, $institution->district, $institution->province])->filter()->implode(', ') }}
                    </p>
                @endif

                @if ($institution->reviews_avg_rating)
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex text-yellow-400">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= round($institution->reviews_avg_rating) ? '' : 'opacity-30' }}"></i>
                            @endfor
                        </div>
                        <span class="text-white/80 text-sm">{{ number_format($institution->reviews_avg_rating, 1) }} rating</span>
                    </div>
                @endif

                @if ($institution->short_description || $institution->description)
                    <p class="text-[1.05rem] text-white/85 leading-relaxed max-w-2xl">
                        {{ $institution->short_description ?: Str::limit($institution->description, 220) }}
                    </p>
                @endif
            </div>

            <div class="bg-white/10 border border-white/20 rounded-xl overflow-hidden shadow-[0_15px_40px_rgba(0,0,0,0.18)]">
                @if ($cover)
                    <div class="h-44 overflow-hidden">
                        <img src="{{ $cover }}" alt="{{ $institution->name }}" class="w-full h-full object-cover">
                    </div>
                @endif
                <div class="p-5 flex items-center gap-4 {{ $cover ? '' : 'pt-8 pb-8 justify-center' }}">
                    <div class="h-16 w-16 rounded-xl bg-white border border-white/20 flex items-center justify-center overflow-hidden flex-shrink-0">
                        @if ($logo)
                            <img src="{{ $logo }}" alt="{{ $institution->name }}" class="h-full w-full object-contain p-2">
                        @else
                            <i class="fas fa-handshake text-[#2c5aa0] text-2xl"></i>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-white/70 mb-1">Consultancy Office</p>
                        <p class="font-bold text-lg">{{ $institution->name }}</p>
                        @if ($institution->consultancyServices->isNotEmpty())
                            <p class="text-sm text-white/80 mt-1">{{ $institution->consultancyServices->count() }} service{{ $institution->consultancyServices->count() !== 1 ? 's' : '' }} available</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
            <div class="space-y-8">

                {{-- Services --}}
                @if ($institution->consultancyServices->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Services Offered</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach ($institution->consultancyServices as $service)
                                <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                    <span class="inline-block text-xs bg-[#4299e1]/10 text-[#2c5aa0] font-semibold px-2.5 py-1 rounded-full mb-3">
                                        {{ \App\Models\ConsultancyService::SERVICE_TYPES[$service->service_type] ?? $service->service_type }}
                                    </span>
                                    <h3 class="font-bold text-gray-900 mb-2">{{ $service->title }}</h3>
                                    @if ($service->description)
                                        <p class="text-gray-500 text-sm leading-relaxed">{{ Str::limit($service->description, 100) }}</p>
                                    @endif
                                    @if ($service->service_fee)
                                        <p class="text-[#2c5aa0] font-bold mt-3">NPR {{ number_format($service->service_fee) }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Destinations --}}
                @if ($institution->consultancyDestinations->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Study Destinations</h2>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            @foreach ($institution->consultancyDestinations as $dest)
                                <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#4299e1]/10 border border-[#4299e1]/20 text-[#2c5aa0] font-semibold rounded-xl">
                                    <i class="fas fa-globe text-[#4299e1]"></i>
                                    {{ $dest->country }}
                                    @if ($dest->city)
                                        <span class="text-[#2c5aa0]/60">·</span> {{ $dest->city }}
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Posts --}}
                @if ($institution->posts->isNotEmpty())
                    <div>
                        <div class="mb-6">
                            <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Latest Updates</h2>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            @foreach ($institution->posts as $post)
                                @include('website.partials.post-card', ['post' => $post])
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Reviews --}}
                @if ($institution->reviews->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="relative inline-block text-[2rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Student Reviews</h2>
                        </div>
                        <div class="space-y-4">
                            @foreach ($institution->reviews as $review)
                                <div class="rounded-xl bg-[#f7fafc] border border-gray-200 p-5">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="font-semibold text-gray-900">{{ $review->student?->name ?? 'Student' }}</span>
                                        <div class="flex text-yellow-400 text-sm gap-0.5">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'opacity-25' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-gray-600 text-sm leading-relaxed">{{ $review->review }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-3">Get in Touch</h3>
                    <p class="text-sm text-gray-600 leading-relaxed mb-5">Send an inquiry to learn more about services, fees, and how this consultancy can help you.</p>
                    <a href="{{ route('website.inquiry.create', ['institution' => $institution->slug]) }}"
                       class="w-full flex items-center justify-center gap-2 px-5 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition no-underline">
                        <i class="fas fa-paper-plane"></i> Send Inquiry
                    </a>
                </div>

                @if ($institution->phone || $institution->email || $institution->website)
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                        <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">Contact Info</h3>
                        <div class="space-y-4">
                            @if ($institution->phone)
                                <div class="flex items-center gap-3 text-gray-700">
                                    <span class="h-10 w-10 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <span class="text-sm font-medium">{{ $institution->phone }}</span>
                                </div>
                            @endif
                            @if ($institution->email)
                                <div class="flex items-center gap-3 text-gray-700">
                                    <span class="h-10 w-10 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <span class="text-sm font-medium break-all">{{ $institution->email }}</span>
                                </div>
                            @endif
                            @if ($institution->website)
                                <div class="flex items-center gap-3">
                                    <span class="h-10 w-10 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-globe"></i>
                                    </span>
                                    <a href="{{ $institution->website }}" target="_blank" rel="noopener"
                                       class="text-sm font-medium text-[#4299e1] hover:text-[#2c5aa0] transition no-underline break-all">
                                        Visit Website
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-xl font-bold mb-3">Study Abroad?</h3>
                    <p class="text-sm text-white/85 leading-relaxed mb-5">Explore all consultancies and find the right guidance for your abroad journey.</p>
                    <a href="{{ route('website.consultancies.index') }}"
                       class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-semibold px-5 py-3 rounded-xl hover:bg-gray-100 transition no-underline">
                        <i class="fas fa-arrow-left"></i> All Consultancies
                    </a>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
