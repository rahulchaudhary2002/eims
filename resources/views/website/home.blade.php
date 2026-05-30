@extends('website.layouts.app')

@section('meta-title', config('app.name') . ' - Find Institutions, Programs & Scholarships')
@section('meta-description', 'Discover top educational institutions, programs, and scholarships. Compare, apply, and start your academic journey.')

@section('content')

{{-- Hero --}}
<section class="pt-40 pb-24 px-4 relative overflow-hidden bg-gradient-to-br from-[#2c5aa0]/10 to-[#1a365d]/5" id="home">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-br from-[#4299e1]/15 to-[#2c5aa0]/5 [clip-path:polygon(100%_0,0_0,100%_100%)]"></div>
    <div class="container max-w-7xl mx-auto px-5 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-5xl text-[#2c5aa0] mb-5 leading-tight font-bold">
                    Find Your Perfect <span class="text-[#4299e1]">Educational Path</span>
                </h1>
                <p class="text-xl text-gray-600 mb-8">
                    Discover institutions, programs, scholarships, and consultancies tailored to your career goals. Start your educational journey with {{ config('app.name') }} today.
                </p>
                <div class="flex flex-wrap gap-5 justify-center lg:justify-start">
                    <a href="#search" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-lg no-underline">
                        <i class="fas fa-search"></i>
                        Find Programs
                    </a>
                    <a href="{{ route('website.institutions.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all bg-white text-[#4299e1] border-2 border-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-1 no-underline">
                        <i class="fas fa-university"></i>
                        Browse Colleges
                    </a>
                </div>
            </div>
            <div class="relative">
                <img class="w-full rounded-xl shadow-2xl border-8 border-white" src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Students studying">
                <div class="absolute -top-5 -right-5 bg-white rounded-xl p-5 shadow-lg items-center gap-4 border-2 border-gray-200 animate-[float_3s_ease-in-out_infinite] hidden lg:flex">
                    <div class="w-12 h-12 rounded-full bg-[#4299e1]/10 text-[#4299e1] flex items-center justify-center text-2xl">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl text-gray-800 mb-1 font-bold">{{ number_format($stats['programs']) }}+</h3>
                        <p class="text-sm text-gray-600">Open Programs</p>
                    </div>
                </div>
                <div class="absolute bottom-7 -left-7 bg-white rounded-xl p-5 shadow-lg items-center gap-4 border-2 border-gray-200 animate-[float_3s_ease-in-out_infinite] hidden lg:flex" style="animation-delay: 1s">
                    <div class="w-12 h-12 rounded-full bg-teal-500/10 text-teal-500 flex items-center justify-center text-2xl">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl text-gray-800 mb-1 font-bold">{{ number_format($stats['institutions']) }}+</h3>
                        <p class="text-sm text-gray-600">Partner Colleges</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Search --}}
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] p-10 rounded-xl shadow-lg relative z-[10] -mt-16 mx-4 lg:mx-auto max-w-7xl" id="search">
    <div>
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-white mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Find Your Program</h2>
            <p class="text-white/90 text-lg max-w-2xl mx-auto">Search programs, colleges, and scholarships across Nepal</p>
        </div>
        <form method="get" action="{{ route('website.programs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 bg-white/10 p-8 rounded-xl backdrop-blur-sm">
            <div class="flex flex-col">
                <label for="homeSearch" class="mb-2 font-semibold text-white">What do you want to study?</label>
                <input type="text" name="search" id="homeSearch" class="p-4 border-none rounded-xl text-base transition-all bg-white focus:outline-none focus:ring-4 focus:ring-white/50" placeholder="e.g., BIM, MBBS, Computer Engineering">
            </div>
            <div class="flex flex-col">
                <label for="homeFaculty" class="mb-2 font-semibold text-white">Faculty</label>
                <select id="homeFaculty" name="faculty" class="p-4 border-none rounded-xl text-base transition-all bg-white focus:outline-none focus:ring-4 focus:ring-white/50">
                    <option value="">Any Faculty</option>
                    @foreach($faculties as $faculty)
                        <option value="{{ $faculty->slug }}">{{ $faculty->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label for="homeStatus" class="mb-2 font-semibold text-white">Admission Status</label>
                <select id="homeStatus" name="status" class="p-4 border-none rounded-xl text-base transition-all bg-white focus:outline-none focus:ring-4 focus:ring-white/50">
                    <option value="">Any Status</option>
                    <option value="open">Open</option>
                    <option value="upcoming">Upcoming</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <button type="submit" class="self-end h-[58px] bg-white text-[#2c5aa0] font-bold text-lg rounded-xl hover:bg-[#4299e1] hover:text-white inline-flex items-center gap-2 justify-center transition-all">
                <i class="fas fa-search"></i>
                Search Now
            </button>
        </form>
    </div>
</section>

{{-- Open Programs --}}
@if ($openPrograms->isNotEmpty())
<section class="py-24 px-4 bg-[#f7fafc]/80" id="programs">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Featured Programs</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Popular programs offered by leading colleges</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach ($openPrograms as $program)
                @include('website.partials.program-card', ['program' => $program])
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('website.programs.index') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-white bg-[#4299e1] hover:bg-[#2c5aa0] transition no-underline">
                View All Programs
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- Featured Colleges --}}
@if ($featuredInstitutions->isNotEmpty())
<section class="py-24 px-4 bg-white" id="institutions">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Featured Colleges</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Top-rated educational institutions from across Nepal</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach ($featuredInstitutions as $institution)
                @include('website.partials.institution-card', ['institution' => $institution])
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('website.institutions.index', ['is_featured' => 1]) }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold transition-all bg-white text-[#4299e1] border-2 border-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-1 no-underline">
                View All Colleges
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- Scholarships --}}
@if ($activeScholarships->isNotEmpty())
<section class="py-24 px-4 bg-[#f7fafc]/80" id="scholarships">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Available Scholarships</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Financial assistance for your educational journey</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-7">
            @foreach ($activeScholarships as $scholarship)
                @include('website.partials.scholarship-card', ['scholarship' => $scholarship])
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('website.scholarships.index') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-white bg-[#4299e1] hover:bg-[#2c5aa0] transition no-underline">
                View All Scholarships
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ── TOP CONSULTANCIES ───────────────────────────────────────────── --}}
@if ($consultancies->isNotEmpty())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Top Consultancies</h2>
                <p class="text-gray-500 text-sm mt-1">Expert guidance for your study abroad journey</p>
            </div>
            <a href="{{ route('website.consultancies.index') }}"
               class="text-blue-600 font-semibold text-sm hover:underline no-underline">
                View all →
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($consultancies as $consultancy)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition-shadow">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-3">
                        @if ($consultancy->logo)
                            <img src="{{ Storage::url($consultancy->logo) }}" alt="{{ $consultancy->name }}" class="w-10 h-10 object-contain">
                        @else
                            <i class="fas fa-handshake text-blue-500"></i>
                        @endif
                    </div>
                    <h3 class="font-semibold text-gray-900 text-base mb-1">{{ $consultancy->name }}</h3>
                    <p class="text-xs text-gray-500 mb-3">
                        <i class="fas fa-map-marker-alt text-gray-400 mr-1"></i>
                        {{ $consultancy->city ?? $consultancy->province ?? 'Nepal' }}
                    </p>
                    <div class="flex flex-wrap gap-1 mb-3">
                        @foreach ($consultancy->consultancyServices->take(2) as $svc)
                            <span class="bg-blue-50 text-blue-600 text-xs px-2 py-0.5 rounded-full">
                                {{ \App\Models\ConsultancyService::SERVICE_TYPES[$svc->service_type] ?? $svc->service_type }}
                            </span>
                        @endforeach
                    </div>
                    <a href="{{ route('website.consultancies.show', $consultancy->slug) }}"
                       class="text-blue-600 text-sm font-semibold hover:underline no-underline">
                        View Details →
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── WHY USE THIS PLATFORM ───────────────────────────────────────── --}}
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Why Choose Us?</h2>
        <p class="text-gray-500 text-center mb-10">Everything you need to make the right academic decision</p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ([
                ['icon' => 'fa-search', 'color' => 'bg-blue-100 text-blue-600', 'title' => 'Smart Discovery', 'desc' => 'Search and filter thousands of institutions, programs, and scholarships in one place.'],
                ['icon' => 'fa-balance-scale', 'color' => 'bg-green-100 text-green-600', 'title' => 'Easy Comparison', 'desc' => 'Compare institutions, programs, fees, and opportunities side by side.'],
                ['icon' => 'fa-paper-plane', 'color' => 'bg-purple-100 text-purple-600', 'title' => 'Direct Application', 'desc' => 'Apply to your preferred programs directly through the platform.'],
                ['icon' => 'fa-award', 'color' => 'bg-yellow-100 text-yellow-600', 'title' => 'Scholarship Access', 'desc' => 'Discover merit-based, need-based, and platform scholarships easily.'],
                ['icon' => 'fa-handshake', 'color' => 'bg-orange-100 text-orange-600', 'title' => 'Expert Consultancy', 'desc' => 'Connect with top consultancies for study abroad guidance.'],
                ['icon' => 'fa-star', 'color' => 'bg-red-100 text-red-600', 'title' => 'Verified Reviews', 'desc' => 'Read approved student reviews to make informed decisions.'],
            ] as $feature)
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl {{ $feature['color'] }} flex items-center justify-center flex-shrink-0">
                        <i class="fas {{ $feature['icon'] }} text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $feature['title'] }}</h3>
                        <p class="text-gray-500 text-sm">{{ $feature['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── STUDENT JOURNEY ─────────────────────────────────────────────── --}}
<section class="py-16 bg-white">
    <div class="max-w-5xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">Your Journey in 4 Steps</h2>
        <p class="text-gray-500 text-center mb-12">From discovery to admission, we're with you every step</p>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 relative">
            @foreach ([
                ['step' => '1', 'icon' => 'fa-search', 'title' => 'Search', 'desc' => 'Browse institutions, programs, and scholarships'],
                ['step' => '2', 'icon' => 'fa-balance-scale', 'title' => 'Compare', 'desc' => 'Compare options and find the best fit for you'],
                ['step' => '3', 'icon' => 'fa-paper-plane', 'title' => 'Apply', 'desc' => 'Submit your application directly online'],
                ['step' => '4', 'icon' => 'fa-graduation-cap', 'title' => 'Succeed', 'desc' => 'Get admitted and start your academic journey'],
            ] as $step)
                <div class="text-center">
                    <div class="w-16 h-16 rounded-full bg-blue-600 text-white flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">
                        {{ $step['step'] }}
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ $step['title'] }}</h3>
                    <p class="text-gray-500 text-sm">{{ $step['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── LATEST POSTS ────────────────────────────────────────────────── --}}
@if ($latestPosts->isNotEmpty())
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Latest News & Blog</h2>
                <p class="text-gray-500 text-sm mt-1">Stay informed with latest updates</p>
            </div>
            <a href="{{ route('website.posts.index') }}"
               class="text-blue-600 font-semibold text-sm hover:underline no-underline">
                View all →
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($latestPosts->take(3) as $post)
                @include('website.partials.post-card', ['post' => $post])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── REVIEWS ─────────────────────────────────────────────────────── --}}
@if ($recentReviews->isNotEmpty())
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <h2 class="text-2xl font-bold text-gray-900 text-center mb-2">What Students Say</h2>
        <p class="text-gray-500 text-center mb-10">Verified reviews from real students</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($recentReviews as $review)
                <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100">
                    <div class="flex text-yellow-400 mb-3">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star text-sm {{ $i <= $review->rating ? '' : 'opacity-30' }}"></i>
                        @endfor
                    </div>
                    <p class="text-gray-700 text-sm mb-4 line-clamp-3">{{ $review->review }}</p>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-600 text-xs font-bold">{{ Str::upper(Str::substr($review->student?->name ?? 'S', 0, 1)) }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $review->student?->name ?? 'Student' }}</div>
                            @if ($review->institution)
                                <div class="text-xs text-gray-500">{{ $review->institution->name }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CALL TO ACTION ──────────────────────────────────────────────── --}}
<section class="py-16 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 items-center">
            <div>
                <h2 class="text-2xl font-bold mb-3">Start Your Academic Journey Today</h2>
                <p class="text-blue-100 mb-6">Create a free student account to apply for programs, track applications, and save your favorite institutions.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('register') }}"
                       class="px-6 py-3 bg-white text-blue-700 font-semibold rounded-xl hover:bg-blue-50 transition-colors no-underline">
                        Register as Student
                    </a>
                    <a href="{{ route('website.institutions.index') }}"
                       class="px-6 py-3 border-2 border-white text-white font-semibold rounded-xl hover:bg-white/10 transition-colors no-underline">
                        Browse Institutions
                    </a>
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-bold mb-3">Are You an Institution?</h2>
                <p class="text-blue-100 mb-6">List your institution, post programs, manage applications, and reach thousands of students.</p>
                <a href="{{ route('admin.login') }}"
                   class="inline-block px-6 py-3 bg-white/10 border-2 border-white text-white font-semibold rounded-xl hover:bg-white/20 transition-colors no-underline">
                    Institution Login
                </a>
            </div>
        </div>
    </div>
</section>

@endsection
