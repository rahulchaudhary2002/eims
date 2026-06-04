@extends('admin.layouts.app')
@section('title', $faculty->name)
@section('page-title', 'Faculty')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="{{ $faculty->name }}"
        subtitle="Faculty detail"
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Faculties','route' => 'admin.faculties.index'],
            ['label'=>$faculty->name],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.faculties.edit', $faculty) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.faculties.index') }}" class="btn btn-secondary">
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
                    @if($faculty->is_active)
                        <span class="badge badge-green">Active</span>
                    @else
                        <span class="badge badge-red">Inactive</span>
                    @endif
                </div>
                <form action="{{ route('admin.faculties.update-status', $faculty) }}" method="POST" class="space-y-2">
                    @csrf @method('PATCH')
                    <label class="form-label text-xs">Change Status</label>
                    <div class="flex gap-2">
                        <select name="is_active" class="form-control text-sm">
                            <option value="1" {{ $faculty->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$faculty->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                        <button type="submit" class="btn btn-primary text-xs px-3 whitespace-nowrap">Update</button>
                    </div>
                </form>
            </div>

            {{-- Timestamps --}}
            <div class="eims-card p-5 text-sm space-y-3">
                <div>
                    <p class="text-slate-400 text-xs mb-1">Created</p>
                    <p class="text-slate-700">{{ $faculty->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-slate-400 text-xs mb-1">Last Updated</p>
                    <p class="text-slate-700">{{ $faculty->updated_at->format('d M Y, H:i') }}</p>
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
                        <dt class="text-slate-400 text-xs mb-1">Name</dt>
                        <dd class="text-slate-800 font-medium text-base">{{ $faculty->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400 text-xs mb-1">Slug</dt>
                        <dd class="font-mono text-slate-600">{{ $faculty->slug }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Programs --}}
            <div class="eims-card overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h3 class="font-semibold text-slate-700 text-sm uppercase tracking-wide">
                        Programs
                        <span class="ml-1 text-slate-400 font-normal">({{ $faculty->programs->count() }})</span>
                    </h3>
                    <a href="{{ route('admin.programs.create') }}?faculty_id={{ $faculty->id }}" class="btn btn-primary text-xs py-1.5 px-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Add Program
                    </a>
                </div>
                @if($faculty->programs->isEmpty())
                    <div class="text-center py-10 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 mx-auto mb-2 text-slate-200" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                        <p class="text-sm mb-3">No programs yet.</p>
                        <a href="{{ route('admin.programs.create') }}?faculty_id={{ $faculty->id }}" class="btn btn-primary text-xs">Add First Program</a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="eims-table w-full">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($faculty->programs as $program)
                                <tr>
                                    <td>
                                        <div class="font-medium text-slate-800">{{ $program->name }}</div>
                                        <div class="text-xs font-mono text-slate-400">{{ $program->slug }}</div>
                                    </td>
                                    <td>
                                        @if($program->level)
                                            <span class="badge badge-blue">{{ $program->level }}</span>
                                        @else
                                            <span class="text-slate-400 text-sm">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($program->is_active)
                                            <span class="badge badge-green">Active</span>
                                        @else
                                            <span class="badge badge-red">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-sm text-slate-500">{{ $program->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('admin.programs.show', $program) }}" class="btn-icon btn-icon-view" title="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </a>
                                            <a href="{{ route('admin.programs.edit', $program) }}" class="btn-icon btn-icon-edit" title="Edit">
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
                        <a href="{{ route('admin.programs.index') }}?faculty_id={{ $faculty->id }}" class="text-sm text-blue-600 hover:text-blue-800 hover:underline">
                            View all programs for this faculty →
                        </a>
                    </div>
                @endif
            </div>

            {{-- Danger zone --}}
            <div class="eims-card p-6 border border-red-100">
                <h3 class="font-semibold text-red-700 text-sm uppercase tracking-wide mb-4">Danger Zone</h3>
                <p class="text-sm text-slate-600 mb-4">Permanently delete this faculty. This action cannot be undone.</p>
                <form action="{{ route('admin.faculties.destroy', $faculty) }}" method="POST"
                      onsubmit="return confirm('Delete {{ addslashes($faculty->name) }}? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                        Delete Faculty
                    </button>
                </form>
            </div>

        </div>
    </div>

</div>
@endsection
