@extends('admin.layouts.app')
@section('title', 'Academic Records')
@section('page-title', 'Academic Records')

@section('content')
<div class="space-y-5">

    <x-admin.page-header title="Academic Records" subtitle="Manage student academic history"
        :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Academic Records']]">
        <x-slot:actions>
            <a href="{{ route('admin.student-academic-records.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Record
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />
    <x-admin.alert type="danger"  :message="session('error')" />

    {{-- Filters --}}
    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.student-academic-records.index') }}" class="flex flex-wrap gap-3 items-end">

            <div class="w-56">
                <label class="form-label text-xs">Student</label>
                <select name="student_id" class="form-control">
                    <option value="">All Students</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ request('student_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="w-44">
                <label class="form-label text-xs">Level</label>
                <select name="level" class="form-control">
                    <option value="">All Levels</option>
                    @foreach($levels as $val => $label)
                        <option value="{{ $val }}" {{ request('level') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-48">
                <label class="form-label text-xs">Board / University</label>
                <select name="board" class="form-control">
                    <option value="">All Boards</option>
                    @foreach($boards as $val => $label)
                        <option value="{{ $val }}" {{ request('board') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-28">
                <label class="form-label text-xs">Passed Year</label>
                <input type="number" name="passed_year" class="form-control"
                    value="{{ request('passed_year') }}" min="1950" max="{{ date('Y') + 1 }}"
                    placeholder="{{ date('Y') }}">
            </div>

            <div class="w-36">
                <label class="form-label text-xs">Verified</label>
                <select name="is_verified" class="form-control">
                    <option value="">All</option>
                    <option value="1" {{ request('is_verified') === '1' ? 'selected' : '' }}>Verified</option>
                    <option value="0" {{ request('is_verified') === '0' ? 'selected' : '' }}>Unverified</option>
                </select>
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.student-academic-records.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student</th>
                        <th>Level</th>
                        <th>Board</th>
                        <th>Faculty</th>
                        <th>Year</th>
                        <th>GPA / %</th>
                        <th>Verified</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td class="text-slate-400 text-sm">{{ $records->firstItem() + $loop->index }}</td>
                        <td>
                            <div class="font-medium text-slate-800">{{ $record->student->name }}</div>
                            <div class="text-xs text-slate-400">{{ $record->student->email }}</div>
                        </td>
                        <td>
                            <span class="badge badge-blue">
                                {{ \App\Models\StudentAcademicRecord::LEVELS[$record->level] ?? $record->level }}
                            </span>
                        </td>
                        <td class="text-sm">
                            {{ \App\Models\StudentAcademicRecord::BOARDS[$record->board] ?? ($record->board ?? '—') }}
                        </td>
                        <td class="text-sm">{{ $record->faculty ?? '—' }}</td>
                        <td class="text-sm">{{ $record->passed_year ?? '—' }}</td>
                        <td class="text-sm">
                            @if($record->gpa !== null)
                                <span class="font-medium">{{ number_format($record->gpa, 2) }}</span>
                                <span class="text-slate-400 text-xs"> GPA</span>
                            @endif
                            @if($record->percentage !== null)
                                @if($record->gpa !== null) <br> @endif
                                <span class="font-medium">{{ number_format($record->percentage, 2) }}%</span>
                            @endif
                            @if($record->gpa === null && $record->percentage === null)
                                <span class="text-slate-300">—</span>
                            @endif
                        </td>
                        <td>
                            @if($record->is_verified)
                                <span class="badge badge-green">Verified</span>
                            @else
                                <span class="badge badge-yellow">Pending</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center gap-1">
                                <a href="{{ route('admin.student-academic-records.show', $record) }}"
                                    class="btn-icon btn-icon-view" title="View">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </a>
                                <a href="{{ route('admin.student-academic-records.edit', $record) }}"
                                    class="btn-icon btn-icon-edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                                </a>
                                @if(!$record->is_verified)
                                <form action="{{ route('admin.student-academic-records.verify', $record) }}"
                                    method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-icon" title="Verify"
                                        style="color:#059669;background:#d1fae5;border-color:#a7f3d0;">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </button>
                                </form>
                                @endif
                                <form action="{{ route('admin.student-academic-records.destroy', $record) }}"
                                    method="POST" class="inline"
                                    onsubmit="return confirm('Delete this academic record?')">
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
                        <td colspan="9" class="py-12 text-center text-slate-400">
                            No academic records found.
                            <a href="{{ route('admin.student-academic-records.create') }}" class="text-blue-600 hover:underline ml-1">Add the first one.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($records->hasPages())
        <div class="px-6 py-4 border-t border-slate-100">
            {{ $records->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
