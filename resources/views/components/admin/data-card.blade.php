{{--
    Data Card (table/grid wrapper)
    Usage:
    <x-admin.data-card title="All Institutions" :count="$total">
        <x-slot:toolbar> ... search/filter inputs ... </x-slot:toolbar>
        <x-slot:table> ... </x-slot:table>
        <x-slot:pagination> ... </x-slot:pagination>
    </x-admin.data-card>
--}}
@props([
    'title' => null,
    'count' => null,
])
<div class="eims-card p-0 overflow-hidden">
    @if($title || isset($toolbar))
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
        @if($title)
        <div class="flex items-center gap-2">
            <h2 class="eims-card-title !mb-0 !pb-0 !border-0">{{ $title }}</h2>
            @if($count !== null)
            <span class="badge badge-secondary">{{ $count }}</span>
            @endif
        </div>
        @endif
        @if(isset($toolbar))
        <div class="flex items-center gap-2 flex-wrap">
            {{ $toolbar }}
        </div>
        @endif
    </div>
    @endif

    @if(isset($table))
    <div class="eims-table-wrapper">
        {{ $table }}
    </div>
    @else
    {{ $slot }}
    @endif

    @if(isset($pagination))
    <div class="px-6 py-4 border-t border-slate-100">
        {{ $pagination }}
    </div>
    @endif
</div>
