@extends('admin.layouts.app')
@section('title', 'Scholarships')
@section('page-title', 'Scholarships')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Scholarships"
        subtitle="Manage scholarship and cashback offers."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Scholarships'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.scholarships.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Scholarship
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.scholarships.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
            <div>
                <label class="form-label text-xs">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Title or slug">
            </div>
            <div>
                <label class="form-label text-xs">Institution</label>
                <select name="institution_id" class="form-control">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $institution)
                        <option value="{{ $institution->id }}" {{ request('institution_id') == $institution->id ? 'selected' : '' }}>{{ $institution->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Institution Program</label>
                <select name="institution_program_id" class="form-control">
                    <option value="">All Programs</option>
                    @foreach($institutionPrograms as $institutionProgram)
                        <option value="{{ $institutionProgram->id }}" {{ request('institution_program_id') == $institutionProgram->id ? 'selected' : '' }}>
                            {{ $institutionProgram->institution->name ?? 'Institution' }} - {{ $institutionProgram->title ?: ($institutionProgram->program->name ?? 'Program') }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Type</label>
                <select name="type" class="form-control">
                    <option value="">All Types</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Benefit Type</label>
                <select name="benefit_type" class="form-control">
                    <option value="">All Benefits</option>
                    @foreach($benefitTypes as $value => $label)
                        <option value="{{ $value }}" {{ request('benefit_type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Status</label>
                <select name="status" class="form-control">
                    <option value="">All Statuses</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Starts From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div>
                <label class="form-label text-xs">Ends By</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="flex gap-2 md:col-span-3 xl:col-span-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Scholarship</th>
                        <th>Institution</th>
                        <th>Type</th>
                        <th>Benefit</th>
                        <th>Slots</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($scholarships as $scholarship)
                        <tr>
                            <td>
                                <a href="{{ route('admin.scholarships.show', $scholarship) }}" class="font-semibold text-blue-600 hover:underline">{{ $scholarship->title }}</a>
                                <div class="text-xs text-slate-400 font-mono">{{ $scholarship->slug }}</div>
                            </td>
                            <td>
                                <div class="font-medium text-slate-800">{{ $scholarship->institution->name ?? '-' }}</div>
                                <div class="text-xs text-slate-400">
                                    {{ $scholarship->institutionProgram?->title ?: ($scholarship->institutionProgram?->program?->name ?? 'Institution-wide') }}
                                </div>
                            </td>
                            <td>{{ $types[$scholarship->type] ?? $scholarship->type }}</td>
                            <td>
                                <div>{{ $benefitTypes[$scholarship->benefit_type] ?? $scholarship->benefit_type }}</div>
                                <div class="text-xs text-slate-400">{{ $scholarship->benefit_value !== null ? number_format((float) $scholarship->benefit_value, 2) : '-' }}</div>
                            </td>
                            <td>{{ $scholarship->used_slots ?? 0 }} / {{ $scholarship->total_slots ?? 'Unlimited' }}</td>
                            <td class="text-xs text-slate-500">
                                {{ $scholarship->start_date?->format('d M Y') ?? '-' }}<br>
                                {{ $scholarship->end_date?->format('d M Y') ?? '-' }}
                            </td>
                            <td><span class="badge">{{ $statuses[$scholarship->status] ?? $scholarship->status }}</span></td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.scholarships.show', $scholarship) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.scholarships.edit', $scholarship) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.scholarships.destroy', $scholarship) }}" method="POST" onsubmit="return confirm('Delete this scholarship?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-slate-400 py-10">No scholarships found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($scholarships->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $scholarships->links() }}</div>
        @endif
    </div>
</div>
@endsection
