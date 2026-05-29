@extends('admin.layouts.app')
@section('title', $program->name)
@section('page-title', 'Program')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="{{ $program->name }}"
        :subtitle="($program->faculty->name ?? '—') . ($program->level ? ' · ' . $program->level : '')"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Programs','route'=>'admin.programs.index'],
            ['label'=>$program->name],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.programs.edit', $program) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            @if($program->faculty)
            <a href="{{ route('admin.faculties.show', $program->faculty) }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                View Faculty
            </a>
            @endif
            <a href="{{ route('admin.programs.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Left sidebar --}}
        <div class="space-y-5">

            {{-- Status card --}}
            <div class="eims-card p-6 space-y-4">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Status</h3>
                <div>
                    @if($program->is_active)
                        <span class="badge badge-green">Active</span>
                    @else
                        <span class="badge badge-red">Inactive</span>
                    @endif
                </div>
                <form action="{{ route('admin.programs.update-status', $program) }}" method="POST" class="space-y-2">
                    @csrf @method('PATCH')
                    <label class="form-label text-xs">Change Status</label>
                    <div class="flex gap-2">
                        <select name="is_active" class="form-control text-sm">
                            <option value="1" {{ $program->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$program->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="btn btn-primary text-xs px-3 whitespace-nowrap">Update</button>
                    </div>
                </form>
            </div>

            {{-- Faculty card --}}
            @if($program->faculty)
            <div class="eims-card p-6 space-y-3">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">Faculty</h3>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-sm shrink-0">
                        {{ mb_strtoupper(mb_substr($program->faculty->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm">{{ $program->faculty->name }}</p>
                        @if($program->faculty->is_active)
                            <span class="badge badge-green text-xs">Active</span>
                        @else
                            <span class="badge badge-red text-xs">Inactive</span>
                        @endif
                    </div>
                </div>
                <a href="{{ route('admin.faculties.show', $program->faculty) }}"
                    class="block text-center text-sm text-blue-600 hover:text-blue-800 hover:underline pt-1">
                    View faculty →
                </a>
            </div>
            @endif

            {{-- Timestamps --}}
            <div class="eims-card p-5 text-sm space-y-3">
                <div>
                    <p class="text-slate-400 text-xs mb-1">Created</p>
                    <p class="text-slate-700">{{ $program->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs mb-1">Last Updated</p>
                    <p class="text-slate-700">{{ $program->updated_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        {{-- Main --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Details --}}
            <div class="eims-card p-6 space-y-5">
                <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide pb-3 border-b border-slate-100">Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Program Name</dt>
                        <dd class="text-slate-800 font-semibold text-base">{{ $program->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Slug</dt>
                        <dd class="font-mono text-slate-600">{{ $program->slug }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Faculty</dt>
                        <dd class="text-slate-800">{{ $program->faculty->name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Level</dt>
                        <dd>
                            @if($program->level)
                                <span class="badge badge-blue">{{ $program->level }}</span>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    @if($program->description)
                    <div class="sm:col-span-2">
                        <dt class="text-slate-400 text-xs mb-1">Description</dt>
                        <dd class="text-slate-700 whitespace-pre-wrap">{{ $program->description }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Offered By (Institutions) --}}
            <div class="eims-card overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">
                        Offered By
                        <span class="ml-1 text-slate-400 font-normal">({{ $program->institutionPrograms->count() }})</span>
                    </h3>
                    <a href="{{ route('admin.institution-programs.create') }}?program_id={{ $program->id }}" class="btn btn-primary text-xs py-1.5 px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add Institution
                    </a>
                </div>
                @if($program->institutionPrograms->isEmpty())
                    <div class="text-center py-10 text-slate-400">
                        <p class="text-sm mb-3">No institutions offer this program yet.</p>
                        <a href="{{ route('admin.institution-programs.create') }}?program_id={{ $program->id }}" class="btn btn-primary text-xs">Add First Institution</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="eims-table w-full">
                            <thead>
                                <tr>
                                    <th>Institution</th>
                                    <th>Title</th>
                                    <th>Total Fee</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($program->institutionPrograms as $ip)
                                <tr>
                                    <td class="font-medium text-slate-800 text-sm">{{ $ip->institution->name ?? '—' }}</td>
                                    <td class="text-sm text-slate-600 max-w-xs truncate">{{ $ip->title ?? '—' }}</td>
                                    <td class="text-sm text-slate-700">{{ $ip->total_fee !== null ? number_format($ip->total_fee, 2) : '—' }}</td>
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
                        <a href="{{ route('admin.institution-programs.index') }}?program_id={{ $program->id }}"
                           class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                            View all institutions offering this program →
                        </a>
                    </div>
                @endif
            </div>

            {{-- Danger zone --}}
            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <p class="text-sm text-slate-600 mb-4">Permanently delete this program. This action cannot be undone.</p>
                <form action="{{ route('admin.programs.destroy', $program) }}" method="POST"
                      onsubmit="return confirm('Delete {{ addslashes($program->name) }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete Program
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
