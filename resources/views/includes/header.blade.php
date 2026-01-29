@php
use Illuminate\Support\Facades\Auth;

$user = Auth::user();
$currentRoute = request()->route()?->getName();

$isActive = function ($routeName) use ($currentRoute) {
return $currentRoute === $routeName
? 'text-blue-500 font-semibold border-b-2 border-blue-500'
: 'text-gray-700 hover:text-blue-500';
};
@endphp

<!-- Top Header -->
<div class="sticky top-0 w-full bg-white z-40 shadow-sm border-b">
    <div class="container max-w-7xl mx-auto py-3 px-4">
        <div class="flex items-center justify-between relative" x-data="{ mobileOpen: false }">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-500">
                    EIMS
                </a>
            </div>

            <div class="flex flex-1 max-w-md mx-8">
                <form action="#" method="GET" class="relative w-full">
                    <x-lucide-search class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
                    <input
                        type="text"
                        placeholder="Search courses, schools, colleges..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg outline-none transition-colors" />
                </form>
            </div>

            <!-- Right Section -->
            <div class="flex items-center space-x-4">

                <!-- Desktop Auth -->
                <div class="hidden md:flex items-center space-x-4">
                    @guest
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-gray-700 hover:text-blue-500 font-medium transition-colors">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg font-medium transition-colors">
                        Register
                    </a>
                    @else
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            @click.outside="open = false"
                            class="flex items-center gap-2 text-gray-700 hover:text-gray-900 focus:outline-none">
                            <x-lucide-user class="w-5 h-5" />
                            <span class="max-w-[120px] truncate">
                                {{ Str::limit($user->name, 15) }}
                            </span>
                            <x-lucide-chevron-down class="w-4 h-4" />
                        </button>

                        <div
                            x-show="open"
                            x-transition
                            class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <x-lucide-user class="w-4 h-4" />
                                Profile
                            </a>

                            <div class="border-t border-gray-100"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <x-lucide-log-out class="w-4 h-4" />
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                    @endguest
                </div>

                <!-- Mobile Menu Button -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg border border-gray-300 text-gray-700">
                    <template x-if="!mobileOpen">
                        <x-lucide-menu class="w-6 h-6" />
                    </template>
                    <template x-if="mobileOpen">
                        <x-lucide-x class="w-6 h-6" />
                    </template>
                </button>
            </div>

            <!-- Mobile Dropdown -->
            <div
                x-show="mobileOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-4"
                @click.outside="mobileOpen = false"
                class="fixed top-[64px] left-0 w-full h-[calc(100vh-64px)] bg-white z-50 overflow-y-auto md:hidden">

                <div class="p-6 flex flex-col space-y-4">

                    <!-- Nav Links -->
                    <a href="{{ route('course') }}" class="block px-4 py-2 rounded hover:bg-gray-100">Course</a>
                    <a href="{{ route('school') }}" class="block px-4 py-2 rounded hover:bg-gray-100">School</a>
                    <a href="{{ route('college') }}" class="block px-4 py-2 rounded hover:bg-gray-100">College</a>
                    <a href="{{ route('forum.question.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100">Forum</a>
                    <a href="{{ route('admission.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100">Admission</a>
                    <a href="{{ route('event.index') }}" class="block px-4 py-2 rounded hover:bg-gray-100">Events</a>

                    <div class="border-t my-2"></div>

                    <!-- Auth Links -->
                    @guest
                    <a href="{{ route('login') }}" class="block px-4 py-2 text-blue-600 font-medium hover:bg-blue-50 rounded">
                        Login
                    </a>

                    <a href="{{ route('register') }}" class="block px-4 py-2 bg-blue-500 text-white font-medium rounded hover:bg-blue-600">
                        Register
                    </a>
                    @else
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 rounded">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2 text-red-600 hover:bg-red-50 rounded">
                            Logout
                        </button>
                    </form>
                    @endguest

                </div>
            </div>

        </div>
    </div>
    
    <!-- Desktop Navigation Menu -->
    <nav class="bg-white hidden md:block">
        <div class="container max-w-7xl mx-auto px-4">
            <div class="flex justify-center">
                <ul class="flex space-x-4 py-2">
                    <li>
                        <a href="{{ route('course') }}"
                            class="text-base px-3 py-2 {{ $isActive('course') }} transition-colors">
                            Course
                        </a>
                    </li>
    
                    <li>
                        <a href="{{ route('school') }}"
                            class="text-base px-3 py-2 {{ $isActive('school') }} transition-colors">
                            School
                        </a>
                    </li>
    
                    <li>
                        <a href="{{ route('college') }}"
                            class="text-base px-3 py-2 {{ $isActive('college') }} transition-colors">
                            College
                        </a>
                    </li>
    
                    <li>
                        <a href="{{ route('forum.question.index') }}"
                            class="text-base px-3 py-2 {{ $isActive('forum.question.index') }} transition-colors">
                            Forum
                        </a>
                    </li>
    
                    <li>
                        <a href="{{ route('admission.index') }}"
                            class="text-base px-3 py-2 {{ $isActive('admission.index') }} transition-colors">
                            Admission
                        </a>
                    </li>
    
                    <li>
                        <a href="{{ route('event.index') }}"
                            class="text-base px-3 py-2 {{ $isActive('event.index') }} transition-colors">
                            Events
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</div>
