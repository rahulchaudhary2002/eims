@php
$sidebarOpen = session('sidebarOpen', true);
@endphp

<div class="fixed left-0 top-0 h-full bg-gradient-to-b from-primary to-secondary border-r transition-all duration-300 {{ $sidebarOpen ? 'w-[250px]' : 'w-[70px]' }}">
    <div class="flex items-center justify-center text-white text-3xl h-[70px] px-4">
        <a class="flex items-center font-bold" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Sikuna" class="h-16 w-auto">
        </a>
    </div>

    <ul class="scrollbar w-full h-[calc(100%-70px)] overflow-auto">
        {{-- Dashboard --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                  {{ request()->routeIs('admin.dashboard') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-layout-dashboard class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Dashboard' : '' }}</span>
            </a>
        </li>

        {{-- Affiliations --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.affiliation.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                  {{ request()->routeIs('admin.affiliation.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-building class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Affiliations' : '' }}</span>
            </a>
        </li>

        {{-- Institutions --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.institution.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                  {{ request()->routeIs('admin.institution.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-school class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Institutions' : '' }}</span>
            </a>
        </li>

        {{-- Institution Types --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.institution-type.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                  {{ request()->routeIs('admin.institution-type.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-building-2 class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Institution Types' : '' }}</span>
            </a>
        </li>

        {{-- Levels --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.level.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                  {{ request()->routeIs('admin.level.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-bar-chart-3 class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Levels' : '' }}</span>
            </a>
        </li>

        {{-- Programs --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.program.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                          {{ request()->routeIs('admin.program.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-layers class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Programs' : '' }}</span>
            </a>
        </li>

        {{-- Courses --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.course.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                  {{ request()->routeIs('admin.course.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-book class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Courses' : '' }}</span>
            </a>
        </li>

        {{-- Vendors --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.vendor.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                  {{ request()->routeIs('admin.vendor.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-truck class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Vendors' : '' }}</span>
            </a>
        </li>

        {{-- Admission Reward --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.admission.reward.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                  {{ request()->routeIs('admin.admission.reward.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-award class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Admission Reward' : '' }}</span>
            </a>
        </li>

        {{-- Admission Commission --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('admin.admission.commission.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                  {{ request()->routeIs('admin.admission.commission.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-percent class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Admission Commission' : '' }}</span>
            </a>
        </li>

    </ul>
</div>
