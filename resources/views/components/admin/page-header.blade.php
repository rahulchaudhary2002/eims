{{--
    Page Header Component
    Usage:
    <x-admin.page-header
        title="Users"
        subtitle="Manage user accounts"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Users']]">
        <x-slot:actions>
            <a href="..." class="btn btn-primary">Add New</a>
        </x-slot:actions>
    </x-admin.page-header>
--}}

@props([
    'title'       => 'Page Title',
    'subtitle'    => null,
    'breadcrumbs' => [],
    'icon'        => null,
])

<div class="page-header mb-6">
    <div class="flex items-start gap-3">
        @if($icon)
        <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center shrink-0">
            <span class="text-primary-600">{{ $icon }}</span>
        </div>
        @endif
        <div>
            <h1 class="page-header-title">{{ $title }}</h1>
            @if($subtitle)
            <p class="page-header-subtitle">{{ $subtitle }}</p>
            @endif
            @if(count($breadcrumbs) > 0)
            <nav class="flex items-center gap-1 mt-1" aria-label="Breadcrumb">
                @foreach($breadcrumbs as $i => $crumb)
                @if($i > 0)
                <svg class="w-3.5 h-3.5 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                @endif
                @if(isset($crumb['route']))
                    @php
                        $routeParams = $crumb['params'] ?? $crumb['param'] ?? $crumb['routeParam'] ?? [];
                    @endphp
                <a href="{{ route($crumb['route'], $routeParams) }}" class="text-xs text-primary-600 hover:underline font-medium">{{ $crumb['label'] }}</a>
                @else
                <span class="text-xs text-slate-500">{{ $crumb['label'] }}</span>
                @endif
                @endforeach
            </nav>
            @endif
        </div>
    </div>

    @if(isset($actions))
    <div class="page-header-actions">
        {{ $actions }}
    </div>
    @endif
</div>
