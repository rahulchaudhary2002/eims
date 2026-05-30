@if (!empty($breadcrumbs))
@php
    $variant = $variant ?? 'light';
    $isDark = $variant === 'dark';
@endphp
<nav class="{{ $isDark ? 'inline-flex bg-white/10 border border-white/20 rounded-full px-4 py-2 text-white/75' : 'flex text-gray-500 mb-6' }} items-center gap-2 text-sm" aria-label="Breadcrumb">
    <a href="{{ route('website.home') }}" class="{{ $isDark ? 'hover:text-white' : 'hover:text-blue-600' }} transition-colors no-underline">
        <i class="fas fa-home"></i>
    </a>
    @foreach ($breadcrumbs as $crumb)
        <span class="{{ $isDark ? 'text-white/35' : 'text-gray-300' }}">/</span>
        @if (!$loop->last && isset($crumb['url']))
            <a href="{{ $crumb['url'] }}" class="{{ $isDark ? 'hover:text-white' : 'hover:text-blue-600' }} transition-colors no-underline">{{ $crumb['label'] }}</a>
        @else
            <span class="{{ $isDark ? 'text-white font-semibold' : 'text-gray-700 font-medium' }}">{{ $crumb['label'] }}</span>
        @endif
    @endforeach
</nav>
@endif
