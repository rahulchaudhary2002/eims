@extends('admin.layouts.app')
@section('title', 'Program Subjects')
@section('page-title', 'Program Subjects')

@section('content')
<div class="space-y-5">

    <x-admin.page-header
        title="Program Subjects"
        subtitle="Manage subjects for institution programs."
        :breadcrumbs="[
            ['label'=>'Dashboard','route'=>'admin.dashboard'],
            ['label'=>'Program Subjects'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.institution-program-subjects.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Subject
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    {{-- Filters --}}
    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.institution-program-subjects.index') }}" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-48">
                <label class="form-label text-xs">Search Subject</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Subject name…" class="form-control">
            </div>
            <div class="flex-1 min-w-48">
                <label class="form-label text-xs">Institution Program</label>
                <select name="institution_program_id" class="form-control">
                    <option value="">All Programs</option>
                    @foreach($institutionPrograms as $ip)
                        <option value="{{ $ip->id }}" {{ request('institution_program_id') == $ip->id ? 'selected' : '' }}>
                            {{ $ip->institution->name ?? '?' }} — {{ $ip->program->name ?? '?' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="min-w-36">
                <label class="form-label text-xs">Type</label>
                <select name="is_optional" class="form-control">
                    <option value="">All Types</option>
                    <option value="0" {{ request('is_optional') === '0' ? 'selected' : '' }}>Required</option>
                    <option value="1" {{ request('is_optional') === '1' ? 'selected' : '' }}>Optional</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.institution-program-subjects.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Institution Program</th>
                        <th>Subject Name</th>
                        <th>Type</th>
                        <th>Added</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($subjects as $subject)
                    <tr>
                        <td class="text-slate-400 text-xs">{{ $subject->id }}</td>
                        <td>
                            <div class="font-medium text-slate-800">
                                {{ $subject->institutionProgram->institution->name ?? '—' }}
                            </div>
                            <div class="text-xs text-slate-400">{{ $subject->institutionProgram->program->name ?? '—' }}</div>
                        </td>
                        <td class="font-medium text-slate-800">{{ $subject->subject_name }}</td>
                        <td>
                            @if($subject->is_optional)
                                <span class="badge badge-blue">Optional</span>
                            @else
                                <span class="badge badge-orange">Required</span>
                            @endif
                        </td>
                        <td class="text-xs text-slate-400">{{ $subject->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex items-center gap-1">
                                <a href="{{ route('admin.institution-program-subjects.show', $subject) }}" class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.641 0-8.573-3.007-9.964-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.institution-program-subjects.edit', $subject) }}" class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                </a>
                                <form action="{{ route('admin.institution-program-subjects.destroy', $subject) }}" method="POST"
                                      onsubmit="return confirm('Delete this subject?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-slate-400 py-10">No subjects found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subjects->hasPages())
        <div class="px-4 py-3 border-t border-slate-100">
            {{ $subjects->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
