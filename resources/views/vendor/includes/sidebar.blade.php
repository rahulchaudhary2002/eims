@php
$sidebarOpen = session('sidebarOpen', true);
@endphp

<div class="fixed left-0 top-0 h-full bg-white border-r transition-all duration-300 {{ $sidebarOpen ? 'w-[250px]' : 'w-[70px]' }}">
    <div class="flex items-center justify-center h-[70px] px-4 border-b">
        <a href="{{ route('vendor.dashboard') }}">
            EIMS
        </a>
    </div>

    <ul class="scrollbar w-full h-[calc(100%-70px)] overflow-auto">

        {{-- Dashboard --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('vendor.dashboard') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('vendor.dashboard') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-layout-dashboard class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Dashboard' : '' }}</span>
            </a>
        </li>

        {{-- Enquiries --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('vendor.enquiry.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('vendor.enquiry.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-message-square class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Enquiries' : '' }}</span>
            </a>
        </li>

        {{-- Admissions --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('vendor.admission.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('vendor.admission.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-graduation-cap class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Admissions' : '' }}</span>
            </a>
        </li>

        {{-- Events --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('vendor.event.index') }}"
               class="flex items-center px-4 py-3 h-12 transition-all duration-300
               {{ request()->routeIs('vendor.event.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-calendar class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Events' : '' }}</span>
            </a>
        </li>

        {{-- Admission Commission --}}
        <li class="text-gray-600 font-semibold text-sm">
            <a href="{{ route('vendor.admission-commission.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300
                {{ request()->routeIs('vendor.admission-commission.*') ? 'text-blue-500' : 'hover:text-blue-500' }}">
                <x-lucide-percent class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Admission Commission' : '' }}</span>
            </a>
        </li>
    </ul>
</div>