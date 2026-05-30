@extends('admin.layouts.app')
@section('title', $user->name)
@section('page-title', 'User Details')

@section('content')
<div class="space-y-5">

    <x-admin.page-header :title="$user->name" subtitle="User account details"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Users','route'=>'admin.users.index'],['label'=>$user->name]]">
        <x-slot:actions>
            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    {{-- Avatar Banner --}}
    <div class="eims-card p-6">
        <div class="flex items-center gap-5">
            @if($user->avatar)
            <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                 class="w-20 h-20 rounded-full object-cover border-2 border-slate-200 shrink-0">
            @else
            <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-9 h-9 text-slate-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </div>
            @endif
            <div class="min-w-0">
                <div class="flex flex-wrap items-center gap-2">
                    <h2 class="text-xl font-bold text-slate-800">{{ $user->name }}</h2>
                    @if($user->is_super_admin)
                    <span class="badge badge-red">Super Admin</span>
                    @endif
                    @if($user->is_active)
                    <span class="badge badge-green">Active</span>
                    @else
                    <span class="badge badge-red">Inactive</span>
                    @endif
                </div>
                <p class="text-sm text-slate-500 mt-0.5">{{ $user->email }}</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left: Profile Info --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="eims-card p-6">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                    <div class="p-2 bg-blue-50 rounded-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-800">Account Information</h3>
                </div>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Name</dt>
                        <dd class="mt-1 text-slate-800 font-medium">{{ $user->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Email</dt>
                        <dd class="mt-1">
                            <a href="mailto:{{ $user->email }}" class="text-blue-600 hover:underline">{{ $user->email }}</a>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Phone</dt>
                        <dd class="mt-1 text-slate-700">{{ $user->phone ?: '-' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Email Verified</dt>
                        <dd class="mt-1">
                            @if($user->email_verified_at)
                            <span class="text-emerald-600 font-medium text-sm">{{ $user->email_verified_at->format('d M Y, h:i A') }}</span>
                            @else
                            <span class="badge badge-yellow">Not Verified</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Created</dt>
                        <dd class="mt-1 text-slate-600 text-sm">{{ $user->created_at->format('d M Y, h:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">Updated</dt>
                        <dd class="mt-1 text-slate-600 text-sm">{{ $user->updated_at->format('d M Y, h:i A') }}</dd>
                    </div>
                </dl>
            </div>

        </div>

        {{-- Right Sidebar --}}
        <div class="space-y-5">

            {{-- Quick Status Update --}}
            <div class="eims-card p-5">
                <h4 class="text-sm font-semibold text-slate-700 mb-4">Quick Status Update</h4>
                <form method="POST" action="{{ route('admin.users.update-status', $user) }}">
                    @csrf @method('PATCH')
                    <div class="mb-3">
                        <label class="form-label text-xs">Status</label>
                        <select name="is_active" class="form-control">
                            <option value="1" {{ $user->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$user->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Update Status</button>
                </form>
            </div>

            {{-- Settings Summary --}}
            <div class="eims-card p-5">
                <h4 class="text-sm font-semibold text-slate-700 mb-4">Settings</h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Super Admin</span>
                        @if($user->is_super_admin)
                        <span class="badge badge-red">Yes</span>
                        @else
                        <span class="badge badge-blue">No</span>
                        @endif
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-slate-600">Active</span>
                        @if($user->is_active)
                        <span class="badge badge-green">Yes</span>
                        @else
                        <span class="badge badge-red">No</span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="eims-card p-5 border border-red-100">
                <h4 class="text-sm font-semibold text-red-600 mb-3">Danger Zone</h4>
                <p class="text-xs text-slate-500 mb-4">This will soft-delete the user. The record can be restored later.</p>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                      onsubmit="return confirm('Delete {{ $user->name }}? This action can be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn w-full border border-red-300 text-red-600 hover:bg-red-50 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete User
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- Assigned Institutions --}}
    <div class="eims-card p-6">
        <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
            <div class="p-2 bg-indigo-50 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>
            </div>
            <h3 class="text-base font-semibold text-slate-800">Assigned Institutions</h3>
            <span class="ml-auto text-xs text-slate-400">{{ $user->institutions->count() }} institution(s)</span>
        </div>

        @if($user->institutions->isEmpty())
            <p class="text-sm text-slate-400">No institutions assigned to this user.</p>
        @else
        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>Institution</th>
                        <th>Role</th>
                        <th>Position</th>
                        <th>Joined</th>
                        <th class="text-center">Active</th>
                        <th class="text-center">Primary</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->institutions as $inst)
                    <tr>
                        <td>
                            <a href="{{ route('admin.institutions.show', $inst) }}"
                               class="font-medium text-blue-600 hover:underline">{{ $inst->name }}</a>
                        </td>
                        <td>{{ \App\Models\UserInstitution::ROLES[$inst->pivot->role] ?? $inst->pivot->role ?? '-' }}</td>
                        <td>{{ $inst->pivot->position ?: '-' }}</td>
                        <td class="text-sm text-slate-500">
                            {{ $inst->pivot->joined_at ? \Carbon\Carbon::parse($inst->pivot->joined_at)->format('d M Y') : '-' }}
                        </td>
                        <td class="text-center">
                            @if($inst->pivot->is_active)
                                <span class="badge badge-green">Yes</span>
                            @else
                                <span class="badge badge-red">No</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($inst->pivot->is_primary)
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-500 mx-auto" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/></svg>
                            @else
                                <span class="text-slate-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>
@endsection
