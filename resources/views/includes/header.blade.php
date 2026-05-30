<header class="fixed top-0 left-0 w-full z-[1000]" id="header">
    {{-- Move x-data to sticky container so both button and full-width dropdown can share state --}}
    <div class="w-full bg-white shadow-[0_2px_15px_rgba(0,0,0,0.1)] border-b border-gray-200 relative"
         x-data="{ mobileOpen: false, accountOpen: false }" @click.outside="accountOpen = false; mobileOpen = false">

        <div class="container max-w-7xl mx-auto py-3 px-4">
            <div class="flex items-center justify-between">

                {{-- LOGO --}}
                <a href="{{ route('website.home') }}" class="flex items-center font-semibold group no-underline shrink-0">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="{{ config('app.name') }}" class="h-16 w-auto group-hover:animate-pulse">
                </a>

                {{-- DESKTOP NAV --}}
                <nav class="hidden md:flex items-center gap-6">
                    @foreach([
                        ['Home', 'website.home', 'website.home'],
                        ['Institutions', 'website.institutions.index', 'website.institutions.*'],
                        ['Programs', 'website.programs.index', 'website.programs.*'],
                        ['Scholarships', 'website.scholarships.index', 'website.scholarships.*'],
                        ['Blog', 'website.posts.index', 'website.posts.*'],
                    ] as [$label, $route, $pattern])
                    <a href="{{ route($route) }}"
                       class="text-[15px] py-2 transition-colors no-underline whitespace-nowrap
                           {{ request()->routeIs($pattern) ? 'text-[#4299e1] font-semibold border-b-2 border-[#4299e1]' : 'text-[#2d3748] hover:text-[#4299e1]' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </nav>

                {{-- RIGHT: Auth / Account --}}
                <div class="flex items-center gap-3">
                    <div class="hidden md:flex items-center gap-3">

                        @guest('student')
                        @php $onLogin = request()->routeIs('student.login'); @endphp
                        <a href="{{ route('student.login') }}"
                           class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-semibold text-sm transition-all no-underline hover:-translate-y-0.5
                               {{ $onLogin ? 'bg-[#4299e1] text-white hover:bg-[#2c5aa0] shadow-md' : 'border-2 border-[#4299e1] bg-white text-[#4299e1] hover:bg-[#4299e1]/10' }}">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-semibold text-sm transition-all no-underline hover:-translate-y-0.5
                               {{ $onLogin ? 'border-2 border-[#4299e1] bg-white text-[#4299e1] hover:bg-[#4299e1]/10' : 'bg-[#4299e1] text-white hover:bg-[#2c5aa0] shadow-md' }}">
                            Register
                        </a>

                        @else
                        @php $student = Auth::guard('student')->user(); @endphp
                        {{-- Account button --}}
                        <button @click="accountOpen = !accountOpen"
                            class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-gray-50 border border-transparent hover:border-gray-200 transition-all focus:outline-none">
                            @if($student->avatar)
                                <img src="{{ Storage::url($student->avatar) }}" class="w-8 h-8 rounded-full object-cover shrink-0">
                            @else
                                <div class="w-8 h-8 rounded-full bg-[#ebf8ff] flex items-center justify-center shrink-0">
                                    <span class="text-[#2c5aa0] text-sm font-bold">{{ strtoupper(substr($student->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <span class="text-sm font-semibold text-[#2d3748] max-w-[120px] truncate hidden lg:block">
                                {{ Str::limit($student->name, 15) }}
                            </span>
                            <i class="fas text-xs text-gray-400 transition-transform duration-200 hidden md:block"
                               :class="accountOpen ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                        @endguest

                    </div>

                    {{-- Mobile hamburger --}}
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">
                        <i x-show="!mobileOpen" class="fas fa-bars text-lg"></i>
                        <i x-show="mobileOpen" class="fas fa-times text-lg" x-cloak></i>
                    </button>
                </div>

            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════════════════
             LARGE SCREEN - Full-width account dropdown (lg+)
             Positioned below the header bar, full container width
        ═══════════════════════════════════════════════════════════════ --}}
        @auth('student')
        @php
            $authStudent = Auth::guard('student')->user();
            $menuGroups = [
                'My Account' => [
                    ['fas fa-tachometer-alt', 'Dashboard', 'student.dashboard', '#4299e1'],
                    ['fas fa-user', 'Profile', 'student.profile.index', '#4299e1'],
                    ['fas fa-cog', 'Settings', 'student.settings.index', '#718096'],
                ],
                'Applications' => [
                    ['fas fa-file-alt', 'Applications', 'student.applications.index', '#4299e1'],
                    ['fas fa-graduation-cap', 'Scholarships', 'student.scholarship-applications.index', '#805ad5'],
                    ['fas fa-coins', 'Cashbacks', 'student.cashbacks.index', '#38a169'],
                ],
                'Discovery' => [
                    ['fas fa-heart', 'Favorites', 'student.favorites.index', '#e53e3e'],
                    ['fas fa-balance-scale', 'Compare', 'website.compare.index', '#d69e2e'],
                    ['fas fa-star', 'Recommendations', 'student.recommendations.index', '#d69e2e'],
                ],
                'Services' => [
                    ['fas fa-comments', 'Inquiries', 'student.inquiries.index', '#3182ce'],
                    ['fas fa-calendar', 'Counseling', 'student.counseling-sessions.index', '#319795'],
                    ['fas fa-star-half-alt', 'Reviews', 'student.reviews.index', '#d69e2e'],
                ],
                'Communication' => [
                    ['fas fa-comment-dots', 'Conversations', 'student.conversations.index', '#2b6cb0'],
                    ['fas fa-envelope', 'Messages', 'student.messages.index', '#2b6cb0'],
                ],
            ];
        @endphp

        <div x-show="accountOpen" x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             class="absolute left-0 right-0 top-full bg-white border-t-2 border-[#4299e1]/20 shadow-[0_8px_30px_rgba(0,0,0,0.12)] z-50">

            {{-- md+: full-width mega panel --}}
            <div class="hidden md:block">
                <div class="container max-w-7xl mx-auto px-4 py-6">
                    <div class="flex gap-8">

                        {{-- Student info panel --}}
                        <div class="w-56 shrink-0">
                            <div class="flex items-center gap-3 p-4 bg-gradient-to-br from-[#2c5aa0] to-[#4299e1] rounded-xl text-white mb-3">
                                @if($authStudent->avatar)
                                    <img src="{{ Storage::url($authStudent->avatar) }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-white/30 shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center ring-2 ring-white/30 shrink-0">
                                        <span class="text-xl font-bold">{{ strtoupper(substr($authStudent->name, 0, 1)) }}</span>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="text-sm font-bold truncate">{{ $authStudent->name }}</p>
                                    <p class="text-xs text-white/70 truncate">{{ $authStudent->email }}</p>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('student.logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-sm font-semibold text-red-600 border border-red-200 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt"></i> Sign Out
                                </button>
                            </form>
                        </div>

                        {{-- Links row - each group is a flex column, all groups in one row --}}
                        <div class="flex-1 grid grid-cols-3 gap-6">
                            @foreach($menuGroups as $group => $links)
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 px-2">{{ $group }}</p>
                                <ul class="space-y-0.5">
                                    @foreach($links as [$icon, $label, $route, $color])
                                    <li>
                                        <a href="{{ route($route) }}"
                                           class="flex items-center gap-2.5 px-2 py-2 rounded-lg text-sm text-gray-600 hover:text-[#2c5aa0] hover:bg-[#ebf8ff] transition-colors no-underline {{ request()->routeIs($route) ? 'bg-[#ebf8ff] text-[#2c5aa0] font-semibold' : '' }}"
                                           @click="accountOpen = false">
                                            <i class="{{ $icon }} w-4 text-center text-xs shrink-0" style="color: {{ $color }}"></i>
                                            <span class="truncate">{{ $label }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

        </div>
        @endauth

        {{-- ═══════════════════════════════════════════════════════════════
             MOBILE DRAWER (full screen overlay)
        ═══════════════════════════════════════════════════════════════ --}}
        <div x-show="mobileOpen" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-4"
             class="absolute left-0 right-0 top-full bg-white shadow-xl z-50 max-h-[calc(100vh-80px)] overflow-y-auto md:hidden">
            <div class="px-4 py-5 space-y-1">

                {{-- Main nav --}}
                @foreach([
                    ['Home', 'website.home', 'website.home'],
                    ['Institutions', 'website.institutions.index', 'website.institutions.*'],
                    ['Programs', 'website.programs.index', 'website.programs.*'],
                    ['Scholarships', 'website.scholarships.index', 'website.scholarships.*'],
                    ['Blog', 'website.posts.index', 'website.posts.*'],
                ] as [$label, $route, $pattern])
                <a href="{{ route($route) }}" @click="mobileOpen = false"
                   class="block py-3 px-4 rounded-xl text-[15px] transition-all no-underline
                       {{ request()->routeIs($pattern) ? 'text-[#4299e1] font-semibold bg-[#ebf8ff] border-l-4 border-[#4299e1]' : 'text-[#2d3748] hover:text-[#4299e1] hover:bg-[#ebf8ff]' }}">
                    {{ $label }}
                </a>
                @endforeach

                <div class="border-t border-gray-100 pt-4 mt-4">
                    @guest('student')
                    <div class="space-y-3 px-2">
                        <a href="{{ route('student.login') }}"
                           class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm no-underline border-2 border-[#4299e1] text-[#4299e1] hover:bg-[#4299e1]/10 transition">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                        <a href="{{ route('register') }}"
                           class="flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm no-underline bg-[#4299e1] text-white hover:bg-[#2c5aa0] shadow-md transition">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    </div>

                    @else
                    @php $authStudent = Auth::guard('student')->user(); @endphp

                    {{-- Student info --}}
                    <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-[#2c5aa0] to-[#4299e1] rounded-xl text-white mb-4 mx-2">
                        @if($authStudent->avatar)
                            <img src="{{ Storage::url($authStudent->avatar) }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-white/30 shrink-0">
                        @else
                            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                                <span class="font-bold">{{ strtoupper(substr($authStudent->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="min-w-0">
                            <p class="text-sm font-bold truncate">{{ $authStudent->name }}</p>
                            <p class="text-xs text-white/70">Student</p>
                        </div>
                    </div>

                    {{-- All student links grouped --}}
                    @php
                    $mobileGroups = [
                        [['fas fa-tachometer-alt', 'Dashboard', 'student.dashboard'], ['fas fa-user', 'Profile', 'student.profile.index'], ['fas fa-cog', 'Settings', 'student.settings.index']],
                        [['fas fa-file-alt', 'Applications', 'student.applications.index'], ['fas fa-graduation-cap', 'Scholarships', 'student.scholarship-applications.index'], ['fas fa-coins', 'Cashbacks', 'student.cashbacks.index']],
                        [['fas fa-heart', 'Favorites', 'student.favorites.index'], ['fas fa-balance-scale', 'Compare', 'website.compare.index'], ['fas fa-star', 'Recommendations', 'student.recommendations.index']],
                        [['fas fa-comments', 'Inquiries', 'student.inquiries.index'], ['fas fa-calendar', 'Counseling', 'student.counseling-sessions.index'], ['fas fa-star-half-alt', 'Reviews', 'student.reviews.index']],
                        [['fas fa-comment-dots', 'Conversations', 'student.conversations.index'], ['fas fa-envelope', 'Messages', 'student.messages.index']],
                    ];
                    @endphp

                    @foreach($mobileGroups as $group)
                    @foreach($group as [$icon, $label, $route])
                    <a href="{{ route($route) }}" @click="mobileOpen = false"
                       class="flex items-center gap-3 py-3 px-4 rounded-xl text-[15px] transition-all no-underline
                           {{ request()->routeIs($route) ? 'text-[#4299e1] font-semibold bg-[#ebf8ff]' : 'text-[#2d3748] hover:text-[#4299e1] hover:bg-[#ebf8ff]' }}">
                        <i class="{{ $icon }} text-[#4299e1] w-5 text-center"></i>
                        {{ $label }}
                    </a>
                    @endforeach
                    @if(!$loop->last)
                    <div class="border-t border-gray-100 my-1"></div>
                    @endif
                    @endforeach

                    <div class="border-t border-gray-100 mt-3 pt-3">
                        <form method="POST" action="{{ route('student.logout') }}" class="px-2">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-xl font-semibold text-sm border-2 border-red-400 text-red-600 hover:bg-red-50 transition">
                                <i class="fas fa-sign-out-alt"></i> Sign Out
                            </button>
                        </form>
                    </div>

                    @endguest
                </div>
            </div>
        </div>

    </div>
</header>
