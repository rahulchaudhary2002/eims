@extends('admin.layouts.app')
@section('title', $institution->name)
@section('page-title', 'Institution Details')

@section('content')
<div class="space-y-5">

    <x-admin.page-header :title="$institution->name" subtitle="Institution Details"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Institutions','route'=>'admin.institutions.index'],['label'=>$institution->name]]">
        <x-slot:actions>
            <a href="{{ route('admin.institutions.edit', $institution) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.institutions.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    {{-- Cover + Logo Banner --}}
    <div class="eims-card p-0 overflow-hidden">
        <div class="h-40 bg-gradient-to-r from-[#2c5aa0] to-[#1a365d] relative">
            @if($institution->cover_image)
            <img src="{{ Storage::url($institution->cover_image) }}" alt="Cover"
                 class="absolute inset-0 w-full h-full object-cover opacity-80">
            @endif
        </div>
        <div class="px-6 py-4 flex items-end gap-5 -mt-10 relative">
            <div class="w-20 h-20 rounded-xl bg-white shadow-md border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                @if($institution->logo)
                <img src="{{ Storage::url($institution->logo) }}" alt="Logo" class="w-full h-full object-contain p-1">
                @else
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21"/></svg>
                @endif
            </div>
            <div class="pb-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl font-bold text-slate-800">{{ $institution->name }}</h2>
                    @if($institution->is_verified)
                    <span class="inline-flex items-center gap-1 text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 rounded-full px-2 py-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                        Verified
                    </span>
                    @endif
                    @if($institution->is_featured)
                    <span class="inline-flex items-center gap-1 text-xs bg-amber-50 text-amber-700 border border-amber-200 rounded-full px-2 py-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005z" clip-rule="evenodd"/></svg>
                        Featured
                    </span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 mt-0.5">
                    {{ \App\Models\Institution::TYPES[$institution->type] ?? $institution->type }}
                    @if($institution->code) &nbsp;·&nbsp; <span class="font-mono">{{ $institution->code }}</span>@endif
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left: Basic Info + Contact + Location --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Basic Information --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">Basic Information</h3>
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Name</dt>
                        <dd class="mt-1 text-slate-800 font-medium">{{ $institution->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Slug</dt>
                        <dd class="mt-1 text-slate-600 font-mono text-sm">{{ $institution->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Type</dt>
                        <dd class="mt-1">
                            <span class="badge badge-blue">{{ \App\Models\Institution::TYPES[$institution->type] ?? $institution->type }}</span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Code</dt>
                        <dd class="mt-1 text-slate-600 font-mono">{{ $institution->code ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Established</dt>
                        <dd class="mt-1 text-slate-700">{{ $institution->established_year ?: '—' }}</dd>
                    </div>
                    @if($institution->parent_id)
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Parent Institution</dt>
                        <dd class="mt-1 text-slate-700">{{ $institution->parent_id }}</dd>
                    </div>
                    @endif
                </dl>

                @if($institution->short_description)
                <div class="mt-5 pt-4 border-t border-slate-100">
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Short Description</dt>
                    <p class="text-slate-700 text-sm leading-relaxed">{{ $institution->short_description }}</p>
                </div>
                @endif

                @if($institution->description)
                <div class="mt-4">
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider mb-2">Description</dt>
                    <div class="prose prose-sm text-slate-700 max-w-none">{!! nl2br(e($institution->description)) !!}</div>
                </div>
                @endif
            </div>

            {{-- Contact --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-green-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">Contact</h3>
                </div>
                <dl class="space-y-3">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                        @if($institution->email)
                        <a href="mailto:{{ $institution->email }}" class="text-blue-600 hover:underline text-sm">{{ $institution->email }}</a>
                        @else
                        <span class="text-slate-400 text-sm">No email</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                        @if($institution->phone)
                        <a href="tel:{{ $institution->phone }}" class="text-slate-700 text-sm hover:text-blue-600">{{ $institution->phone }}</a>
                        @else
                        <span class="text-slate-400 text-sm">No phone</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/></svg>
                        @if($institution->website)
                        <a href="{{ $institution->website }}" target="_blank" rel="noopener noreferrer" class="text-blue-600 hover:underline text-sm">{{ $institution->website }}</a>
                        @else
                        <span class="text-slate-400 text-sm">No website</span>
                        @endif
                    </div>
                </dl>
            </div>

            {{-- Location --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-amber-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">Location</h3>
                </div>
                <dl class="grid grid-cols-2 gap-4">
                    <div><dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Country</dt><dd class="mt-1 text-slate-700 text-sm">{{ $institution->country ?: '—' }}</dd></div>
                    <div><dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Province</dt><dd class="mt-1 text-slate-700 text-sm">{{ $institution->province ?: '—' }}</dd></div>
                    <div><dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">District</dt><dd class="mt-1 text-slate-700 text-sm">{{ $institution->district ?: '—' }}</dd></div>
                    <div><dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">City</dt><dd class="mt-1 text-slate-700 text-sm">{{ $institution->city ?: '—' }}</dd></div>
                    @if($institution->address)
                    <div class="col-span-2"><dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Address</dt><dd class="mt-1 text-slate-700 text-sm">{{ $institution->address }}</dd></div>
                    @endif
                    @if($institution->latitude || $institution->longitude)
                    <div><dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Latitude</dt><dd class="mt-1 text-slate-600 font-mono text-sm">{{ $institution->latitude }}</dd></div>
                    <div><dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Longitude</dt><dd class="mt-1 text-slate-600 font-mono text-sm">{{ $institution->longitude }}</dd></div>
                    @endif
                </dl>
            </div>

        </div>

        {{-- Right: Status + Settings + Meta --}}
        <div class="space-y-5">

            {{-- Status Card --}}
            <div class="eims-card p-6">
                <h3 class="text-sm font-semibold text-slate-700 mb-4">Quick Status Update</h3>
                <form action="{{ route('admin.institutions.update-status', $institution) }}" method="POST" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control">
                        @foreach(\App\Models\Institution::STATUSES as $val => $lbl)
                        <option value="{{ $val }}" {{ $institution->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-full">Update Status</button>
                </form>
            </div>

            {{-- Meta Info --}}
            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-4 pb-3 border-b border-slate-100">
                    <div class="p-2 bg-slate-100 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">Settings</h3>
                </div>
                <dl class="space-y-3">
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500">Verified</dt>
                        <dd>
                            @if($institution->is_verified)
                            <span class="badge badge-green">Yes</span>
                            @else
                            <span class="badge">No</span>
                            @endif
                        </dd>
                    </div>
                    @if($institution->is_verified && $institution->verified_at)
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500">Verified At</dt>
                        <dd class="text-sm text-slate-600">{{ $institution->verified_at->format('d M Y') }}</dd>
                    </div>
                    @endif
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500">Featured</dt>
                        <dd>
                            @if($institution->is_featured)
                            <span class="badge badge-yellow">Yes</span>
                            @else
                            <span class="badge">No</span>
                            @endif
                        </dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500">Sort Order</dt>
                        <dd class="text-sm text-slate-600 font-mono">{{ $institution->sort_order }}</dd>
                    </div>
                    <div class="flex items-center justify-between pt-2 border-t border-slate-100">
                        <dt class="text-sm text-slate-500">Created</dt>
                        <dd class="text-sm text-slate-600">{{ $institution->created_at->format('d M Y') }}</dd>
                    </div>
                    <div class="flex items-center justify-between">
                        <dt class="text-sm text-slate-500">Updated</dt>
                        <dd class="text-sm text-slate-600">{{ $institution->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Danger Zone --}}
            <div class="eims-card p-6 border border-red-100">
                <h3 class="text-sm font-semibold text-red-700 mb-3">Danger Zone</h3>
                <form action="{{ route('admin.institutions.destroy', $institution) }}" method="POST"
                      onsubmit="return confirm('Permanently delete {{ addslashes($institution->name) }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete Institution
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- Assigned Users --}}
    <div class="eims-card p-6">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
            <div class="p-2 bg-blue-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Assigned Users</h3>
            <span class="ml-auto text-xs text-slate-400">{{ $institution->users->count() }} user(s)</span>
        </div>

        @if($institution->users->isEmpty())
            <p class="text-sm text-slate-400">No users assigned to this institution.</p>
        @else
        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Position</th>
                        <th>Joined</th>
                        <th class="text-center">Active</th>
                        <th class="text-center">Primary</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($institution->users as $usr)
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($usr->avatar)
                                    <img src="{{ Storage::url($usr->avatar) }}" alt=""
                                         class="w-8 h-8 rounded-full object-cover shrink-0">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <a href="{{ route('admin.users.show', $usr) }}"
                                       class="font-medium text-blue-600 hover:underline text-sm">{{ $usr->name }}</a>
                                    <p class="text-xs text-slate-400">{{ $usr->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $usr->pivot->role_name ?: '—' }}</td>
                        <td>{{ $usr->pivot->position ?: '—' }}</td>
                        <td class="text-sm text-slate-500">
                            {{ $usr->pivot->joined_at ? \Carbon\Carbon::parse($usr->pivot->joined_at)->format('d M Y') : '—' }}
                        </td>
                        <td class="text-center">
                            @if($usr->pivot->is_active)
                                <span class="badge badge-green">Yes</span>
                            @else
                                <span class="badge badge-red">No</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($usr->pivot->is_primary)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500 mx-auto" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                            @else
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    {{-- Institution Profile --}}
    <div class="eims-card p-6">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
            <div class="p-2 bg-violet-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-violet-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Institution Profile</h3>
            <div class="ml-auto flex items-center gap-2">
                @if($institution->profile)
                    <a href="{{ route('admin.institution-profiles.edit', $institution->profile) }}" class="btn btn-secondary text-xs py-1.5">Edit Profile</a>
                    <a href="{{ route('admin.institution-profiles.show', $institution->profile) }}" class="btn btn-secondary text-xs py-1.5">View Full</a>
                @else
                    <a href="{{ route('admin.institution-profiles.create', ['institution_id' => $institution->id]) }}" class="btn btn-primary text-xs py-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add Profile
                    </a>
                @endif
            </div>
        </div>

        @if(!$institution->profile)
        <p class="text-sm text-slate-400">No profile created yet for this institution.</p>
        @else
        @php $profile = $institution->profile; @endphp
        <div class="space-y-5">
            {{-- Amenities --}}
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Amenities</p>
                <div class="flex flex-wrap gap-2">
                    @foreach([
                        'has_hostel'        => 'Hostel',
                        'has_transportation'=> 'Transportation',
                        'has_library'       => 'Library',
                        'has_lab'           => 'Laboratory',
                        'has_cafeteria'     => 'Cafeteria',
                        'has_sports'        => 'Sports',
                        'has_scholarship'   => 'Scholarship',
                    ] as $field => $label)
                    @if($profile->$field)
                        <span class="badge badge-green">{{ $label }}</span>
                    @endif
                    @endforeach
                </div>
            </div>

            {{-- Tag rows --}}
            @foreach([
                'facilities'     => ['label' => 'Facilities', 'color' => 'blue'],
                'infrastructure' => ['label' => 'Infrastructure', 'color' => 'violet'],
                'achievements'   => ['label' => 'Achievements', 'color' => 'amber'],
                'accreditations' => ['label' => 'Accreditations', 'color' => 'emerald'],
            ] as $field => $meta)
            @php $items = $profile->$field ?? []; @endphp
            @if(!empty($items))
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">{{ $meta['label'] }}</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($items as $item)
                    <span class="inline-flex items-center px-3 py-1 bg-{{ $meta['color'] }}-50 text-{{ $meta['color'] }}-700 border border-{{ $meta['color'] }}-200 rounded-full text-xs">{{ $item }}</span>
                    @endforeach
                </div>
            </div>
            @endif
            @endforeach

            {{-- Social links --}}
            @php $socials = array_filter(['Facebook'=>$profile->facebook_url,'Instagram'=>$profile->instagram_url,'LinkedIn'=>$profile->linkedin_url,'YouTube'=>$profile->youtube_url]); @endphp
            @if(!empty($socials))
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Social Links</p>
                <div class="flex flex-wrap gap-3">
                    @foreach($socials as $name => $url)
                    <a href="{{ $url }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 text-sm text-blue-600 hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"/></svg>
                        {{ $name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>

    {{-- Institution Documents --}}
    <div class="eims-card p-6">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
            <div class="p-2 bg-orange-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-orange-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Documents</h3>
            <span class="ml-2 text-xs text-slate-400">{{ $institution->documents->count() }} document(s)</span>
            <div class="ml-auto">
                <a href="{{ route('admin.institution-documents.create', ['institution_id' => $institution->id]) }}"
                   class="btn btn-primary text-xs py-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Upload Document
                </a>
            </div>
        </div>

        @if($institution->documents->isEmpty())
            <p class="text-sm text-slate-400">No documents uploaded for this institution.</p>
        @else
        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>File</th>
                        <th>Status</th>
                        <th>Uploaded</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($institution->documents->sortByDesc('created_at') as $doc)
                    <tr>
                        <td><span class="badge badge-blue">{{ \App\Models\InstitutionDocument::DOCUMENT_TYPES[$doc->document_type] ?? $doc->document_type }}</span></td>
                        <td class="text-sm text-slate-700 max-w-xs truncate">{{ $doc->title }}</td>
                        <td>
                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank" rel="noopener"
                               class="inline-flex items-center gap-1 text-xs text-blue-600 hover:underline">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                                View
                            </a>
                        </td>
                        <td>
                            @if($doc->status === 'active')
                                <span class="badge badge-green">Active</span>
                            @elseif($doc->status === 'expired')
                                <span class="badge badge-red">Expired</span>
                            @else
                                <span class="badge badge-yellow">Inactive</span>
                            @endif
                        </td>
                        <td class="text-sm text-slate-500">{{ $doc->created_at->format('d M Y') }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.institution-documents.show', $doc) }}" class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.institution-documents.edit', $doc) }}" class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($institution->documents->count() > 5)
        <div class="pt-3 text-center">
            <a href="{{ route('admin.institution-documents.index', ['institution_id' => $institution->id]) }}"
               class="text-sm text-blue-600 hover:underline">View all documents →</a>
        </div>
        @endif
        @endif
    </div>
    {{-- Institution Programs --}}
    <div class="eims-card overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
            <div class="p-2 bg-indigo-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Programs</h3>
            <span class="ml-1 text-xs text-slate-400">{{ $institution->programs->count() }} program(s)</span>
            <div class="ml-auto">
                <a href="{{ route('admin.institution-programs.create', ['institution_id' => $institution->id]) }}"
                   class="btn btn-primary text-xs py-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Add Program
                </a>
            </div>
        </div>

        @if($institution->programs->isEmpty())
            <div class="text-center py-10 text-slate-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                <p class="text-sm mb-3">No programs assigned yet.</p>
                <a href="{{ route('admin.institution-programs.create', ['institution_id' => $institution->id]) }}"
                   class="btn btn-primary text-xs">Add First Program</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Program</th>
                            <th>Title</th>
                            <th>Total Fee</th>
                            <th>Seats</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($institution->programs as $ip)
                        <tr>
                            <td>
                                <div class="font-medium text-slate-800 text-sm">{{ $ip->program->name ?? '—' }}</div>
                                @if($ip->program?->level)
                                    <span class="badge badge-blue text-xs">{{ $ip->program->level }}</span>
                                @endif
                            </td>
                            <td class="text-sm text-slate-600 max-w-xs truncate">{{ $ip->title ?? '—' }}</td>
                            <td class="text-sm text-slate-700">{{ $ip->total_fee !== null ? number_format($ip->total_fee, 2) : '—' }}</td>
                            <td class="text-sm text-slate-600">
                                {{ $ip->available_seats !== null ? $ip->available_seats . ' / ' . ($ip->total_seats ?? '?') : '—' }}
                            </td>
                            <td>
                                @php $c = match($ip->status) { 'open'=>'green','upcoming'=>'blue','suspended'=>'orange',default=>'red' }; @endphp
                                <span class="badge badge-{{ $c }}">{{ \App\Models\InstitutionProgram::STATUSES[$ip->status] ?? $ip->status }}</span>
                            </td>
                            <td>
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.institution-programs.show', $ip) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.institution-programs.edit', $ip) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-slate-100 text-right">
                <a href="{{ route('admin.institution-programs.index', ['institution_id' => $institution->id]) }}"
                   class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    View all programs for this institution →
                </a>
            </div>
        @endif
    </div>

    {{-- Applications --}}
    <div class="eims-card overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
            <div class="p-2 bg-blue-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2.25 4.5H6.75A2.25 2.25 0 014.5 18.25V5.75A2.25 2.25 0 016.75 3.5h7.19c.597 0 1.169.237 1.591.659l2.81 2.81c.422.422.659.994.659 1.591v9.69a2.25 2.25 0 01-2.25 2.25z"/></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Applications</h3>
            <span class="ml-1 text-xs text-slate-400">{{ $institution->applications->count() }} application(s)</span>
            <div class="ml-auto">
                <a href="{{ route('admin.applications.create', ['institution_id' => $institution->id]) }}" class="btn btn-primary text-xs py-1.5">Add Application</a>
            </div>
        </div>

        @if($institution->applications->isEmpty())
            <div class="text-center py-10 text-slate-400">
                <p class="text-sm mb-3">No applications created yet.</p>
                <a href="{{ route('admin.applications.create', ['institution_id' => $institution->id]) }}" class="btn btn-primary text-xs">Add First Application</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Application</th>
                            <th>Student</th>
                            <th>Program</th>
                            <th>Scholarship</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($institution->applications->take(5) as $application)
                            <tr>
                                <td class="font-medium text-slate-800">{{ $application->application_number }}</td>
                                <td>{{ $application->student->name ?? '-' }}</td>
                                <td>{{ $application->institutionProgram?->title ?: ($application->institutionProgram?->program?->name ?? '-') }}</td>
                                <td>{{ $application->scholarship->title ?? '-' }}</td>
                                <td><span class="badge">{{ \App\Models\Application::STATUSES[$application->status] ?? $application->status }}</span></td>
                                <td>
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.applications.show', $application) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.applications.edit', $application) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-slate-100 text-right">
                <a href="{{ route('admin.applications.index', ['institution_id' => $institution->id]) }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    View all applications for this institution →
                </a>
            </div>
        @endif
    </div>

    {{-- Scholarships --}}
    <div class="eims-card overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-100">
            <div class="p-2 bg-emerald-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Scholarships</h3>
            <span class="ml-1 text-xs text-slate-400">{{ $institution->scholarships->count() }} scholarship(s)</span>
            <div class="ml-auto">
                <a href="{{ route('admin.scholarships.create', ['institution_id' => $institution->id]) }}" class="btn btn-primary text-xs py-1.5">
                    Add Scholarship
                </a>
            </div>
        </div>

        @if($institution->scholarships->isEmpty())
            <div class="text-center py-10 text-slate-400">
                <p class="text-sm mb-3">No scholarships created yet.</p>
                <a href="{{ route('admin.scholarships.create', ['institution_id' => $institution->id]) }}" class="btn btn-primary text-xs">Add First Scholarship</a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>Scholarship</th>
                            <th>Program</th>
                            <th>Type</th>
                            <th>Benefit</th>
                            <th>Dates</th>
                            <th>Status</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($institution->scholarships as $scholarship)
                            <tr>
                                <td>
                                    <div class="font-medium text-slate-800 text-sm">{{ $scholarship->title }}</div>
                                    <div class="text-xs text-slate-400 font-mono">{{ $scholarship->slug }}</div>
                                </td>
                                <td class="text-sm text-slate-600">
                                    {{ $scholarship->institutionProgram?->title ?: ($scholarship->institutionProgram?->program?->name ?? 'Institution-wide') }}
                                </td>
                                <td class="text-sm">{{ \App\Models\Scholarship::TYPES[$scholarship->type] ?? $scholarship->type }}</td>
                                <td class="text-sm">
                                    {{ \App\Models\Scholarship::BENEFIT_TYPES[$scholarship->benefit_type] ?? $scholarship->benefit_type }}
                                    @if($scholarship->benefit_value !== null)
                                        <span class="text-slate-400">({{ number_format((float) $scholarship->benefit_value, 2) }})</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">
                                    {{ $scholarship->start_date?->format('d M Y') ?? '-' }} - {{ $scholarship->end_date?->format('d M Y') ?? '-' }}
                                </td>
                                <td><span class="badge">{{ \App\Models\Scholarship::STATUSES[$scholarship->status] ?? $scholarship->status }}</span></td>
                                <td>
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.scholarships.show', $scholarship) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.scholarships.edit', $scholarship) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-3 border-t border-slate-100 text-right">
                <a href="{{ route('admin.scholarships.index', ['institution_id' => $institution->id]) }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                    View all scholarships for this institution →
                </a>
            </div>
        @endif
    </div>

</div>
@endsection
