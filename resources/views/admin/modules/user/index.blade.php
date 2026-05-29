@extends('admin.layouts.app')
@section('title', 'Users')
@section('page-title', 'Users')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Users" subtitle="Manage web guard user accounts"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Users']]">
        <x-slot:actions>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add User
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    {{-- Filters --}}
    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-3 items-end">

            {{-- Search --}}
            <div class="flex-1 min-w-[200px]">
                <label class="form-label text-xs">Search</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control pl-9" placeholder="Name, email, phone…">
                </div>
            </div>

            {{-- Super Admin --}}
            <div class="w-40">
                <label class="form-label text-xs">Super Admin</label>
                <select name="is_super_admin" class="form-control">
                    <option value="">All</option>
                    <option value="1" {{ request('is_super_admin') === '1' ? 'selected' : '' }}>Super Admin</option>
                    <option value="0" {{ request('is_super_admin') === '0' ? 'selected' : '' }}>Non-Super Admin</option>
                </select>
            </div>

            {{-- Active --}}
            <div class="w-36">
                <label class="form-label text-xs">Status</label>
                <select name="is_active" class="form-control">
                    <option value="">All</option>
                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z"/></svg>
                    Filter
                </button>
                @if(request()->hasAny(['search','is_super_admin','is_active']))
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Clear</a>
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
                        <th>User</th>
                        <th>Phone</th>
                        <th class="text-center">Super Admin</th>
                        <th>Email Verified</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="text-slate-400 text-sm">{{ $user->id }}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt=""
                                     class="w-8 h-8 rounded-full object-cover border border-slate-200 shrink-0">
                                @else
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                                </div>
                                @endif
                                <div class="min-w-0">
                                    <p class="font-medium text-slate-800 truncate">{{ $user->name }}</p>
                                    <p class="text-xs text-slate-400 truncate">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-slate-600 text-sm">{{ $user->phone ?: '—' }}</td>
                        <td class="text-center">
                            @if($user->is_super_admin)
                            <span class="badge badge-red">Super Admin</span>
                            @else
                            <span class="text-slate-300 text-xs">—</span>
                            @endif
                        </td>
                        <td class="text-sm">
                            @if($user->email_verified_at)
                            <span class="text-emerald-600 text-xs font-medium">{{ $user->email_verified_at->format('d M Y') }}</span>
                            @else
                            <span class="badge badge-yellow">Unverified</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                            <span class="badge badge-green">Active</span>
                            @else
                            <span class="badge badge-red">Inactive</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.users.show', $user) }}" class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </a>

                                {{-- Quick status toggle --}}
                                <div class="relative" x-data="{ open: false }">
                                    <button type="button" @click="open = !open" @click.outside="open = false"
                                        class="btn-icon btn-icon-view" title="Toggle Status">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/></svg>
                                    </button>
                                    <div x-show="open" x-cloak
                                         class="absolute right-0 mt-1 w-36 bg-white rounded-lg shadow-lg border border-slate-200 z-10 py-1">
                                        <form method="POST" action="{{ route('admin.users.update-status', $user) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="is_active" value="1">
                                            <button class="w-full text-left px-4 py-1.5 text-sm text-emerald-600 hover:bg-slate-50 {{ $user->is_active ? 'font-semibold' : '' }}">
                                                Active
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.users.update-status', $user) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="is_active" value="0">
                                            <button class="w-full text-left px-4 py-1.5 text-sm text-red-600 hover:bg-slate-50 {{ !$user->is_active ? 'font-semibold' : '' }}">
                                                Inactive
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                      onsubmit="return confirm('Delete this user?')">
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
                        <td colspan="7" class="text-center py-12 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-3 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                            No users found.
                            <a href="{{ route('admin.users.create') }}" class="text-blue-600 hover:underline">Add one</a>
                            or <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">clear filters</a>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pagination --}}
    @if($users->hasPages())
    <div class="flex justify-end">
        {{ $users->links() }}
    </div>
    @endif

</div>
@endsection
