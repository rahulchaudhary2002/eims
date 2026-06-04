@extends('website.layouts.app')

@section('meta-title', 'Privacy Policy - ' . config('app.name'))
@section('meta-description', 'Read our privacy policy to understand how we collect, use, and protect your personal information.')

@section('content')
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Privacy Policy'],
            ],
        ])

        <div class="mt-12 max-w-3xl">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                <i class="fas fa-shield-alt text-[#4299e1]"></i>
                Legal
            </span>
            <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-5">Privacy Policy</h1>
            <p class="text-[1.05rem] text-white/85 leading-relaxed">
                Last updated: {{ date('F Y') }}. This policy explains how we collect, use, and protect your personal information.
            </p>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[minmax(0,1fr)_280px] gap-8 items-start">
            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-10">
                <div class="space-y-8 text-gray-700 text-[0.97rem] leading-relaxed">
                    @foreach ([
                        ['title' => '1. Information We Collect', 'icon' => 'fa-database', 'text' => 'We collect information you provide directly to us, such as when you create an account, submit an application, or contact us. This may include your name, email address, phone number, and academic information.'],
                        ['title' => '2. How We Use Your Information', 'icon' => 'fa-cogs', 'text' => 'We use the information we collect to provide, maintain, and improve our services, process applications, send you communications, and comply with legal obligations.'],
                        ['title' => '3. Information Sharing', 'icon' => 'fa-share-alt', 'text' => 'We share your information with educational institutions when you submit an application or inquiry to them. We do not sell your personal information to third parties.'],
                        ['title' => '4. Data Security', 'icon' => 'fa-lock', 'text' => 'We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.'],
                        ['title' => '5. Your Rights', 'icon' => 'fa-user-shield', 'text' => 'You have the right to access, correct, or delete your personal information. You may also withdraw consent where processing is based on consent.'],
                        ['title' => '6. Contact Us', 'icon' => 'fa-envelope', 'text' => null],
                    ] as $section)
                        <div class="flex gap-4">
                            <div class="h-10 w-10 rounded-xl bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center flex-shrink-0 mt-1">
                                <i class="fas {{ $section['icon'] }}"></i>
                            </div>
                            <div>
                                <h2 class="text-[1.1rem] font-bold text-gray-900 mb-2">{{ $section['title'] }}</h2>
                                @if ($section['text'])
                                    <p>{{ $section['text'] }}</p>
                                @else
                                    <p>If you have questions about this Privacy Policy, please contact us at <a href="{{ route('website.contact.index') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold no-underline">our contact page</a>.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h3 class="text-xl font-bold text-[#2c5aa0] mb-4">Quick Navigation</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach (['Information We Collect', 'How We Use It', 'Information Sharing', 'Data Security', 'Your Rights', 'Contact Us'] as $i => $section)
                            <li class="flex items-center gap-2 text-gray-600 hover:text-[#2c5aa0] transition">
                                <span class="h-6 w-6 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center text-xs font-bold flex-shrink-0">{{ $i + 1 }}</span>
                                {{ $section }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h3 class="text-xl font-bold mb-3">Related Policies</h3>
                    <div class="space-y-3">
                        <a href="{{ route('website.terms') }}"
                           class="flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white font-semibold px-4 py-3 rounded-xl transition no-underline">
                            <i class="fas fa-file-contract text-[#4299e1]"></i> Terms & Conditions
                        </a>
                        <a href="{{ route('website.contact.index') }}"
                           class="flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white font-semibold px-4 py-3 rounded-xl transition no-underline">
                            <i class="fas fa-envelope text-[#4299e1]"></i> Contact Us
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
