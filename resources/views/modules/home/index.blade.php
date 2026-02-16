@extends('layouts.app')

@section('title', 'Home')

@section('page-specific-style')
<style>
    @keyframes modalAppear {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.9);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }

    .active-tab {
        background-color: white !important;
        color: #2c5aa0 !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection

@section('content')

<!-- Coming Soon Modal -->
<!-- <div class="fixed inset-0 bg-black/70 flex justify-center items-center z-[2000] opacity-100 transition-opacity duration-300" id="comingSoonModal">
    <div class="bg-white rounded-xl p-10 max-w-[500px] w-[90%] text-center shadow-2xl relative animate-[modalAppear_0.5s_ease-out]">
        <button class="absolute top-4 right-4 bg-none border-none text-xl text-gray-500 cursor-pointer transition-all hover:text-orange-500 hover:rotate-90" id="modalClose">
            <i class="fas fa-times"></i>
        </button>
        <div class="text-[4rem] text-[#2c5aa0] mb-5">
            <i class="fas fa-rocket"></i>
        </div>
        <h2 class="text-[#2c5aa0] mb-4 text-3xl font-bold">Coming Soon!</h2>
        <p class="text-gray-500 mb-6 leading-relaxed">We're currently working on making Sikuna.com even better! Some features are still in development and will be available soon.</p>
        <button class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold cursor-pointer transition-all bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-lg" id="modalOkBtn">
            Got it!
        </button>
    </div>
</div> -->

<!-- Hero Section -->
<section class="pt-40 pb-24 px-4 relative overflow-hidden bg-gradient-to-br from-[#2c5aa0]/10 to-[#1a365d]/5" id="home">
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-br from-[#4299e1]/15 to-[#2c5aa0]/5 clip-path-[polygon(100%_0,0_0,100%_100%)]"></div>
    <div class="container max-w-7xl mx-auto px-5 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="text-center lg:text-left">
                <h1 class="text-4xl md:text-5xl lg:text-5xl text-[#2c5aa0] mb-5 leading-tight font-bold">Find Your Perfect <span class="text-[#4299e1]">Educational Path</span></h1>
                <p class="text-xl text-gray-600 mb-8">Discover thousands of courses, institutions, and scholarships tailored to your career goals. Start your educational journey with Sikuna today.</p>
                <div class="flex flex-wrap gap-5 justify-center lg:justify-start">
                    <a href="#search" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold cursor-pointer transition-all bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-lg">
                        <i class="fas fa-search"></i>
                        Find Courses
                    </a>
                    <a href="#institutions" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold cursor-pointer transition-all bg-white text-[#4299e1] border-2 border-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-1">
                        <i class="fas fa-university"></i>
                        Browse Colleges
                    </a>
                </div>
            </div>
            <div class="relative">
                <img class="w-full rounded-xl shadow-2xl border-8 border-white" src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Students studying">
                <div class="absolute -top-5 -right-5 bg-white rounded-xl p-5 shadow-lg flex items-center gap-4 border-2 border-gray-200 animate-[float_3s_ease-in-out_infinite] hidden lg:flex">
                    <div class="w-12 h-12 rounded-full bg-[#4299e1]/10 text-[#4299e1] flex items-center justify-center text-2xl">
                        <i class="fas fa-users"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl text-gray-800 mb-1 font-bold">50,000+</h3>
                        <p class="text-sm text-gray-600">Students Enrolled</p>
                    </div>
                </div>
                <div class="absolute bottom-7 -left-7 bg-white rounded-xl p-5 shadow-lg flex items-center gap-4 border-2 border-gray-200 animate-[float_3s_ease-in-out_infinite] hidden lg:flex" style="animation-delay: 1s">
                    <div class="w-12 h-12 rounded-full bg-teal-500/10 text-teal-500 flex items-center justify-center text-2xl">
                        <i class="fas fa-university"></i>
                    </div>
                    <div>
                        <h3 class="text-2xl text-gray-800 mb-1 font-bold">{{ $institutionCount }}</h3>
                        <p class="text-sm text-gray-600">Partner Institutions</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] p-10 rounded-xl shadow-lg relative z-[100] -mt-16 lg:px-5" id="search">
    <div class="mx-5 md:mx-10 lg:mx-auto max-w-7xl">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-white mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Find Your Program</h2>
            <p class="text-white/90 text-lg max-w-2xl mx-auto">Search from thousands of courses and programs across Nepal</p>
        </div>
        <div class="flex gap-2.5 mb-6 justify-center flex-wrap">
            <button class="px-8 py-3 bg-white/10 border-none font-semibold text-white/80 cursor-pointer rounded-xl transition-all hover:text-white hover:bg-white/20 backdrop-blur-sm active-tab bg-white text-[#2c5aa0] shadow-lg" data-tab="courses">Courses</button>
            <button class="px-8 py-3 bg-white/10 border-none font-semibold text-white/80 cursor-pointer rounded-xl transition-all hover:text-white hover:bg-white/20 backdrop-blur-sm" data-tab="programs">Programs</button>
            <button class="px-8 py-3 bg-white/10 border-none font-semibold text-white/80 cursor-pointer rounded-xl transition-all hover:text-white hover:bg-white/20 backdrop-blur-sm" data-tab="institutions">Colleges</button>
            <button class="px-8 py-3 bg-white/10 border-none font-semibold text-white/80 cursor-pointer rounded-xl transition-all hover:text-white hover:bg-white/20 backdrop-blur-sm" data-tab="scholarships">Scholarships</button>
        </div>
        <form method="get" action="{{ route('course') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 bg-white/10 p-8 rounded-xl backdrop-blur-sm" id="searchForm">
            <div class="flex flex-col">
                <label for="searchKeyword" class="mb-2 font-semibold text-white">What do you want to study?</label>
                <input type="text" name="search" id="searchKeyword" class="p-4 border-none rounded-xl text-base transition-all bg-white focus:outline-none focus:ring-4 focus:ring-white/50" placeholder="e.g., BIM, MBBS, Computer Engineering">
            </div>
            <div class="flex flex-col">
                <label for="searchProgram" class="mb-2 font-semibold text-white">Program</label>
                <select id="searchProgram" name="program" class="p-4 border-none rounded-xl text-base transition-all bg-white focus:outline-none focus:ring-4 focus:ring-white/50">
                    <option value="">Any Programs</option>
                    @foreach($programs as $program)
                    <option value="{{ $program->slug }}">{{ $program->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col">
                <label for="searchLevel" class="mb-2 font-semibold text-white">Level</label>
                <select id="searchLevel" name="level" class="p-4 border-none rounded-xl text-base transition-all bg-white focus:outline-none focus:ring-4 focus:ring-white/50">
                    <option value="">Any Level</option>
                    @foreach($levels as $level)
                    <option value="{{ $level->slug }}">{{ $level->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="self-end h-[50px] bg-white text-[#2c5aa0] font-bold text-lg rounded-xl hover:bg-[#4299e1] hover:text-white inline-flex items-center gap-2 justify-center transition-all">
                <i class="fas fa-search"></i>
                Search Now
            </button>
        </form>
    </div>
</section>

<!-- Featured Programs Section -->
<section class="py-24 px-4 bg-[#f7fafc]/80" id="programs">
    <div class="container max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Featured Programs</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Popular programs offered by leading colleges</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @foreach($featuredPrograms as $program)
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl">
                <div class="p-7 pb-4 border-b border-gray-200">
                    <div class="flex items-center gap-2.5 mb-4">
                        <span class="px-4 py-1.5 bg-[#4299e1]/10 text-[#4299e1] rounded-full text-xs font-semibold">{{ $program->category->name }}</span>
                    </div>
                    <h3 class="text-2xl text-[#2c5aa0] mb-2.5 leading-tight font-bold">{{ $program->name }}</h3>
                    <div class="text-gray-600 mb-4 flex items-center gap-1.5 text-sm">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ $program->duration }} • {{ $program->level->name }}</span>
                    </div>
                </div>
                <div class="p-5 pt-5">
                    <!-- <div class="flex flex-col gap-3 mb-5">
                        <div class="flex items-center gap-2.5 text-gray-600 text-sm">
                            <i class="fas fa-check-circle text-[#4299e1]"></i>
                            <span>Project-based learning</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-gray-600 text-sm">
                            <i class="fas fa-check-circle text-[#4299e1]"></i>
                            <span>Research opportunities</span>
                        </div>
                        <div class="flex items-center gap-2.5 text-gray-600 text-sm">
                            <i class="fas fa-check-circle text-[#4299e1]"></i>
                            <span>Industry partnerships</span>
                        </div>
                    </div> -->
                    <div class="flex justify-between items-center">
                        <div class="text-2xl font-bold text-[#2c5aa0] whitespace-nowrap">NPR {{ number_format($program->fee) }}<small class="text-sm text-gray-600 font-normal">/total</small></div>
                        <a href="{{ route('program.show', [$program->slug]) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold cursor-pointer transition-all bg-[#4299e1] text-white text-sm hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-lg">
                            View Program
                            <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('program.index') }}" class="inline-flex items-center gap-2 px-8 py-4 rounded-xl font-semibold text-white bg-[#4299e1] hover:bg-[#2c5aa0] transition">
                View All Programs
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<section class="py-24 px-4 bg-white" id="institutions">
    <div class="container max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Featured Colleges</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Top-rated educational institutions from across Nepal</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse ($colleges as $college)
            <a href="{{ route('institution.show', [$college->slug]) }}" class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200">
                <div class="h-44 overflow-hidden">
                    <img src="{{ Storage::url($college->cover_image) }}" alt="{{ $college->name }}" class="w-full h-full object-cover transition-all hover:scale-105">
                </div>
                <div class="p-7">
                    <span class="inline-block px-4 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold mb-4">{{ $college->category->name }}</span>
                    <h3 class="text-2xl text-[#2c5aa0] mb-2.5 leading-tight font-bold">{{ $college->name }}</h3>
                    <div class="text-gray-600 mb-4 flex items-center gap-1.5 text-sm">
                        <i class="fas fa-map-marker-alt text-[#4299e1]"></i>
                        <span>{{ $college->address }}</span>
                    </div>
                    <div class="flex justify-between pt-4 border-t border-gray-200">
                        <div class="text-center">
                            <span class="text-lg font-bold text-[#2c5aa0] block">{{ $college->programs()->count() }}</span>
                            <span class="text-xs text-gray-600 block">Programs</span>
                        </div>
                        <div class="text-center">
                            <span class="text-lg font-bold text-[#2c5aa0] block">3.5K</span>
                            <span class="text-xs text-gray-600 block">Students</span>
                        </div>
                        <div class="text-center">
                            <span class="text-lg font-bold text-[#2c5aa0] block">{{ $college->established_year }}</span>
                            <span class="text-xs text-gray-600 block">Established</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            @endforelse
        </div>
        <div class="text-center mt-12">
            <a href="{{ route('institution.index') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold cursor-pointer transition-all bg-white text-[#4299e1] border-2 border-[#4299e1] hover:bg-[#4299e1]/10 hover:-translate-y-1">
                View All Colleges
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

<!-- Degrees/Certifications Section -->
<section class="py-20 px-4 bg-[#f7fafc]/80" id="degrees">
    <div class="container max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Degrees & Certifications</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Explore various academic and professional qualifications</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
            <!-- Degree 1 -->
            <div class="bg-white rounded-xl p-7 text-center shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl border border-gray-200 h-full">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl bg-[#4299e1]/10 text-[#4299e1]">
                    <i class="fas fa-laptop-code"></i>
                </div>
                <h3 class="text-lg text-gray-800 mb-2 font-semibold">BE Computer</h3>
                <p class="text-sm text-gray-600 mb-3">4 Years</p>
                <span class="inline-block px-3 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold">Bachelor</span>
            </div>
            <!-- Degree 2 -->
            <div class="bg-white rounded-xl p-7 text-center shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl border border-gray-200 h-full">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl bg-[#4299e1]/10 text-[#4299e1]">
                    <i class="fas fa-user-md"></i>
                </div>
                <h3 class="text-lg text-gray-800 mb-2 font-semibold">MBBS</h3>
                <p class="text-sm text-gray-600 mb-3">5.5 Years</p>
                <span class="inline-block px-3 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold">Medical</span>
            </div>
            <!-- Degree 3 -->
            <div class="bg-white rounded-xl p-7 text-center shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl border border-gray-200 h-full">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl bg-[#4299e1]/10 text-[#4299e1]">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-lg text-gray-800 mb-2 font-semibold">BBA/MBA</h3>
                <p class="text-sm text-gray-600 mb-3">4/2 Years</p>
                <span class="inline-block px-3 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold">Management</span>
            </div>
            <!-- Degree 4 -->
            <div class="bg-white rounded-xl p-7 text-center shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl border border-gray-200 h-full">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl bg-[#4299e1]/10 text-[#4299e1]">
                    <i class="fas fa-gavel"></i>
                </div>
                <h3 class="text-lg text-gray-800 mb-2 font-semibold">LLB</h3>
                <p class="text-sm text-gray-600 mb-3">3 Years</p>
                <span class="inline-block px-3 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold">Law</span>
            </div>
            <!-- Certification 1 -->
            <div class="bg-white rounded-xl p-7 text-center shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl border border-gray-200 h-full">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl bg-[#4299e1]/10 text-[#4299e1]">
                    <i class="fas fa-certificate"></i>
                </div>
                <h3 class="text-lg text-gray-800 mb-2 font-semibold">CCNA</h3>
                <p class="text-sm text-gray-600 mb-3">6 Months</p>
                <span class="inline-block px-3 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold">Certification</span>
            </div>
            <!-- Certification 2 -->
            <div class="bg-white rounded-xl p-7 text-center shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl border border-gray-200 h-full">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl bg-[#4299e1]/10 text-[#4299e1]">
                    <i class="fas fa-code"></i>
                </div>
                <h3 class="text-lg text-gray-800 mb-2 font-semibold">Full Stack</h3>
                <p class="text-sm text-gray-600 mb-3">1 Year</p>
                <span class="inline-block px-3 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold">Diploma</span>
            </div>
            <!-- Degree 5 -->
            <div class="bg-white rounded-xl p-7 text-center shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl border border-gray-200 h-full">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl bg-[#4299e1]/10 text-[#4299e1]">
                    <i class="fas fa-dna"></i>
                </div>
                <h3 class="text-lg text-gray-800 mb-2 font-semibold">BSc Nursing</h3>
                <p class="text-sm text-gray-600 mb-3">4 Years</p>
                <span class="inline-block px-3 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold">Science</span>
            </div>
            <!-- Certification 3 -->
            <div class="bg-white rounded-xl p-7 text-center shadow-lg transition-all hover:-translate-y-1 hover:shadow-xl border border-gray-200 h-full">
                <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl bg-[#4299e1]/10 text-[#4299e1]">
                    <i class="fas fa-paint-brush"></i>
                </div>
                <h3 class="text-lg text-gray-800 mb-2 font-semibold">Graphic Design</h3>
                <p class="text-sm text-gray-600 mb-3">1 Year</p>
                <span class="inline-block px-3 py-1.5 bg-teal-500/10 text-teal-500 rounded-full text-xs font-semibold">Diploma</span>
            </div>
        </div>
    </div>
</section>

<!-- Courses Section -->
<section class="py-24 px-4 bg-white" id="courses">
    <div class="container max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Popular Courses</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Explore trending courses across various disciplines</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            @forelse ($courses as $course)
            @php $primaryProgram = $course->programs->first(); @endphp
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200">
                <div class="p-7 pb-4 border-b border-gray-200">
                    <span class="inline-block px-4 py-1.5 bg-orange-500/10 text-orange-500 rounded-full text-xs font-semibold mb-4">{{ $primaryProgram?->affiliation?->name ?? '—' }}</span>
                    <h3 class="text-2xl text-[#2c5aa0] mb-2.5 leading-tight font-bold">{{ $course->display_name }}</h3>
                    <div class="flex gap-4 text-gray-600 text-sm">
                        <span><i class="fas fa-clock"></i> {{ $primaryProgram?->duration ?? '—' }}</span>
                        <span><i class="fas fa-university"></i> {{ $primaryProgram?->level?->name ?? '—' }}</span>
                    </div>
                </div>
                <div class="p-5 pt-5">
                    <p class="text-gray-600 mb-5 text-sm line-clamp-3">
                        {{ $course->description }}
                    </p>
                    <div class="flex justify-between items-center">
                        <!-- <div class="text-2xl font-bold text-[#2c5aa0] whitespace-nowrap">NPR 1,25,000<small class="text-sm text-gray-600 font-normal">/year</small></div> -->
                        <a href="{{ route('course.show', $course) }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl font-semibold cursor-pointer transition-all bg-[#4299e1] text-white text-sm hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-lg">Details</a>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
        </div>
    </div>
</section>

<!-- Scholarships Section -->
<section class="py-24 px-4 bg-[#f7fafc]/80" id="scholarships">
    <div class="container max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Available Scholarships</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Financial assistance for your educational journey</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            <!-- Scholarship 1 -->
            <div class="bg-white rounded-xl p-7 shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl relative overflow-hidden border border-gray-200">
                <div class="absolute top-5 right-[-30px] bg-orange-500 text-white px-8 py-1.5 text-xs font-semibold rotate-45">Merit-Based</div>
                <div class="text-4xl font-bold text-[#2c5aa0] mb-2">NPR 5,00,000</div>
                <h3 class="text-xl text-gray-800 mb-3 font-bold">Sikuna Excellence Scholarship</h3>
                <p class="text-gray-600 mb-5 text-sm">For students with outstanding academic records pursuing undergraduate programs.</p>
                <div class="flex justify-between items-center mb-5 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-2 text-orange-600 font-semibold text-sm">
                        <i class="fas fa-calendar-alt"></i>
                        <span>June 30, 2024</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-teal-600 font-semibold text-sm">
                        <i class="fas fa-users"></i>
                        <span class="text-teal-600 font-bold">25</span> / <span class="text-gray-600 font-normal">50</span>
                    </div>
                </div>
                <a href="#" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold cursor-pointer transition-all bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-lg w-full justify-center">Apply Now</a>
            </div>
            <!-- Scholarship 2 -->
            <div class="bg-white rounded-xl p-7 shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl relative overflow-hidden border border-gray-200">
                <div class="absolute top-5 right-[-30px] bg-orange-500 text-white px-8 py-1.5 text-xs font-semibold rotate-45">Need-Based</div>
                <div class="text-4xl font-bold text-[#2c5aa0] mb-2">Full Tuition</div>
                <h3 class="text-xl text-gray-800 mb-3 font-bold">Opportunity Grant</h3>
                <p class="text-gray-600 mb-5 text-sm">For economically disadvantaged students pursuing higher education.</p>
                <div class="flex justify-between items-center mb-5 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-2 text-orange-600 font-semibold text-sm">
                        <i class="fas fa-calendar-alt"></i>
                        <span>July 15, 2024</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-teal-600 font-semibold text-sm">
                        <i class="fas fa-users"></i>
                        <span class="text-teal-600 font-bold">15</span> / <span class="text-gray-600 font-normal">30</span>
                    </div>
                </div>
                <a href="#" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold cursor-pointer transition-all bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-lg w-full justify-center">Apply Now</a>
            </div>
            <!-- Scholarship 3 -->
            <div class="bg-white rounded-xl p-7 shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl relative overflow-hidden border border-gray-200">
                <div class="absolute top-5 right-[-30px] bg-orange-500 text-white px-8 py-1.5 text-xs font-semibold rotate-45">Women in Tech</div>
                <div class="text-4xl font-bold text-[#2c5aa0] mb-2">NPR 3,00,000</div>
                <h3 class="text-xl text-gray-800 mb-3 font-bold">Women in Technology</h3>
                <p class="text-gray-600 mb-5 text-sm">For female students pursuing degrees in computer science or engineering.</p>
                <div class="flex justify-between items-center mb-5 pt-4 border-t border-gray-200">
                    <div class="flex items-center gap-2 text-orange-600 font-semibold text-sm">
                        <i class="fas fa-calendar-alt"></i>
                        <span>August 10, 2024</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-teal-600 font-semibold text-sm">
                        <i class="fas fa-users"></i>
                        <span class="text-teal-600 font-bold">20</span> / <span class="text-gray-600 font-normal">40</span>
                    </div>
                </div>
                <a href="#" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold cursor-pointer transition-all bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-lg w-full justify-center">Apply Now</a>
            </div>
        </div>
    </div>
</section>

<!-- Activity & Forums Section -->
<section class="py-24 px-4 bg-white" id="activity">
    <div class="container max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Latest Updates & Discussions</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Stay updated with latest activities and join discussions</p>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Activity Feed -->
            <div class="bg-white rounded-xl p-7 shadow-lg border border-gray-200">
                <h3 class="text-[#2c5aa0] mb-5 pb-4 text-2xl font-bold border-b-2 border-gray-200">Latest Updates</h3>
                <!-- Activity Item 1 -->
                <div class="flex gap-4 p-5 border-b border-gray-200 transition-all hover:bg-[#f7fafc]/50">
                    <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Admission Open" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2.5">
                            <span class="font-bold text-[#2c5aa0] text-sm">Prime College</span>
                            <span class="text-xs text-gray-600">2 hours ago</span>
                        </div>
                        <p class="text-gray-800 mb-4 text-sm leading-relaxed">Admission open for BIM program for the academic year 2024. Last date for application: June 30, 2024.</p>
                        <div class="flex gap-5 text-xs text-gray-600">
                            <span class="cursor-pointer"><i class="fas fa-heart text-[#4299e1]"></i> 45</span>
                            <span><i class="fas fa-comment"></i> 12</span>
                            <span><i class="fas fa-share"></i> 5</span>
                        </div>
                    </div>
                </div>
                <!-- Activity Item 2 -->
                <div class="flex gap-4 p-5 border-b border-gray-200 transition-all hover:bg-[#f7fafc]/50">
                    <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0">
                        <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Cultural Festival" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2.5">
                            <span class="font-bold text-[#2c5aa0] text-sm">St. Xavier's College</span>
                            <span class="text-xs text-gray-600">1 day ago</span>
                        </div>
                        <p class="text-gray-800 mb-4 text-sm leading-relaxed">Annual cultural festival "Xavotsav 2024" scheduled for July 15-20. Registrations open now.</p>
                        <div class="flex gap-5 text-xs text-gray-600">
                            <span class="cursor-pointer"><i class="fas fa-heart text-[#4299e1]"></i> 89</span>
                            <span><i class="fas fa-comment"></i> 23</span>
                            <span><i class="fas fa-share"></i> 8</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Forums Section -->
            <div class="bg-white rounded-xl p-7 shadow-lg border border-gray-200">
                <h3 class="text-[#2c5aa0] mb-5 pb-4 text-2xl font-bold border-b-2 border-gray-200">Discussion Forums</h3>
                <div class="flex flex-col gap-5">
                    <!-- Forum 1 -->
                    <div class="p-5 bg-[#f7fafc]/50 rounded-xl transition-all hover:bg-white hover:shadow-md border border-gray-200">
                        <div class="flex items-center gap-3 mb-2.5">
                            <i class="fas fa-graduation-cap text-[#4299e1] text-xl"></i>
                            <span class="font-semibold text-[#2c5aa0] text-base">Admissions & Applications</span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm">Discuss admission processes, requirements, and share experiences.</p>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>1,245 Topics</span>
                            <span>5,678 Posts</span>
                        </div>
                    </div>
                    <!-- Forum 2 -->
                    <div class="p-5 bg-[#f7fafc]/50 rounded-xl transition-all hover:bg-white hover:shadow-md border border-gray-200">
                        <div class="flex items-center gap-3 mb-2.5">
                            <i class="fas fa-book text-[#4299e1] text-xl"></i>
                            <span class="font-semibold text-[#2c5aa0] text-base">Study Help & Resources</span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm">Get help with coursework and share study materials.</p>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>890 Topics</span>
                            <span>3,456 Posts</span>
                        </div>
                    </div>
                    <!-- Forum 3 -->
                    <div class="p-5 bg-[#f7fafc]/50 rounded-xl transition-all hover:bg-white hover:shadow-md border border-gray-200">
                        <div class="flex items-center gap-3 mb-2.5">
                            <i class="fas fa-rupee-sign text-[#4299e1] text-xl"></i>
                            <span class="font-semibold text-[#2c5aa0] text-base">Scholarships & Financial Aid</span>
                        </div>
                        <p class="text-gray-600 mb-4 text-sm">Discuss scholarship opportunities and financial aid options.</p>
                        <div class="flex justify-between text-xs text-gray-600">
                            <span>567 Topics</span>
                            <span>2,345 Posts</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blogs Section -->
<section class="py-24 px-4 bg-[#f7fafc]/80" id="blogs">
    <div class="container max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Latest Blogs</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">Insights, tips, and news about education</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            <!-- Blog 1 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Education in Nepal" class="w-full h-full object-cover transition-all hover:scale-105">
                </div>
                <div class="p-7">
                    <span class="inline-block px-4 py-1.5 bg-orange-500/10 text-orange-500 rounded-full text-xs font-semibold mb-4">Career Guidance</span>
                    <h3 class="text-xl text-[#2c5aa0] mb-2.5 leading-tight font-bold">Top Emerging Careers in Nepal for 2024</h3>
                    <p class="text-gray-600 mb-5 text-sm line-clamp-3">Discover the fastest growing career opportunities in Nepal's evolving job market.</p>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-2.5">
                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Author" class="w-7 h-7 rounded-full object-cover">
                            <span class="text-xs text-gray-800">Rajesh Thapa</span>
                        </div>
                        <span class="text-xs text-gray-600">May 15, 2024</span>
                    </div>
                </div>
            </div>
            <!-- Blog 2 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1523580494863-6f3031224c94?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Scholarship Tips" class="w-full h-full object-cover transition-all hover:scale-105">
                </div>
                <div class="p-7">
                    <span class="inline-block px-4 py-1.5 bg-orange-500/10 text-orange-500 rounded-full text-xs font-semibold mb-4">Scholarships</span>
                    <h3 class="text-xl text-[#2c5aa0] mb-2.5 leading-tight font-bold">How to Write a Winning Scholarship Essay</h3>
                    <p class="text-gray-600 mb-5 text-sm line-clamp-3">Learn the secrets to crafting compelling scholarship essays that increase your chances.</p>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-2.5">
                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Author" class="w-7 h-7 rounded-full object-cover">
                            <span class="text-xs text-gray-800">Sunita Rai</span>
                        </div>
                        <span class="text-xs text-gray-600">May 10, 2024</span>
                    </div>
                </div>
            </div>
            <!-- Blog 3 -->
            <div class="bg-white rounded-xl overflow-hidden shadow-lg transition-all hover:-translate-y-1.5 hover:shadow-2xl border border-gray-200">
                <div class="h-48 overflow-hidden">
                    <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Study Tips" class="w-full h-full object-cover transition-all hover:scale-105">
                </div>
                <div class="p-7">
                    <span class="inline-block px-4 py-1.5 bg-orange-500/10 text-orange-500 rounded-full text-xs font-semibold mb-4">Study Tips</span>
                    <h3 class="text-xl text-[#2c5aa0] mb-2.5 leading-tight font-bold">Effective Study Techniques for Students</h3>
                    <p class="text-gray-600 mb-5 text-sm line-clamp-3">Proven study methods and time management strategies to help you excel academically.</p>
                    <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                        <div class="flex items-center gap-2.5">
                            <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Author" class="w-7 h-7 rounded-full object-cover">
                            <span class="text-xs text-gray-800">Amit Sharma</span>
                        </div>
                        <span class="text-xs text-gray-600">May 5, 2024</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section - 6 Cards -->
<section class="py-24 px-4 bg-white" id="features">
    <div class="container max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl text-[#2c5aa0] mb-4 font-bold relative inline-block after:content-[''] after:absolute after:-bottom-2.5 after:left-1/2 after:-translate-x-1/2 after:w-20 after:h-1 after:bg-[#4299e1] after:rounded">Why Choose Sikuna?</h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">We provide comprehensive educational solutions to help you achieve your goals</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-7">
            <div class="bg-white rounded-xl p-10 text-center shadow-lg transition-all hover:-translate-y-2.5 hover:shadow-2xl border border-gray-200">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl bg-[#4299e1]/10 text-[#4299e1]">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-2xl text-[#2c5aa0] mb-4 font-bold">Find the Right Fit</h3>
                <p class="text-gray-600">Our advanced search helps you find courses and institutions that match your interests, budget, and career goals.</p>
            </div>
            <div class="bg-white rounded-xl p-10 text-center shadow-lg transition-all hover:-translate-y-2.5 hover:shadow-2xl border border-gray-200">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl bg-teal-500/10 text-teal-500">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h3 class="text-2xl text-[#2c5aa0] mb-4 font-bold">Expert Guidance</h3>
                <p class="text-gray-600">Get personalized guidance from our education experts to make informed decisions about your future.</p>
            </div>
            <div class="bg-white rounded-xl p-10 text-center shadow-lg transition-all hover:-translate-y-2.5 hover:shadow-2xl border border-gray-200">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl bg-orange-500/10 text-orange-500">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <h3 class="text-2xl text-[#2c5aa0] mb-4 font-bold">Scholarship Assistance</h3>
                <p class="text-gray-600">Access thousands of scholarship opportunities and get help with applications to fund your education.</p>
            </div>
            <div class="bg-white rounded-xl p-10 text-center shadow-lg transition-all hover:-translate-y-2.5 hover:shadow-2xl border border-gray-200">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl bg-[#2c5aa0]/10 text-[#2c5aa0]">
                    <i class="fas fa-handshake"></i>
                </div>
                <h3 class="text-2xl text-[#2c5aa0] mb-4 font-bold">Trusted Partners</h3>
                <p class="text-gray-600">We work with accredited institutions and universities to provide quality education options.</p>
            </div>
            <div class="bg-white rounded-xl p-10 text-center shadow-lg transition-all hover:-translate-y-2.5 hover:shadow-2xl border border-gray-200">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl bg-[#667eea]/10 text-[#667eea]">
                    <i class="fas fa-network-wired"></i>
                </div>
                <h3 class="text-2xl text-[#2c5aa0] mb-4 font-bold">Wide Network</h3>
                <p class="text-gray-600">Associated with almost every educational institution across Nepal for comprehensive coverage.</p>
            </div>
            <div class="bg-white rounded-xl p-10 text-center shadow-lg transition-all hover:-translate-y-2.5 hover:shadow-2xl border border-gray-200">
                <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-3xl bg-[#f6ad55]/10 text-[#f6ad55]">
                    <i class="fas fa-user-clock"></i>
                </div>
                <h3 class="text-2xl text-[#2c5aa0] mb-4 font-bold">24/7 Support</h3>
                <p class="text-gray-600">Round-the-clock support system to help you with queries, applications, and guidance anytime.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="py-20 px-4 bg-gradient-to-br from-[#2c5aa0] to-[#1a365d] text-white">
    <div class="container max-w-7xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-7">
            <div class="text-center">
                <div class="text-4xl mb-5 text-[#4299e1]">
                    <i class="fas fa-users"></i>
                </div>
                <div class="text-4xl md:text-5xl font-bold mb-2.5">50,000+</div>
                <div class="text-lg opacity-90">Students Enrolled</div>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-5 text-[#4299e1]">
                    <i class="fas fa-university"></i>
                </div>
                <div class="text-4xl md:text-5xl font-bold mb-2.5">100+</div>
                <div class="text-lg opacity-90">Partner Institutions</div>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-5 text-[#4299e1]">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="text-4xl md:text-5xl font-bold mb-2.5">5,000+</div>
                <div class="text-lg opacity-90">Courses Offered</div>
            </div>
            <div class="text-center">
                <div class="text-4xl mb-5 text-[#4299e1]">
                    <i class="fas fa-rupee-sign"></i>
                </div>
                <div class="text-4xl md:text-5xl font-bold mb-2.5">NPR 5Cr+</div>
                <div class="text-lg opacity-90">Scholarships Awarded</div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('page-specific-script')
<script>
    // Coming Soon Modal functionality
    (function() {
        // Get modal elements
        const modal = document.getElementById('comingSoonModal');
        const closeBtn = document.getElementById('modalClose');
        const okBtn = document.getElementById('modalOkBtn');

        // Close modal function
        function hideModal() {
            if (modal) modal.classList.add('hidden');
        }

        // Show modal function
        function showModal(e) {
            if (e) e.preventDefault();
            if (modal) modal.classList.remove('hidden');
        }

        // Add event listeners
        if (closeBtn) closeBtn.addEventListener('click', hideModal);
        if (okBtn) okBtn.addEventListener('click', hideModal);

        // Click outside to close
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) hideModal();
            });
        }

        // Show modal on page load (remove this if you don't want it to show automatically)
        setTimeout(showModal, 1000);
    })();
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchForm = document.getElementById('searchForm');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const keyword = document.getElementById('searchKeyword').value;
                const program = document.getElementById('searchProgram').value;
                const level = document.getElementById('searchLevel').value;
                const params = new URLSearchParams();
                if (keyword) params.append('search', keyword);
                if (program) params.append('program', program);
                if (level) params.append('level', level);
                window.location.href = "{{ route('course') }}" + (params.toString() ? '?' + params.toString() : '');
            });
        }
    });
</script>
@endsection
