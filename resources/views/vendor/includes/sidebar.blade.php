@php
$sidebarOpen = session('sidebarOpen', true);
@endphp

<div class="fixed left-0 top-0 h-full bg-gradient-to-b from-primary to-secondary border-r transition-all duration-300 {{ $sidebarOpen ? 'w-[250px]' : 'w-[70px]' }}">
    <div class="flex items-center justify-center text-white text-3xl h-[70px] px-4">
        <a class="flex items-center font-bold" href="{{ route('vendor.dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Sikuna" class="h-16 w-auto">
        </a>
    </div>

    <ul class="scrollbar w-full h-[calc(100%-70px)] overflow-auto">

        {{-- Dashboard --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('vendor.dashboard') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                {{ request()->routeIs('vendor.dashboard') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-layout-dashboard class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Dashboard' : '' }}</span>
            </a>
        </li>

        {{-- Enquiries --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('vendor.enquiry.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
                {{ request()->routeIs('vendor.enquiry.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-message-square class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Enquiries' : '' }}</span>
            </a>
        </li>

        {{-- Events --}}
        <li class="text-white font-semibold text-sm">
            <a href="{{ route('vendor.event.index') }}"
                class="flex items-center px-4 py-3 h-12 transition-all duration-300 border-l-4
               {{ request()->routeIs('vendor.event.*') ? 'bg-[rgba(255,255,255,0.1)] border-success' : 'border-transparent hover:bg-[rgba(255,255,255,0.1)] hover:border-success' }}">
                <x-lucide-calendar class="w-5 h-5 mr-2" />
                <span>{{ $sidebarOpen ? 'Events' : '' }}</span>
            </a>
        </li>

    </ul>
</div>
