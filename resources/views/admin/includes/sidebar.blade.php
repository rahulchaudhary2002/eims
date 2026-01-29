@php
$sidebarOpen = session('sidebarOpen', true);
@endphp

<div class="fixed left-0 top-0 h-full bg-white border-r transition-all duration-300 {{ $sidebarOpen ? 'w-[250px]' : 'w-[70px]' }}">
    <div class="flex items-center justify-center h-[70px] px-4 border-b">
        <a href="{{ route('admin.dashboard') }}">
            <!-- @if($sidebarOpen)
            <img src="{{ asset('images/logo.png') }}" class="h-[50px]" alt="Logo">
            @else
            <img src="{{ asset('images/half-logo.png') }}" class="h-[40px]" alt="Logo">
            @endif -->
            EIMS
        </a>
    </div>

    <ul class="scrollbar w-full h-[calc(100%-70px)] overflow-auto">
        {{-- Dashboard --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('admin.dashboard') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-layout-dashboard class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Dashboard' : '' }}</span>
            </a>
        </li>

        {{-- Affiliations --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('admin.affiliation.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('admin.affiliation.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-building class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Affiliations' : '' }}</span>
            </a>
        </li>

        {{-- Institutions --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('admin.institution.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('admin.institution.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-school class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Institutions' : '' }}</span>
            </a>
        </li>

        {{-- Levels --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('admin.level.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('admin.level.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-bar-chart-3 class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Levels' : '' }}</span>
            </a>
        </li>

        {{-- Courses --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('admin.course.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('admin.course.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-book class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Courses' : '' }}</span>
            </a>
        </li>

        {{-- Vendors --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('admin.vendor.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('admin.vendor.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-truck class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Vendors' : '' }}</span>
            </a>
        </li>

        {{-- Admission Reward --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('admin.admission.reward.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                    {{ request()->routeIs('admin.admission.reward.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-award class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Admission Reward' : '' }}</span>
            </a>
        </li>

        {{-- Admission Comission --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('admin.admission.comission.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('admin.admission.comission.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-percent class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Admission Comission' : '' }}</span>
            </a>
        </li>
    </ul>
</div>