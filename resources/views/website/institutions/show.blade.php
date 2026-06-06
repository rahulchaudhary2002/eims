@extends('website.layouts.app')

@section('meta-title', $institution->name . ' - ' . config('app.name'))
@section('meta-description', $institution->short_description ?? 'Learn about ' . $institution->name)
@if(storage_exists($institution->logo))
    @section('og-image', storage_url($institution->logo))
@endif

@section('content')
@php
    $coverImage = $institution->cover_image && Storage::disk('public')->exists(storage_exists($institution->cover_image))
        ? storage_url($institution->cover_image)
        : asset('assets/images/logo.png');

    $logoImage = $institution->logo && Storage::disk('public')->exists(storage_exists($institution->logo))
        ? storage_url($institution->logo)
        : null;

    $location = collect([$institution->city, $institution->district, $institution->province])->filter()->implode(', ');
    $rating = $institution->reviews_avg_rating ? number_format($institution->reviews_avg_rating, 1) : null;
@endphp

<section class="relative bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white overflow-hidden">
    <img src="{{ $coverImage }}" alt="{{ $institution->name }}" class="absolute inset-0 h-full w-full object-cover opacity-20">
    <div class="absolute inset-0 bg-gradient-to-br from-[#2c5aa0]/95 to-[#1a365d]/95"></div>

    <div class="container max-w-7xl mx-auto px-4 relative z-10">
        @php
            $isCollegeRoute = $routePrefix === 'website.colleges';
            $listingLabel = $isCollegeRoute ? 'Colleges' : 'Institutions';
            $listingRoute = $isCollegeRoute ? route('website.colleges.index') : route('website.institutions.index');
        @endphp
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => $listingLabel, 'url' => $listingRoute],
                ['label' => $institution->name],
            ],
        ])

        <div class="grid lg:grid-cols-[minmax(0,1fr)_360px] gap-10 items-center mt-12">
            <div>
                <div class="flex flex-wrap gap-2 mb-5">
                    <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold capitalize">
                        <i class="fas fa-university text-[#4299e1]"></i>
                        {{ \App\Models\Institution::TYPES[$institution->type] ?? $institution->type }}
                    </span>
                    @if ($institution->is_verified)
                        <span class="inline-flex items-center gap-2 bg-green-500/20 border border-green-300/30 rounded-full px-4 py-2 text-sm font-semibold">
                            <i class="fas fa-check-circle"></i>
                            Verified
                        </span>
                    @endif
                    @if ($rating)
                        <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold">
                            <i class="fas fa-star text-yellow-300"></i>
                            {{ $rating }} Rating
                        </span>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-5 items-start">
                    <div class="h-24 w-24 rounded-xl bg-white shadow-[0_5px_15px_rgba(0,0,0,0.15)] border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                        @if($logoImage)
                            <img src="{{ $logoImage }}" alt="{{ $institution->name }} logo" class="h-full w-full object-contain p-2">
                        @else
                            <i class="fas fa-university text-4xl text-[#2c5aa0]"></i>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-4">{{ $institution->name }}</h1>
                        @if ($location)
                            <p class="text-[1.05rem] text-white/85 flex items-center gap-2 mb-4">
                                <i class="fas fa-map-marker-alt text-[#4299e1]"></i>
                                {{ $location }}
                            </p>
                        @endif
                        @if ($institution->short_description)
                            <p class="text-[1.05rem] md:text-[1.15rem] text-white/85 leading-relaxed max-w-3xl">{{ $institution->short_description }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white/10 border border-white/20 rounded-xl p-6 shadow-[0_15px_40px_rgba(0,0,0,0.18)]">
                <div class="grid grid-cols-2 gap-3">
                    <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                        <p class="text-xs text-white/65 mb-1">Programs</p>
                        <p class="text-2xl font-bold">{{ $institution->programs->count() }}</p>
                    </div>
                    <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                        <p class="text-xs text-white/65 mb-1">Reviews</p>
                        <p class="text-2xl font-bold">{{ $institution->reviews_count ?? 0 }}</p>
                    </div>
                    @if ($institution->established_year)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                            <p class="text-xs text-white/65 mb-1">Established</p>
                            <p class="text-2xl font-bold">{{ $institution->established_year }}</p>
                        </div>
                    @endif
                    @if ($institution->followers_count)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                            <p class="text-xs text-white/65 mb-1">Followers</p>
                            <p class="text-2xl font-bold">{{ $institution->followers_count }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_340px] gap-8 items-start">
            <div class="space-y-8 min-w-0">
                @if ($institution->description || $institution->short_description)
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-7">
                            <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">About {{ $institution->name }}</h2>
                            @if ($institution->short_description)
                                <p class="text-gray-600 text-[0.95rem]">{{ $institution->short_description }}</p>
                            @endif
                        </div>
                        @if ($institution->description)
                            <div class="ck-content">{!! $institution->description !!}</div>
                        @endif
                    </div>
                @endif

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
                                        <p class="text-gray-500 text-sm leading-relaxed">{{ Str::limit(strip_tags($service->description), 100) }}</p>
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

                @if ($institution->profile)
                    @php $profile = $institution->profile; @endphp
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="text-[2rem] font-bold text-[#2c5aa0] mb-2">Facilities & Infrastructure</h2>
                            <p class="text-gray-600 text-[0.95rem]">Available services and campus facilities.</p>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach ([
                                ['key' => 'has_hostel', 'icon' => 'fa-bed', 'label' => 'Hostel'],
                                ['key' => 'has_library', 'icon' => 'fa-book', 'label' => 'Library'],
                                ['key' => 'has_lab', 'icon' => 'fa-flask', 'label' => 'Lab'],
                                ['key' => 'has_cafeteria', 'icon' => 'fa-utensils', 'label' => 'Cafeteria'],
                                ['key' => 'has_sports', 'icon' => 'fa-futbol', 'label' => 'Sports'],
                                ['key' => 'has_transportation', 'icon' => 'fa-bus', 'label' => 'Transport'],
                                ['key' => 'has_scholarship', 'icon' => 'fa-award', 'label' => 'Scholarship'],
                            ] as $fac)
                                <div class="rounded-xl border {{ $profile->{$fac['key']} ? 'border-[#4299e1]/25 bg-[#4299e1]/10 text-[#2c5aa0]' : 'border-gray-200 bg-[#f7fafc] text-gray-400' }} p-4">
                                    <div class="flex items-center gap-3">
                                        <span class="h-10 w-10 rounded-full bg-white flex items-center justify-center">
                                            <i class="fas {{ $fac['icon'] }}"></i>
                                        </span>
                                        <span class="font-semibold text-sm">{{ $fac['label'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($institution->programs->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                            <div>
                                <h2 class="text-[2rem] font-bold text-[#2c5aa0] mb-2">Programs Offered</h2>
                                <p class="text-gray-600 text-[0.95rem]">Popular programs currently open or upcoming.</p>
                            </div>
                            <a href="{{ route($routePrefix . '.programs', $institution->slug) }}" class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-[#4299e1] text-white font-semibold hover:bg-[#2c5aa0] transition no-underline">
                                View All <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($institution->programs->take(4) as $ip)
                                <a href="{{ route($routePrefix . '.programs.show', [$institution->slug, $ip->slug]) }}" class="block border border-gray-200 rounded-xl p-5 hover:border-[#4299e1] hover:shadow-[0_5px_15px_rgba(0,0,0,0.08)] transition no-underline">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-sm text-[#4299e1] font-semibold mb-1">{{ $ip->program?->faculty?->name ?? 'General' }}</p>
                                            <p class="font-bold text-gray-900">{{ $ip->display_name }}</p>
                                            @if ($ip->total_fee)
                                                <p class="text-sm text-gray-500 mt-2">Total: NPR {{ number_format($ip->total_fee) }}</p>
                                            @endif
                                        </div>
                                        @php $statusClasses = ['open'=>'bg-green-100 text-green-700','upcoming'=>'bg-yellow-100 text-yellow-700','closed'=>'bg-red-100 text-red-700']; @endphp
                                        <span class="text-xs font-semibold px-3 py-1 rounded-full {{ $statusClasses[$ip->status] ?? 'bg-gray-100 text-gray-600' }}">
                                            {{ ucfirst($ip->status) }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($institution->scholarships->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="text-[2rem] font-bold text-[#2c5aa0] mb-2">Available Scholarships</h2>
                            <p class="text-gray-600 text-[0.95rem]">Active scholarship opportunities from this institution.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($institution->scholarships as $scholarship)
                                @include('website.partials.scholarship-card', ['scholarship' => $scholarship])
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($institution->posts->isNotEmpty())
                    <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                        <div class="mb-6">
                            <h2 class="text-[2rem] font-bold text-[#2c5aa0] mb-2">Latest News & Updates</h2>
                            <p class="text-gray-600 text-[0.95rem]">Recent announcements and articles.</p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($institution->posts as $post)
                                @include('website.partials.post-card', ['post' => $post])
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
                        <div>
                            <h2 class="text-[2rem] font-bold text-[#2c5aa0] mb-2">Student Reviews</h2>
                            <p class="text-gray-600 text-[0.95rem]">{{ $institution->reviews_count ?? 0 }} approved review{{ ($institution->reviews_count ?? 0) == 1 ? '' : 's' }}</p>
                        </div>
                        @if ($rating)
                            <span class="inline-flex items-center gap-2 text-yellow-500 font-bold text-lg">
                                <i class="fas fa-star"></i>{{ $rating }}
                            </span>
                        @endif
                    </div>

                    @if ($institution->reviews->isNotEmpty())
                        <div class="space-y-4">
                            @foreach ($institution->reviews as $review)
                                <div class="border border-gray-200 rounded-xl p-5">
                                    <div class="flex items-start justify-between gap-4 mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-[#4299e1]/10 flex items-center justify-center text-sm font-bold text-[#2c5aa0]">
                                                {{ Str::upper(Str::substr($review->student?->name ?? 'S', 0, 1)) }}
                                            </div>
                                            <span class="font-semibold text-gray-900">{{ $review->student?->name ?? 'Student' }}</span>
                                        </div>
                                        <div class="flex text-yellow-400 text-sm">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? '' : 'opacity-30' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-gray-600 leading-relaxed text-sm">{{ $review->review }}</p>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No reviews yet.</p>
                    @endif

                    @auth('student')
                        <div class="mt-8 border-t border-gray-200 pt-6">
                            <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">Write a Review</h3>
                            <form method="POST" action="{{ route($routePrefix . '.review', $institution->slug) }}" class="space-y-4">
                                @csrf
                                @if (session('error'))
                                    <div class="bg-red-50 text-red-700 text-sm px-4 py-3 rounded-xl">{{ session('error') }}</div>
                                @endif
                                <div>
                                    <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Rating</label>
                                    <select name="rating" required class="px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                                        @for ($i = 5; $i >= 1; $i--)
                                            <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Your Review</label>
                                    <textarea name="review" rows="4" required maxlength="2000" placeholder="Share your experience..." class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition resize-none"></textarea>
                                </div>
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                    @endauth
                </div>
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">Quick Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('website.applications.create', ['institution' => $institution->slug]) }}" class="w-full px-5 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2 no-underline">
                            <i class="fas fa-paper-plane"></i> Apply Now
                        </a>
                        <a href="{{ route('website.inquiry.create', ['institution' => $institution->slug]) }}" class="w-full px-5 py-3.5 bg-white border-2 border-[#4299e1] text-[#2c5aa0] font-bold rounded-xl hover:bg-[#4299e1]/10 transition flex items-center justify-center gap-2 no-underline">
                            <i class="fas fa-question-circle"></i> Send Inquiry
                        </a>

                        @auth('student')
                        @php
                            $isFollowing = auth('student')->user()->follows()->where('institution_id', $institution->id)->exists();
                        @endphp
                        @if($isFollowing)
                        <form action="{{ route('student.follow.destroy', $institution) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full px-5 py-3.5 bg-white border-2 border-[#4299e1] text-[#4299e1] font-bold rounded-xl hover:bg-[#4299e1]/10 transition flex items-center justify-center gap-2">
                                <i class="fas fa-user-check"></i> Following
                            </button>
                        </form>
                        @else
                        <form action="{{ route('student.follow.store', $institution) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-5 py-3.5 bg-[#4299e1] text-white font-bold rounded-xl hover:bg-[#2c5aa0] transition flex items-center justify-center gap-2">
                                <i class="fas fa-user-plus"></i> Follow Institution
                            </button>
                        </form>
                        @endif
                        @endauth

                        @auth('student')
                            <form method="POST" action="{{ route($routePrefix . '.favorite', $institution->slug) }}">
                                @csrf
                                <button type="submit" class="w-full px-5 py-3.5 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:border-[#4299e1] hover:text-[#2c5aa0] transition flex items-center justify-center gap-2">
                                    <i class="fas fa-heart"></i>
                                    {{ $isFavorited ? 'Remove Favorite' : 'Add Favorite' }}
                                </button>
                            </form>
                        @endauth

                        @unless(in_array($institution->id, $compareItems))
                            <form method="POST" action="{{ route('website.compare.store') }}">
                                @csrf
                                <input type="hidden" name="type" value="institution">
                                <input type="hidden" name="slug" value="{{ $institution->slug }}">
                                <button type="submit" class="w-full px-5 py-3.5 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:border-[#4299e1] hover:text-[#2c5aa0] transition flex items-center justify-center gap-2">
                                    <i class="fas fa-balance-scale"></i> Add to Compare
                                </button>
                            </form>
                        @else
                            <a href="{{ route('website.compare.index') }}" class="w-full px-5 py-3.5 bg-white border-2 border-gray-200 text-gray-700 font-bold rounded-xl hover:border-[#4299e1] hover:text-[#2c5aa0] transition flex items-center justify-center gap-2 no-underline">
                                <i class="fas fa-balance-scale"></i> View Compare
                            </a>
                        @endunless
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">Institution Info</h3>
                    <dl class="space-y-4 text-sm">
                        @if ($institution->code)
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Code</dt><dd class="font-semibold text-gray-900 text-right">{{ $institution->code }}</dd></div>
                        @endif
                        @if ($institution->established_year)
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Established</dt><dd class="font-semibold text-gray-900 text-right">{{ $institution->established_year }}</dd></div>
                        @endif
                        @if ($institution->email)
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Email</dt><dd class="font-semibold text-gray-900 text-right break-all">{{ $institution->email }}</dd></div>
                        @endif
                        @if ($institution->phone)
                            <div class="flex justify-between gap-4"><dt class="text-gray-500">Phone</dt><dd class="font-semibold text-gray-900 text-right">{{ $institution->phone }}</dd></div>
                        @endif
                        @if ($institution->website)
                            <div class="flex justify-between gap-4">
                                <dt class="text-gray-500">Website</dt>
                                <dd class="text-right"><a href="{{ $institution->website }}" target="_blank" rel="noopener" class="text-[#2c5aa0] hover:text-[#4299e1] font-semibold break-all">{{ parse_url($institution->website, PHP_URL_HOST) }}</a></dd>
                            </div>
                        @endif
                    </dl>
                </div>

                @if ($institution->profile && ($institution->profile->facebook_url || $institution->profile->instagram_url || $institution->profile->linkedin_url || $institution->profile->youtube_url))
                    <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                        <h3 class="text-xl font-bold mb-4">Social Media</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach ([
                                ['url' => $institution->profile->facebook_url, 'icon' => 'fa-facebook-f', 'label' => 'Facebook'],
                                ['url' => $institution->profile->instagram_url, 'icon' => 'fa-instagram', 'label' => 'Instagram'],
                                ['url' => $institution->profile->linkedin_url, 'icon' => 'fa-linkedin-in', 'label' => 'LinkedIn'],
                                ['url' => $institution->profile->youtube_url, 'icon' => 'fa-youtube', 'label' => 'YouTube'],
                            ] as $social)
                                @if ($social['url'])
                                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-white px-3 py-2 rounded-xl hover:bg-white/20 transition no-underline text-sm font-semibold">
                                        <i class="fab {{ $social['icon'] }}"></i> {{ $social['label'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>
        </div>
    </div>
</section>

@if (session('success'))
    <div class="fixed bottom-4 right-4 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg z-50 text-sm font-medium">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif
@endsection
