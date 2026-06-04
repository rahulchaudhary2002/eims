@extends('institution.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Institution Dashboard')

@section('content')
<div class="space-y-6">
    <x-admin.page-header
        :title="$activeInstitution->name"
        :subtitle="(\App\Models\Institution::TYPES[$activeInstitution->type] ?? $activeInstitution->type) . ' dashboard'"
        :breadcrumbs="[['label' => 'Dashboard', 'route' => 'institution.dashboard']]">
        <x-slot:actions>
            <span class="badge badge-blue">{{ $activeInstitution->status }}</span>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach($cards as $label => $value)
            <div class="eims-card p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide">{{ $label }}</p>
                <p class="text-2xl font-bold text-slate-800 mt-2">{{ is_float($value) ? number_format($value, 1) : number_format((float) $value, str_contains($label, 'amount') ? 2 : 0) }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
        @foreach($charts as $label => $rows)
            <div class="eims-card p-5">
                <h2 class="text-base font-semibold text-slate-800 mb-4">{{ $label }}</h2>
                <div class="space-y-3">
                    @forelse($rows as $name => $total)
                        @php $width = max(6, min(100, (float) $total * 8)); @endphp
                        <div>
                            <div class="flex justify-between gap-3 text-sm mb-1">
                                <span class="text-slate-600 truncate">{{ $name ?: 'Not set' }}</span>
                                <span class="font-semibold text-slate-800">{{ number_format((float) $total, is_float($total) ? 1 : 0) }}</span>
                            </div>
                            <div class="h-2 rounded-full bg-slate-100 overflow-hidden">
                                <div class="h-full rounded-full bg-primary-500" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400">No data yet.</p>
                    @endforelse
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
