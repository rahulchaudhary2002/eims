@extends('admin.layouts.app')
@section('title', 'Institutions')
@section('page-title', 'Institutions')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Institutions" subtitle="Manage educational institutions"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Institutions']]">
        <x-slot:actions>
            <a href="{{ route('admin.institutions.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Institution
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    {{-- Filters --}}
    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.institutions.index') }}" class="flex flex-wrap gap-3 items-end">

            {{-- Search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="form-label text-xs">Search</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control pl-9" placeholder="Name, email, phone, code…">
                </div>
            </div>

            {{-- Type --}}
            <div class="w-40">
                <label class="form-label text-xs">Type</label>
                <select name="type" class="form-control">
                    <option value="">All Types</option>
                    @foreach($types as $val => $label)
                    <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status --}}
            <div class="w-36">
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-control">
                    <option value="">All</option>
                    @foreach($statuses as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Verified --}}
            <div class="w-36">
                <label class="form-label text-xs">Verified</label>
                <select name="is_verified" class="form-control">
                    <option value="">All</option>
                    <option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>

            {{-- Featured --}}
            <div class="w-36">
                <label class="form-label text-xs">Featured</label>
                <select name="is_featured" class="form-control">
                    <option value="">All</option>
                    <option value="1" {{ request('is_featured') === '1' ? 'selected' : '' }}>Featured</option>
                    <option value="0" {{ request('is_featured') === '0' ? 'selected' : '' }}>Not Featured</option>
                </select>
            </div>

            {{-- Province --}}
            @if($provinces->isNotEmpty())
            <div class="w-40">
                <label class="form-label text-xs">Province</label>
                <select name="province" class="form-control">
                    <option value="">All</option>
                    @foreach($provinces as $p)
                    <option value="{{ $p }}" {{ request('province') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- District --}}
            @if($districts->isNotEmpty())
            <div class="w-40">
                <label class="form-label text-xs">District</label>
                <select name="district" class="form-control">
                    <option value="">All</option>
                    @foreach($districts as $d)
                    <option value="{{ $d }}" {{ request('district') === $d ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- City --}}
            @if($cities->isNotEmpty())
            <div class="w-40">
                <label class="form-label text-xs">City</label>
                <select name="city" class="form-control">
                    <option value="">All</option>
                    @foreach($cities as $c)
                    <option value="{{ $c }}" {{ request('city') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search','type','status','is_verified','is_featured','province','district','city']))
                <a href="{{ route('admin.institutions.index') }}" class="btn btn-secondary">Clear</a>
                @endif
            </div>

        </form>
    </div>

    {{-- Table --}}
    <div class="eims-card p-0 overflow-hidden">
        <div class="eims-table-wrapper">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th class="w-10">#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Code</th>
                        <th>Location</th>
                        <th>Contact</th>
                        <th class="text-center">Verified</th>
                        <th class="text-center">Featured</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($institutions as $institution)
                    <tr>
                        <td class="text-slate-400 text-sm">{{ $institution->id }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($institution->logo)
                                <img src="{{ Storage::url($institution->logo) }}" alt="" class="w-8 h-8 rounded object-contain border border-slate-100 bg-slate-50 shrink-0">
                                @else
                                <div class="w-8 h-8 rounded bg-slate-100 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-800 truncate">{{ $institution->name }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $institution->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-blue">{{ \App\Models\Institution::TYPES[$institution->type] ?? $institution->type }}</span>
                        </td>
                        <td class="text-slate-500 font-mono text-sm">{{ $institution->code ?: '—' }}</td>
                        <td class="text-sm text-slate-600">
                            @if($institution->city || $institution->district)
                            {{ implode(', ', array_filter([$institution->city, $institution->district])) }}
                            @else
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="text-sm">
                            @if($institution->email)
                            <a href="mailto:{{ $institution->email }}" class="text-blue-600 hover:underline block truncate max-w-[160px]">{{ $institution->email }}</a>
                            @endif
                            @if($institution->phone)
                            <span class="text-slate-500">{{ $institution->phone }}</span>
                            @endif
                            @if(!$institution->email && !$institution->phone)
                            <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($institution->is_verified)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500 mx-auto" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z"/></svg>
                            @else
                            <span class="text-slate-200">—</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($institution->is_featured)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-400 mx-auto" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.006 5.404.434c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.434 2.082-5.005z" clip-rule="evenodd"/></svg>
                            @else
                            <span class="text-slate-200">—</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = ['active'=>'badge-green','inactive'=>'badge-red','pending'=>'badge-yellow','suspended'=>'badge-orange'];
                            @endphp
                            <span class="badge {{ $statusColors[$institution->status] ?? 'badge' }}">
                                {{ \App\Models\Institution::STATUSES[$institution->status] ?? $institution->status }}
                            </span>
                        </td>
                        <td class="actions-cell">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.institutions.show', $institution) }}"
                                   class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.institutions.edit', $institution) }}"
                                   class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </a>

                                {{-- Quick Status --}}
                                <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                                    <button @click="open = !open"
                                        class="btn-icon text-slate-500 hover:text-slate-700 hover:bg-slate-100" title="Change Status">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </button>
                                    <div x-show="open" x-cloak
                                         class="absolute right-0 top-8 w-36 bg-white border border-slate-200 rounded-lg shadow-lg z-10 py-1 overflow-hidden">
                                        @foreach(\App\Models\Institution::STATUSES as $val => $lbl)
                                        <form action="{{ route('admin.institutions.update-status', $institution) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $val }}">
                                            <button type="submit"
                                                class="w-full text-left px-3 py-1.5 text-sm {{ $institution->status === $val ? 'font-semibold text-primary-700 bg-primary-50' : 'text-slate-600 hover:bg-slate-50' }}">
                                                {{ $lbl }}
                                            </button>
                                        </form>
                                        @endforeach
                                    </div>
                                </div>

                                <form action="{{ route('admin.institutions.destroy', $institution) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ addslashes($institution->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="py-12 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
                                <p class="text-sm font-medium">No institutions found</p>
                                @if(request()->hasAny(['search','type','status','is_verified','is_featured','province','district','city']))
                                <a href="{{ route('admin.institutions.index') }}" class="text-sm text-primary-600 hover:underline">Clear filters</a>
                                @else
                                <a href="{{ route('admin.institutions.create') }}" class="text-sm text-primary-600 hover:underline">Add the first institution</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($institutions->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">
            {{ $institutions->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
