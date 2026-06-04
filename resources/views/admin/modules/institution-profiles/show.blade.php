@extends('admin.layouts.app')
@section('title', $institutionProfile->institution->name . ' - Profile')
@section('page-title', 'Institution Profile')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        :title="$institutionProfile->institution->name . ' - Profile'"
        subtitle="Institution profile details"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Institution Profiles','route' => 'admin.institution-profiles.index'],
            ['label'=>$institutionProfile->institution->name],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-profiles.edit', $institutionProfile) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.institution-profiles.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left: Details --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Amenities --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-emerald-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">Amenities</h3>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach([
                        'has_hostel'        => 'Hostel',
                        'has_transportation'=> 'Transportation',
                        'has_library'       => 'Library',
                        'has_lab'           => 'Laboratory',
                        'has_cafeteria'     => 'Cafeteria',
                        'has_sports'        => 'Sports Facility',
                        'has_scholarship'   => 'Scholarship',
                    ] as $field => $label)
                    <div class="flex items-center gap-2 p-3 rounded-lg bg-slate-50 border border-slate-100">
                        @if($institutionProfile->$field)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-500 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd"/></svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300 shrink-0" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z" clip-rule="evenodd"/></svg>
                        @endif
                        <span class="text-sm {{ $institutionProfile->$field ? 'text-slate-700' : 'text-slate-400' }}">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Tag Lists --}}
            @foreach([
                'facilities'     => ['label' => 'Facilities', 'color' => 'blue'],
                'infrastructure' => ['label' => 'Infrastructure', 'color' => 'violet'],
                'achievements'   => ['label' => 'Achievements', 'color' => 'amber'],
                'accreditations' => ['label' => 'Accreditations', 'color' => 'emerald'],
            ] as $field => $meta)
            @php $items = $institutionProfile->$field ?? []; @endphp
            @if(!empty($items))
            <div class="eims-card p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">{{ $meta['label'] }}</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($items as $item)
                    <span class="inline-flex items-center px-3 py-1 bg-{{ $meta['color'] }}-50 text-{{ $meta['color'] }}-700 border border-{{ $meta['color'] }}-200 rounded-full text-sm">
                        {{ $item }}
                    </span>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach

        </div>

        {{-- Right: Meta --}}
        <div class="space-y-5">

            {{-- Institution --}}
            <div class="eims-card p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4 pb-3 border-b border-slate-100">Institution</h3>
                <a href="{{ route('admin.institutions.show', $institutionProfile->institution) }}"
                   class="font-medium text-blue-600 hover:underline text-sm">
                    {{ $institutionProfile->institution->name }}
                </a>
            </div>

            {{-- Social Links --}}
            @php
                $hasSocial = $institutionProfile->facebook_url || $institutionProfile->instagram_url
                          || $institutionProfile->linkedin_url || $institutionProfile->youtube_url;
            @endphp
            @if($hasSocial)
            <div class="eims-card p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4 pb-3 border-b border-slate-100">Social Links</h3>
                <div class="space-y-2">
                    @foreach([
                        'facebook_url'  => ['label' => 'Facebook', 'color' => 'blue'],
                        'instagram_url' => ['label' => 'Instagram', 'color' => 'pink'],
                        'linkedin_url'  => ['label' => 'LinkedIn', 'color' => 'blue'],
                        'youtube_url'   => ['label' => 'YouTube', 'color' => 'red'],
                    ] as $field => $meta)
                        @if($institutionProfile->$field)
                        <a href="{{ $institutionProfile->$field }}" target="_blank" rel="noopener"
                           class="flex items-center gap-2 text-sm text-{{ $meta['color'] }}-600 hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                            {{ $meta['label'] }}
                        </a>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Timestamps --}}
            <div class="eims-card p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4 pb-3 border-b border-slate-100">Meta</h3>
                <dl class="space-y-2">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500">Created</dt>
                        <dd class="text-sm text-slate-600">{{ $institutionProfile->created_at->format('d M Y') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500">Updated</dt>
                        <dd class="text-sm text-slate-600">{{ $institutionProfile->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Danger Zone --}}
            <div class="eims-card p-6 border border-red-100">
                <h3 class="text-sm font-semibold text-red-700 mb-3">Danger Zone</h3>
                <form action="{{ route('admin.institution-profiles.destroy', $institutionProfile) }}" method="POST"
                      onsubmit="return confirm('Delete this institution profile? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete Profile
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
