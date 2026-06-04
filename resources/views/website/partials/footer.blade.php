@php
    $footerDomain = parse_url(config('app.url'), PHP_URL_HOST) ?: str_replace(['http://', 'https://'], '', config('app.url'));
@endphp

<footer class="bg-[#1a365d] text-white py-[70px] pb-[30px]">
    <div class="container mx-auto px-5 max-w-[1200px]">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-[50px]">
            <div>
                <a href="{{ route('website.home') }}" class="flex items-center gap-2.5 text-[1.8rem] font-bold mb-5 no-underline">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-12 w-auto">
                </a>
                <p class="opacity-80 mb-5 leading-relaxed">
                    We connect students with the best educational opportunities in Nepal. Our platform helps you find programs, institutions, and scholarships tailored to your goals.
                </p>
                <div class="flex gap-[15px]">
                    @foreach (['facebook-f', 'twitter', 'instagram', 'linkedin-in'] as $icon)
                        <a href="#" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-[#4299e1] hover:-translate-y-1 transition-all duration-300 no-underline">
                            <i class="fab fa-{{ $icon }}"></i>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h3 class="text-[1.3rem] text-white mb-[25px] pb-2.5 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-[3px] after:bg-[#4299e1]">
                    Quick Links
                </h3>
                <ul class="list-none">
                    @foreach ([
                        ['route' => 'website.home', 'label' => 'Home'],
                        ['route' => 'website.programs.index', 'label' => 'Programs'],
                        ['route' => 'website.institutions.index', 'label' => 'Colleges'],
                        ['route' => 'website.scholarships.index', 'label' => 'Scholarships'],
                        ['route' => 'website.consultancies.index', 'label' => 'Consultancies'],
                        ['route' => 'website.posts.index', 'label' => 'Blog'],
                    ] as $link)
                        <li class="mb-3">
                            <a href="{{ route($link['route']) }}" class="opacity-80 hover:opacity-100 hover:text-[#4299e1] hover:pl-[5px] transition-all duration-300 no-underline">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-[1.3rem] text-white mb-[25px] pb-2.5 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-[3px] after:bg-[#4299e1]">
                    About Us
                </h3>
                <ul class="list-none">
                    @foreach ([
                        ['route' => 'website.about', 'label' => 'Our Mission'],
                        ['route' => 'website.compare.index', 'label' => 'Compare'],
                        ['route' => 'website.inquiry.create', 'label' => 'Submit Inquiry'],
                        ['route' => 'website.contact.index', 'label' => 'Contact'],
                        ['route' => 'website.privacy-policy', 'label' => 'Privacy Policy'],
                        ['route' => 'website.terms', 'label' => 'Terms of Service'],
                    ] as $link)
                        <li class="mb-3">
                            <a href="{{ route($link['route']) }}" class="opacity-80 hover:opacity-100 hover:text-[#4299e1] hover:pl-[5px] transition-all duration-300 no-underline">
                                {{ $link['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h3 class="text-[1.3rem] text-white mb-[25px] pb-2.5 relative after:content-[''] after:absolute after:bottom-0 after:left-0 after:w-10 after:h-[3px] after:bg-[#4299e1]">
                    Contact Info
                </h3>
                <ul class="list-none">
                    <li class="flex items-start gap-[15px] mb-5">
                        <i class="fas fa-map-marker-alt text-[#4299e1] text-[1.2rem] mt-[3px]"></i>
                        <span class="opacity-80 leading-relaxed">New Baneshwor, Kathmandu, Nepal</span>
                    </li>
                    <li class="flex items-start gap-[15px] mb-5">
                        <i class="fas fa-phone text-[#4299e1] text-[1.2rem] mt-[3px]"></i>
                        <span class="opacity-80 leading-relaxed">+977 1 1234567<br>+977 9801234567</span>
                    </li>
                    <li class="flex items-start gap-[15px] mb-5">
                        <i class="fas fa-envelope text-[#4299e1] text-[1.2rem] mt-[3px]"></i>
                        <span class="opacity-80 leading-relaxed">
                            <a href="mailto:info&#64;{{ $footerDomain }}" class="hover:text-[#4299e1] no-underline">info&#64;{{ $footerDomain }}</a><br>
                            <a href="mailto:support&#64;{{ $footerDomain }}" class="hover:text-[#4299e1] no-underline">support&#64;{{ $footerDomain }}</a>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="text-center pt-[30px] border-t border-white/10 opacity-70 text-[0.9rem]">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved. | <a href="{{ route('website.privacy-policy') }}" class="hover:text-[#4299e1] no-underline">Privacy Policy</a> | <a href="{{ route('website.terms') }}" class="hover:text-[#4299e1] no-underline">Terms of Service</a></p>
        </div>
    </div>
</footer>
