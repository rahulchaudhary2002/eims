{{--
    Empty State
    Usage:
    <x-admin.empty-state
        title="No institutions yet"
        description="Get started by adding your first institution."
        :action-href="route('admin.institution.create')"
        action-label="Add Institution" />
--}}
@props([
    'title'       => 'No records found',
    'description' => 'Get started by creating your first record.',
    'actionHref'  => null,
    'actionLabel' => 'Create',
    'icon'        => null,
])
<div class="empty-state">
    <div class="empty-state-icon">
        @if($icon)
            {!! $icon !!}
        @else
        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z"/>
        </svg>
        @endif
    </div>
    <h3 class="empty-state-title">{{ $title }}</h3>
    <p class="empty-state-desc">{{ $description }}</p>
    @if($actionHref)
    <a href="{{ $actionHref }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        {{ $actionLabel }}
    </a>
    @endif
</div>
