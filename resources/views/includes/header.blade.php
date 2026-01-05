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

<div class="sticky top-0 w-full bg-white z-50 border-b">
    <div class="container max-w-7xl mx-auto py-3 px-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="text-2xl font-bold text-blue-500">
                    EIMS
                </a>
            </div>

            <!-- Search Field -->
            <div class="flex-1 max-w-md mx-8">
                <form action="#" method="GET" class="relative">
                    <div class="relative">
                        <x-lucide-search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
                        <input
                            type="text"
                            placeholder="Search courses, schools, colleges..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg outline-none transition-colors" />
                    </div>
                </form>
            </div>

            <!-- Auth Links -->
            <div class="flex items-center space-x-4">
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
                    <!-- Trigger -->
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

                    <!-- Dropdown -->
                    <div
                        x-show="open"
                        x-transition
                        class="absolute right-0 mt-2 w-44 bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                        <a
                            href="{{ route('profile.edit') }}"
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
        </div>
    </div>
</div>

<!-- Navigation Menu -->
<nav class="bg-white shadow-sm border-b">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="flex justify-center">
            <ul class="flex space-x-8 py-2">
                <li>
                    <a href="{{ url('/') }}"
                        class="px-3 py-2 {{ $isActive('home') }} transition-colors flex items-center gap-2">
                        <x-lucide-home class="w-4 h-4" />
                        Home
                    </a>
                </li>

                <li>
                    <a href="{{ route('course') }}"
                        class="px-3 py-2 {{ $isActive('course') }} transition-colors flex items-center gap-2">
                        <x-lucide-book-open class="w-4 h-4" />
                        Course
                    </a>
                </li>

                <li>
                    <a href="{{ route('school') }}"
                        class="px-3 py-2 {{ $isActive('school') }} transition-colors flex items-center gap-2">
                        <x-lucide-school class="w-4 h-4" />
                        School
                    </a>
                </li>

                <li>
                    <a href="{{ route('college') }}"
                        class="px-3 py-2 {{ $isActive('college') }} transition-colors flex items-center gap-2">
                        <x-lucide-building-2 class="w-4 h-4" />
                        College
                    </a>
                </li>

                <li>
                    <a href="{{ route('forum.question.index') }}"
                        class="px-3 py-2 {{ $isActive('forum.question.index') }} transition-colors flex items-center gap-2">
                        <x-lucide-message-square class="w-4 h-4" />
                        Forum
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>