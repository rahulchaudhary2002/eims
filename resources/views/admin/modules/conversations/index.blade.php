@extends('admin.layouts.app')

@section('title', 'Conversations')

@section('content')
<div class="space-y-5">
<x-admin.page-header title="Conversations" subtitle="Manage conversations between students and institutions."
    :breadcrumbs="[['label'=>'Dashboard','route'=>'admin.dashboard'],['label'=>'Conversations']]">
    <x-slot:actions>
        <a href="{{ route('admin.conversations.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            New Conversation
        </a>
    </x-slot:actions>
</x-admin.page-header>

{{-- Filters --}}
<div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.conversations.index') }}" class="flex flex-wrap gap-3 items-end">
            <div>
                <label class="form-label text-xs">Institution</label>
                <select name="institution_id" class="form-control w-56">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $inst)
                        <option value="{{ $inst->id }}" {{ request('institution_id') == $inst->id ? 'selected' : '' }}>
                            {{ $inst->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Student</label>
                <select name="student_id" class="form-control w-56">
                    <option value="">All Students</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ request('student_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Type</label>
                <select name="type" class="form-control w-44">
                    <option value="">All Types</option>
                    @foreach($types as $value => $label)
                        <option value="{{ $value }}" {{ request('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control w-44">
            </div>
            <div>
                <label class="form-label text-xs">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control w-44">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
</div>

{{-- Table --}}
<div class="eims-card overflow-hidden">
        @if($conversations->isEmpty())
            <x-admin.empty-state title="No conversations found." description="Try changing the filters or start a conversation." />
        @else
            <div class="eims-table-wrapper">
                <table class="eims-table w-full">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Institution</th>
                            <th>Type</th>
                            <th>Started</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conversations as $conversation)
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $conversation->id }}</td>
                                <td class="text-sm">
                                    @if($conversation->student)
                                        <a href="{{ route('admin.students.show', $conversation->student) }}" class="text-blue-600 hover:underline">{{ $conversation->student->name }}</a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="text-sm">
                                    @if($conversation->institution)
                                        <a href="{{ route('admin.institutions.show', $conversation->institution) }}" class="text-blue-600 hover:underline">{{ $conversation->institution->name }}</a>
                                    @else
                                        <span class="text-slate-400">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge">{{ \App\Models\Conversation::TYPES[$conversation->type] ?? $conversation->type }}</span>
                                </td>
                                <td class="text-xs text-slate-500">{{ $conversation->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.conversations.show', $conversation) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.conversations.edit', $conversation) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('admin.conversations.destroy', $conversation) }}" class="inline"
                                              onsubmit="return confirm('Delete this conversation?')">
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
            <div class="px-6 py-4 border-t border-slate-100">
                {{ $conversations->links() }}
            </div>
        @endif
</div>
</div>
@endsection
