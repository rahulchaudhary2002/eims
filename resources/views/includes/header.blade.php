<header class="fixed top-0 left-0 w-full z-[1000] bg-white shadow-[0_2px_15px_rgba(0,0,0,0.1)] transition-all duration-300" id="header">
    <!-- Top Header / main bar – sticky within header, border-b -->
    <div class="sticky top-0 w-full bg-white z-40 shadow-sm border-b border-gray-200">
        <div class="container max-w-7xl mx-auto py-3 px-4">
            <!-- Main flex row: x-data for mobile toggle -->
            <div class="flex items-center justify-between relative" x-data="{ mobileOpen: false }">

                <!-- ----- LOGO (left) ----- -->
                <div class="flex items-center justify-center">
                    <!-- route: home -->
                    <a href="{{ route('home') }}" class="flex items-center font-semibold group no-underline">
                        <i class="fas fa-graduation-cap mr-2 text-[#4299e1] text-3xl"></i>
                        <span class="text-[#2c5aa0] text-3xl">Sikuna.com</span>
                    </a>
                </div>

                <!-- ----- CENTER NAVIGATION (desktop) – hidden on mobile, flex on md+ ----- -->
                <div class="hidden md:flex flex-1 max-w-md mx-8">
                    <div class="container max-w-7xl mx-auto px-4">
                        <div class="flex justify-center">
                            <!-- nav items using routes (simulated as plain links for demo, but with route classes) -->
                            <ul class="flex space-x-6 py-2">
                                <li>
                                    <a href="{{ route('home') }}"
                                        class="text-base py-2 {{ request()->routeIs('home') ? 'text-[#4299e1] font-semibold border-b-2 border-[#4299e1]' : 'text-[#2d3748] hover:text-[#4299e1]' }} transition-colors no-underline">
                                        Home
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('college') }}"
                                        class="text-base py-2 {{ request()->routeIs('college') ? 'text-[#4299e1] font-semibold border-b-2 border-[#4299e1]' : 'text-[#2d3748] hover:text-[#4299e1]' }} transition-colors no-underline">
                                        Colleges
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('school') }}"
                                        class="text-base py-2 {{ request()->routeIs('school') ? 'text-[#4299e1] font-semibold border-b-2 border-[#4299e1]' : 'text-[#2d3748] hover:text-[#4299e1]' }} transition-colors no-underline">
                                        Schools
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('course') }}"
                                        class="text-base py-2 {{ request()->routeIs('course') ? 'text-[#4299e1] font-semibold border-b-2 border-[#4299e1]' : 'text-[#2d3748] hover:text-[#4299e1]' }} transition-colors no-underline">
                                        Courses
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('forum.question.index') }}"
                                        class="text-base py-2 {{ request()->routeIs('forum.question.index') ? 'text-[#4299e1] font-semibold border-b-2 border-[#4299e1]' : 'text-[#2d3748] hover:text-[#4299e1]' }} transition-colors no-underline">
                                        Forums
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('event.index') }}"
                                        class="text-base py-2 {{ request()->routeIs('event.index') ? 'text-[#4299e1] font-semibold border-b-2 border-[#4299e1]' : 'text-[#2d3748] hover:text-[#4299e1]' }} transition-colors no-underline">
                                        Events
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- ----- RIGHT SECTION: desktop auth & mobile button ----- -->
                <div class="flex items-center space-x-4">
                    <!-- Desktop Authentication (hidden on mobile, flex on md) -->
                    <div class="hidden md:flex items-center space-x-4">
                        @guest
                        <a href="{{ route('login') }}"
                            class="inline-flex items-center gap-2 px-[25px] py-3 rounded-[12px] font-semibold cursor-pointer transition-all duration-300 {{ request()->routeIs('login') ? 'border-none bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-[0_6px_15px_rgba(66,153,225,0.3)] text-[1rem] no-underline' : 'border-2 border-[#4299e1] bg-white text-[#4299e1] hover:bg-[rgba(66,153,225,0.1)] hover:-translate-y-1 text-[1rem] no-underline'}}">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="inline-flex items-center gap-2 px-[25px] py-3 rounded-[12px] font-semibold cursor-pointer transition-all duration-300 {{ request()->routeIs('register') || !request()->routeIs('login') ? 'border-none bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-[0_6px_15px_rgba(66,153,225,0.3)] text-[1rem] no-underline' : 'border-2 border-[#4299e1] bg-white text-[#4299e1] hover:bg-[rgba(66,153,225,0.1)] hover:-translate-y-1 text-[1rem] no-underline'}}">
                            Register
                        </a>
                        @else
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" @click.outside="open = false"
                                class="flex items-center gap-2 text-[#2d3748] hover:text-[#4299e1] focus:outline-none font-semibold">
                                <i class="fas fa-user-circle text-[1.5rem] text-[#4299e1]"></i>
                                <span class="max-w-[120px] truncate">
                                    {{ Str::limit(Auth::user()->name, 15) }}
                                </span>
                                <i class="fas fa-chevron-down text-[0.8rem]" x-show="!open"></i>
                                <i class="fas fa-chevron-up text-[0.8rem]" x-show="open" x-cloak></i>
                            </button>
                            <div x-show="open" x-transition @click.outside="open = false"
                                class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50 py-1"
                                x-cloak>
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-[#2d3748] hover:bg-[rgba(66,153,225,0.1)] hover:text-[#4299e1] transition-colors no-underline">
                                    <i class="fas fa-user w-4 h-4"></i> Profile
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                        <i class="fas fa-sign-out-alt w-4 h-4"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endguest
                    </div>

                    <!-- Mobile Menu Toggle Button (visible md:hidden) -->
                    <button @click="mobileOpen = !mobileOpen" class="flex items-center md:hidden p-4 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 focus:outline-none">
                        <i x-show="!mobileOpen" class="fas fa-bars"></i>
                        <i x-show="mobileOpen" class="fas fa-times" x-cloak></i>
                    </button>
                </div>

                <!-- ----- MOBILE DROPDOWN (full width, animated with desktop-like styling) ----- -->
                <div x-show="mobileOpen"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-4"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-4"
                    @click.outside="mobileOpen = false"
                    class="fixed top-[70px] left-0 w-full h-[calc(100vh-70px)] bg-white z-50 overflow-y-auto md:hidden"
                    x-cloak>
                    <div class="container max-w-7xl mx-auto px-4 py-6">
                        <!-- Mobile Nav Links – EXACTLY like desktop styling -->
                        <div class="flex flex-col space-y-1">
                            <a href="{{ route('home') }}"
                                class="text-base py-3 px-4 {{ request()->routeIs('home') ? 'text-[#4299e1] font-semibold border-l-4 border-[#4299e1] bg-[rgba(66,153,225,0.05)]' : 'text-[#2d3748] hover:text-[#4299e1] hover:bg-[rgba(66,153,225,0.05)]' }} transition-all duration-200 no-underline block">
                                Home
                            </a>
                            <a href="{{ route('college') }}"
                                class="text-base py-3 px-4 {{ request()->routeIs('college') ? 'text-[#4299e1] font-semibold border-l-4 border-[#4299e1] bg-[rgba(66,153,225,0.05)]' : 'text-[#2d3748] hover:text-[#4299e1] hover:bg-[rgba(66,153,225,0.05)]' }} transition-all duration-200 no-underline block">
                                Colleges
                            </a>
                            <a href="{{ route('school') }}"
                                class="text-base py-3 px-4 {{ request()->routeIs('school') ? 'text-[#4299e1] font-semibold border-l-4 border-[#4299e1] bg-[rgba(66,153,225,0.05)]' : 'text-[#2d3748] hover:text-[#4299e1] hover:bg-[rgba(66,153,225,0.05)]' }} transition-all duration-200 no-underline block">
                                Schools
                            </a>
                            <a href="{{ route('course') }}"
                                class="text-base py-3 px-4 {{ request()->routeIs('course') ? 'text-[#4299e1] font-semibold border-l-4 border-[#4299e1] bg-[rgba(66,153,225,0.05)]' : 'text-[#2d3748] hover:text-[#4299e1] hover:bg-[rgba(66,153,225,0.05)]' }} transition-all duration-200 no-underline block">
                                Courses
                            </a>
                            <a href="{{ route('forum.question.index') }}"
                                class="text-base py-3 px-4 {{ request()->routeIs('forum.question.index') ? 'text-[#4299e1] font-semibold border-l-4 border-[#4299e1] bg-[rgba(66,153,225,0.05)]' : 'text-[#2d3748] hover:text-[#4299e1] hover:bg-[rgba(66,153,225,0.05)]' }} transition-all duration-200 no-underline block">
                                Forums
                            </a>
                            <a href="{{ route('event.index') }}"
                                class="text-base py-3 px-4 {{ request()->routeIs('event.index') ? 'text-[#4299e1] font-semibold border-l-4 border-[#4299e1] bg-[rgba(66,153,225,0.05)]' : 'text-[#2d3748] hover:text-[#4299e1] hover:bg-[rgba(66,153,225,0.05)]' }} transition-all duration-200 no-underline block">
                                Events
                            </a>
                        </div>

                        <div class="border-t border-gray-200 my-6"></div>

                        <!-- Mobile Auth Links – EXACTLY like desktop button styling -->
                        @guest
                        <div class="flex flex-col space-y-3 px-4">
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center gap-2 px-[25px] py-3 rounded-[12px] font-semibold cursor-pointer transition-all duration-300 text-center no-underline
                                {{ request()->routeIs('login') ? 'border-none bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-[0_6px_15px_rgba(66,153,225,0.3)] text-[1rem]' : 'border-2 border-[#4299e1] bg-white text-[#4299e1] hover:bg-[rgba(66,153,225,0.1)] hover:-translate-y-1 text-[1rem]' }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                            <a href="{{ route('register') }}"
                                class="inline-flex items-center justify-center gap-2 px-[25px] py-3 rounded-[12px] font-semibold cursor-pointer transition-all duration-300 text-center no-underline
                                {{ request()->routeIs('register') || !request()->routeIs('login') ? 'border-none bg-[#4299e1] text-white hover:bg-[#2c5aa0] hover:-translate-y-1 hover:shadow-[0_6px_15px_rgba(66,153,225,0.3)] text-[1rem]' : 'border-2 border-[#4299e1] bg-white text-[#4299e1] hover:bg-[rgba(66,153,225,0.1)] hover:-translate-y-1 text-[1rem]' }}">
                                <i class="fas fa-user-plus"></i> Register
                            </a>
                        </div>
                        @else
                        <div class="flex flex-col space-y-3 px-4">
                            <div class="flex items-center gap-3 px-4 py-3 bg-gray-50 rounded-lg mb-2">
                                <i class="fas fa-user-circle text-[2rem] text-[#4299e1]"></i>
                                <div class="flex flex-col">
                                    <span class="text-sm text-gray-600">Welcome back,</span>
                                    <span class="font-semibold text-[#2d3748]">{{ Auth::user()->name }}</span>
                                </div>
                            </div>
                            <a href="{{ route('profile.edit') }}"
                                class="inline-flex items-center justify-center gap-2 px-[25px] py-3 rounded-[12px] font-semibold cursor-pointer transition-all duration-300 text-center no-underline border-2 border-[#4299e1] bg-white text-[#4299e1] hover:bg-[rgba(66,153,225,0.1)] hover:-translate-y-1">
                                <i class="fas fa-user"></i> Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-[25px] py-3 rounded-[12px] font-semibold cursor-pointer transition-all duration-300 border-2 border-red-500 bg-white text-red-500 hover:bg-red-50 hover:-translate-y-1">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                        @endguest
                    </div>
                </div>
                <!-- end mobile menu -->
            </div>
            <!-- end flex row -->
        </div>
    </div>
    <!-- end top header -->
</header>