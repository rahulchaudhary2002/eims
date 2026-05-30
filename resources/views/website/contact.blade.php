@extends('website.layouts.app')

@section('meta-title', 'Contact Us - ' . config('app.name'))
@section('meta-description', 'Get in touch with us for any questions or support.')

@section('content')
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] pt-[150px] pb-28 text-white">
    <div class="container max-w-7xl mx-auto px-4">
        @include('website.partials.breadcrumb', [
            'variant' => 'dark',
            'breadcrumbs' => [
                ['label' => 'Contact'],
            ],
        ])

        <div class="grid lg:grid-cols-[1.05fr_0.95fr] gap-10 items-center mt-12">
            <div>
                <span class="inline-flex items-center gap-2 bg-white/10 border border-white/20 rounded-full px-4 py-2 text-sm font-semibold mb-5">
                    <i class="fas fa-envelope text-[#4299e1]"></i>
                    Get In Touch
                </span>
                <h1 class="text-[2.6rem] md:text-[3.4rem] font-bold leading-[1.15] mb-5">Contact Us</h1>
                <p class="text-[1.05rem] md:text-[1.15rem] text-white/85 leading-relaxed max-w-2xl">
                    Have questions about institutions, programs, scholarships, or your application journey? Send us a message and we will respond as soon as possible.
                </p>
            </div>

            <div class="bg-white/10 border border-white/20 rounded-xl p-6 shadow-[0_15px_40px_rgba(0,0,0,0.18)]">
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach ([
                        ['icon' => 'fa-map-marker-alt', 'title' => 'Address', 'text' => 'Kathmandu, Nepal'],
                        ['icon' => 'fa-phone', 'title' => 'Phone', 'text' => '+977 1 1234567'],
                        ['icon' => 'fa-envelope', 'title' => 'Email', 'text' => 'info@' . parse_url(config('app.url'), PHP_URL_HOST)],
                        ['icon' => 'fa-clock', 'title' => 'Support', 'text' => 'Sun - Fri, 10 AM - 5 PM'],
                    ] as $info)
                        <div class="rounded-xl bg-white/10 border border-white/15 p-4">
                            <div class="w-11 h-11 rounded-xl bg-white text-[#2c5aa0] flex items-center justify-center mb-4">
                                <i class="fas {{ $info['icon'] }}"></i>
                            </div>
                            <p class="text-sm text-white/70 font-semibold mb-1">{{ $info['title'] }}</p>
                            <p class="text-white font-semibold">{{ $info['text'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-[#f7fafc] pt-12 pb-20">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid lg:grid-cols-[340px_minmax(0,1fr)] gap-8 items-start">
            <aside class="lg:sticky lg:top-28 space-y-6">
                <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6">
                    <h2 class="text-xl font-bold text-[#2c5aa0] mb-4">Connect With Us</h2>
                    <div class="space-y-4">
                        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-[#2c5aa0] transition no-underline">
                            <span class="h-10 w-10 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center"><i class="fab fa-facebook-f"></i></span>
                            <span class="font-semibold">Facebook</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-[#2c5aa0] transition no-underline">
                            <span class="h-10 w-10 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center"><i class="fab fa-twitter"></i></span>
                            <span class="font-semibold">Twitter</span>
                        </a>
                        <a href="#" class="flex items-center gap-3 text-gray-700 hover:text-[#2c5aa0] transition no-underline">
                            <span class="h-10 w-10 rounded-full bg-[#4299e1]/10 text-[#2c5aa0] flex items-center justify-center"><i class="fab fa-instagram"></i></span>
                            <span class="font-semibold">Instagram</span>
                        </a>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] rounded-xl p-6 text-white shadow-[0_5px_15px_rgba(0,0,0,0.08)]">
                    <h2 class="text-xl font-bold mb-3">Institution Question?</h2>
                    <p class="text-sm text-white/85 leading-relaxed mb-5">Use inquiry if your message is about a specific college, program, or admission detail.</p>
                    <a href="{{ route('website.inquiry.create') }}" class="inline-flex items-center gap-2 bg-white text-[#2c5aa0] font-semibold px-5 py-3 rounded-xl hover:bg-gray-100 transition no-underline">
                        <i class="fas fa-paper-plane"></i> Submit Inquiry
                    </a>
                </div>
            </aside>

            <div class="bg-white rounded-xl shadow-[0_5px_15px_rgba(0,0,0,0.08)] border border-gray-200 p-6 md:p-8">
                <div class="mb-7">
                    <h2 class="relative inline-block text-[2.1rem] md:text-[2.3rem] font-bold text-[#2c5aa0] mb-4 after:content-[''] after:absolute after:left-0 after:-bottom-2 after:w-[70px] after:h-[3px] after:bg-[#4299e1]">Send Message</h2>
                    <p class="text-gray-600 text-[0.95rem]">Share your details and message below. Our team will follow up shortly.</p>
                </div>

                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('website.contact.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required maxlength="255"
                                placeholder="Your full name"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('name') border-red-400 @enderror">
                        </div>
                        <div>
                            <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="20"
                                placeholder="+977 9800000000"
                                class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('phone') border-red-400 @enderror">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required maxlength="255"
                            placeholder="your@email.com"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition @error('email') border-red-400 @enderror">
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Subject (optional)</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" maxlength="255"
                            placeholder="What is your message about?"
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition">
                    </div>

                    <div>
                        <label class="block text-[0.95rem] font-semibold text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                        <textarea name="message" rows="5" required maxlength="3000"
                            placeholder="Write your message..."
                            class="w-full px-4 py-3 text-sm border border-gray-200 rounded-xl focus:border-[#4299e1] focus:ring-4 focus:ring-[#4299e1]/10 transition resize-none @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-[#4299e1] to-[#2c5aa0] hover:from-[#2c5aa0] hover:to-[#1a365d] text-white font-bold rounded-xl transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center gap-2">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
