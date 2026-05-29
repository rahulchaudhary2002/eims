@extends('admin.layouts.app')
@section('title', 'Institution Profiles')
@section('page-title', 'Institution Profiles')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Institution Profiles" subtitle="Manage institution profile details"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Institution Profiles']]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-profiles.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Profile
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    {{-- Filters --}}
    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.institution-profiles.index') }}" class="flex flex-wrap gap-3 items-end">

            {{-- Institution --}}
            @if($institutions->count() > 1)
            <div class="w-56">
                <label class="form-label text-xs">Institution</label>
                <select name="institution_id" class="form-control">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $inst)
                        <option value="{{ $inst->id }}" {{ request('institution_id') == $inst->id ? 'selected' : '' }}>
                            {{ $inst->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Has Hostel --}}
            <div class="w-36">
                <label class="form-label text-xs">Hostel</label>
                <select name="has_hostel" class="form-control">
                    <option value="">All</option>
                    <option value="1" {{ request('has_hostel') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('has_hostel') === '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            {{-- Has Transportation --}}
            <div class="w-40">
                <label class="form-label text-xs">Transportation</label>
                <select name="has_transportation" class="form-control">
                    <option value="">All</option>
                    <option value="1" {{ request('has_transportation') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('has_transportation') === '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            {{-- Has Scholarship --}}
            <div class="w-40">
                <label class="form-label text-xs">Scholarship</label>
                <select name="has_scholarship" class="form-control">
                    <option value="">All</option>
                    <option value="1" {{ request('has_scholarship') === '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ request('has_scholarship') === '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['institution_id','has_hostel','has_transportation','has_scholarship']))
                <a href="{{ route('admin.institution-profiles.index') }}" class="btn btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    Clear
                </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="eims-card overflow-hidden">
        @if($profiles->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21"/></svg>
            <p class="text-sm font-medium">No institution profiles found</p>
            @if(request()->hasAny(['institution_id','has_hostel','has_transportation','has_scholarship']))
            <p class="text-xs mt-1">Try adjusting your filters.</p>
            @endif
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>Institution</th>
                        <th class="text-center">Facilities</th>
                        <th class="text-center">Achievements</th>
                        <th>Amenities</th>
                        <th>Social</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($profiles as $profile)
                    <tr>
                        <td>
                            <a href="{{ route('admin.institutions.show', $profile->institution) }}"
                               class="font-medium text-blue-600 hover:underline text-sm">
                                {{ $profile->institution->name }}
                            </a>
                        </td>
                        <td class="text-center">
                            <span class="text-sm text-slate-500">{{ count($profile->facilities ?? []) }}</span>
                        </td>
                        <td class="text-center">
                            <span class="text-sm text-slate-500">{{ count($profile->achievements ?? []) }}</span>
                        </td>
                        <td>
                            <div class="flex flex-wrap gap-1">
                                @foreach([
                                    'has_hostel' => 'Hostel',
                                    'has_library' => 'Library',
                                    'has_lab' => 'Lab',
                                    'has_transportation' => 'Transport',
                                    'has_scholarship' => 'Scholarship',
                                ] as $field => $label)
                                    @if($profile->$field)
                                    <span class="badge badge-green text-xs">{{ $label }}</span>
                                    @endif
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="flex gap-2">
                                @if($profile->facebook_url)
                                    <a href="{{ $profile->facebook_url }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-blue-600" title="Facebook">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                    </a>
                                @endif
                                @if($profile->instagram_url)
                                    <a href="{{ $profile->instagram_url }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-pink-600" title="Instagram">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                                    </a>
                                @endif
                                @if($profile->linkedin_url)
                                    <a href="{{ $profile->linkedin_url }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-blue-700" title="LinkedIn">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                    </a>
                                @endif
                                @if($profile->youtube_url)
                                    <a href="{{ $profile->youtube_url }}" target="_blank" rel="noopener" class="text-slate-400 hover:text-red-600" title="YouTube">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                        <td class="text-right">
                            <div class="flex justify-end gap-1">
                                <a href="{{ route('admin.institution-profiles.show', $profile) }}" class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.institution-profiles.edit', $profile) }}" class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                </a>
                                <form action="{{ route('admin.institution-profiles.destroy', $profile) }}" method="POST"
                                      onsubmit="return confirm('Delete this profile?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($profiles->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $profiles->links() }}
        </div>
        @endif
        @endif
    </div>

</div>
@endsection
