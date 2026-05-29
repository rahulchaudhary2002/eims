{{--
    Alert (flash message)
    Usage:
    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger">Custom message here</x-admin.alert>

    Renders nothing if no message.
--}}
@props([
    'type'    => 'success',
    'message' => null,
    'dismissible' => true,
])

@php
$text    = $message ?? (isset($slot) && $slot->isNotEmpty() ? null : null);
$typeMap = [
    'success' => ['cls' => 'alert-success', 'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
    'danger'  => ['cls' => 'alert-danger',  'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'],
    'warning' => ['cls' => 'alert-warning', 'icon' => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z'],
    'info'    => ['cls' => 'alert-info',    'icon' => 'M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z'],
    'error'   => ['cls' => 'alert-danger',  'icon' => 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z'],
];
$t = $typeMap[$type] ?? $typeMap['info'];
@endphp

@if($message || $slot->isNotEmpty())
<div class="alert {{ $t['cls'] }}" role="alert" {{ $dismissible ? 'x-data="{show:true}" x-show="show"' : '' }}>
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $t['icon'] }}"/>
    </svg>
    <span class="flex-1 text-sm">
        @if($message)
            {{ $message }}
        @else
            {{ $slot }}
        @endif
    </span>
    @if($dismissible)
    <button @click="show = false" class="shrink-0 opacity-60 hover:opacity-100 transition-opacity ml-auto">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
    @endif
</div>
@endif
