@extends('admin.layouts.app')
@section('title', 'Programs')
@section('page-title', 'Programs')
@section('content')
<div class="space-y-5">
    <x-admin.page-header title="Programs" subtitle="Manage all academic programs"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Programs']]">
        <x-slot:actions>
            <a href="{{ route('admin.program.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Program
            </a>
        </x-slot:actions>
    </x-admin.page-header>
    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />
    <div class="eims-card p-0 overflow-hidden">
        <div class="eims-table-wrapper">
            <table class="eims-table">
                <thead><tr>
                    <th>Name</th><th>Code</th><th>Category</th><th>Level</th><th>Affiliation</th><th>Fee</th><th>Duration</th><th>Status</th><th class="text-right">Actions</th>
                </tr></thead>
                <tbody>
                    @forelse($programs as $program)
                    <tr>
                        <td class="font-medium text-slate-800">{{ $program->name }}</td>
                        <td>@if($program->code)<span class="badge badge-secondary font-mono">{{ $program->code }}</span>@else<span class="text-slate-400">-</span>@endif</td>
                        <td class="text-slate-600">{{ $program->category?->name ?? '-' }}</td>
                        <td class="text-slate-600">{{ $program->level?->name ?? '-' }}</td>
                        <td class="text-slate-600">{{ $program->affiliation?->name ?? '-' }}</td>
                        <td class="text-slate-600">NPR {{ number_format($program->fee) }}</td>
                        <td class="text-slate-600">{{ $program->duration ?? '-' }}</td>
                        <td><x-admin.status-badge :status="$program->is_active ? 'active' : 'inactive'" /></td>
                        <td class="actions-cell">
                            <div class="flex items-center justify-end gap-1.5">
                                <a href="{{ route('admin.program.edit', $program) }}" class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                </a>
                                <form action="{{ route('admin.program.destroy', $program) }}" method="POST" class="inline" onsubmit="return confirm('Delete this program?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="9"><x-admin.empty-state title="No programs yet" description="Create programs to be offered by institutions." :action-href="route('admin.program.create')" action-label="Add Program" /></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(method_exists($programs,'links') && $programs->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">{{ $programs->links() }}</div>
        @endif
    </div>
</div>
@endsection
