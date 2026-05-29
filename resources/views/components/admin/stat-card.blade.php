{{--
    Stat Card Component
    Usage:
    <x-admin.stat-card
        label="Total Students"
        :value="$studentCount"
        icon="users"
        color="primary"
        :trend="+12"
        subtitle="vs last month" />

    color options: primary | sky | success | warning | danger | slate
--}}
@props([
    'label'    => 'Stat',
    'value'    => '0',
    'icon'     => null,
    'color'    => 'primary',
    'trend'    => null,
    'subtitle' => null,
    'href'     => null,
])

@php
$colorMap = [
    'primary' => ['bg' => 'bg-primary-100',   'text' => 'text-primary-600'],
    'sky'     => ['bg' => 'bg-sky-100',        'text' => 'text-sky-600'],
    'success' => ['bg' => 'bg-success-light',  'text' => 'text-success'],
    'warning' => ['bg' => 'bg-warning-light',  'text' => 'text-warning'],
    'danger'  => ['bg' => 'bg-danger-light',   'text' => 'text-danger'],
    'slate'   => ['bg' => 'bg-slate-100',      'text' => 'text-slate-600'],
];
$c = $colorMap[$color] ?? $colorMap['primary'];
$tag = $href ? 'a' : 'div';
$attrs = $href ? "href=\"$href\"" : '';
@endphp

<{{ $tag }} {{ $attrs }} class="stat-card flex items-center gap-4 {{ $href ? 'hover:no-underline' : '' }}">
    @if($icon)
    <div class="w-14 h-14 rounded-xl {{ $c['bg'] }} flex items-center justify-center shrink-0">
        <span class="{{ $c['text'] }}">
            @includeIf('components.admin.icons.' . $icon, ['class' => 'w-7 h-7'])
            @if(!View::exists('components.admin.icons.' . $icon))
            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5"/></svg>
            @endif
        </span>
    </div>
    @endif
    <div class="flex-1 min-w-0">
        <p class="text-sm font-medium text-slate-500 mb-1">{{ $label }}</p>
        <p class="text-2xl font-bold text-slate-900 leading-tight">{{ $value }}</p>
        @if($subtitle || $trend !== null)
        <p class="text-xs text-slate-400 mt-1 flex items-center gap-1">
            @if($trend !== null)
            @if($trend >= 0)
            <span class="text-success font-semibold">+{{ $trend }}%</span>
            @else
            <span class="text-danger font-semibold">{{ $trend }}%</span>
            @endif
            @endif
            {{ $subtitle }}
        </p>
        @endif
    </div>
</{{ $tag }}>
