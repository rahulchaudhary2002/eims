@extends('website.layouts.app')

@section('meta-title', 'Terms & Conditions - ' . config('app.name'))
@section('meta-description', 'Read our terms and conditions to understand your rights and responsibilities when using our platform.')

@section('content')
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Terms & Conditions'],
            ],
        ])

        <div class="mt-12 max-w-3xl">
            <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                <i class="fas fa-file-contract text-[#4299e1]"></i>
                Legal
            </span>
            <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-5">Terms & Conditions</h1>
            <p class="text-[1.05rem] text-white/85 leading-relaxed">
                Last updated: {{ date('F Y') }}. Please read these terms carefully before using {{ config('app.name') }}.
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
                        ['title' => '1. Acceptance of Terms', 'icon' => 'fa-handshake', 'text' => 'By accessing and using ' . config('app.name') . ', you agree to be bound by these Terms and Conditions. If you do not agree, please do not use our platform.'],
                        ['title' => '2. Use of the Platform', 'icon' => 'fa-laptop', 'text' => 'You may use our platform for lawful purposes only. You must not misuse, interfere with, or attempt to gain unauthorized access to any part of our platform.'],
                        ['title' => '3. Account Responsibility', 'icon' => 'fa-user-lock', 'text' => 'You are responsible for maintaining the confidentiality of your account credentials and for all activities that occur under your account.'],
                        ['title' => '4. Applications and Inquiries', 'icon' => 'fa-paper-plane', 'text' => 'Applications submitted through our platform are forwarded to the respective institutions. We do not guarantee admission or acceptance. Final decisions rest with the institutions.'],
                        ['title' => '5. Accuracy of Information', 'icon' => 'fa-check-circle', 'text' => 'While we strive to keep information accurate and up to date, we cannot guarantee the completeness or accuracy of institution, program, or scholarship information provided on the platform.'],
                        ['title' => '6. Intellectual Property', 'icon' => 'fa-copyright', 'text' => 'All content on this platform, including text, graphics, logos, and software, is the property of ' . config('app.name') . ' or its content suppliers and is protected by applicable laws.'],
                        ['title' => '7. Limitation of Liability', 'icon' => 'fa-exclamation-triangle', 'text' => config('app.name') . ' shall not be liable for any indirect, incidental, or consequential damages arising from your use of the platform.'],
                        ['title' => '8. Changes to Terms', 'icon' => 'fa-sync-alt', 'text' => 'We reserve the right to modify these terms at any time. Continued use of the platform after changes constitutes acceptance of the new terms.'],
                        ['title' => '9. Contact', 'icon' => 'fa-envelope', 'text' => null],
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
                                    <p>For questions about these Terms, please <a href="{{ route('website.contact.index') }}" class="text-[#4299e1] hover:text-[#2c5aa0] font-semibold no-underline">contact us</a>.</p>
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
                        @foreach (['Acceptance of Terms', 'Use of Platform', 'Account Responsibility', 'Applications', 'Accuracy', 'Intellectual Property', 'Liability', 'Changes', 'Contact'] as $i => $section)
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
                        <a href="{{ route('website.privacy-policy') }}"
                           class="flex items-center gap-2 bg-white/15 hover:bg-white/25 border border-white/20 text-white font-semibold px-4 py-3 rounded-xl transition no-underline">
                            <i class="fas fa-shield-alt text-[#4299e1]"></i> Privacy Policy
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
