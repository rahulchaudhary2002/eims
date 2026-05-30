@extends('website.layouts.app')

@section('meta-title', 'About Us - ' . config('app.name'))
@section('meta-description', 'Learn about our mission to connect students with the best educational opportunities in Nepal.')

@section('content')
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'About Us'],
            ],
        ])

        <div class="mt-12 max-w-3xl">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                <i class="fas fa-info-circle text-[#4299e1]"></i>
                Our Story
            </span>
            <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-5">About {{ config('app.name') }}</h1>
            <p class="text-[1.05rem] md:text-[1.15rem] text-white/85 leading-relaxed max-w-2xl">
                Empowering students to find the right educational path by connecting them with top institutions, programs, and scholarships across Nepal.
            </p>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4 space-y-10">

        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
            <div class="mb-7">
                <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Our Mission</h2>
            </div>
            <p class="text-gray-600 leading-relaxed text-[1.05rem]">
                {{ config('app.name') }} is an education platform dedicated to helping students discover, compare, and apply to educational institutions across Nepal. We believe every student deserves access to quality education information and support to make informed academic decisions.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ([
                ['icon' => 'fa-eye', 'title' => 'Our Vision', 'text' => 'To be the most trusted education discovery platform, enabling every student to find their ideal academic journey with confidence.'],
                ['icon' => 'fa-bullseye', 'title' => 'Our Goal', 'text' => 'Simplify the process of finding, comparing, and applying to educational institutions and scholarships across Nepal.'],
                ['icon' => 'fa-heart', 'title' => 'Our Values', 'text' => 'Transparency, accessibility, and a student-first approach in everything we do - from listings to application support.'],
            ] as $item)
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 text-center">
                    <div class="w-14 h-14 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center mx-auto mb-4">
                        <i class="fas {{ $item['icon'] }} text-xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-3">{{ $item['title'] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $item['text'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
            <div class="mb-7">
                <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">What We Offer</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ([
                    'Comprehensive database of educational institutions',
                    'Detailed program and fee information',
                    'Active scholarship listings with easy applications',
                    'Verified student reviews and ratings',
                    'Expert consultancy connections for study abroad',
                    'Direct application submission to institutions',
                    'Side-by-side institution and program comparison',
                    'Latest news and updates from institutions',
                ] as $feature)
                    <div class="flex items-center gap-3 text-[0.97rem] text-gray-700">
                        <span class="h-8 w-8 rounded-lg bg-green-100 text-green-600 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-check text-xs"></i>
                        </span>
                        {{ $feature }}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-8 md:p-10 text-center text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
            <h2 class="text-[2rem] font-bold mb-3">Ready to Start Your Journey?</h2>
            <p class="text-white/85 mb-7 text-[1.05rem] max-w-xl mx-auto">Join thousands of students already using our platform to find the right institution and program.</p>
            <div class="flex justify-center gap-4 flex-wrap">
                <a href="{{ route('register') }}"
                   class="px-7 py-3.5 bg-white text-[#2c5aa0] font-bold rounded-xl hover:bg-gray-100 transition no-underline">
                    Create Account
                </a>
                <a href="{{ route('website.contact.index') }}"
                   class="px-7 py-3.5 border-2 border-white text-white font-bold rounded-xl hover:bg-white/10 transition no-underline">
                    Contact Us
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
