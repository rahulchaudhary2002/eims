@extends('admin.layouts.app')
@section('title', 'Inquiry — ' . $inquiry->name)
@section('page-title', 'Inquiry Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="{{ $inquiry->name }}"
        subtitle="Inquiry Details"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Inquiries', 'route' => 'admin.inquiries.index'],
            ['label' => $inquiry->name],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.inquiries.edit', $inquiry) }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                Edit
            </a>
            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
                Back
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Main Details --}}
        <div class="lg:col-span-2 space-y-5">

            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Contact Details</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Name</dt>
                        <dd class="mt-1 font-semibold text-slate-800">{{ $inquiry->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Email</dt>
                        <dd class="mt-1"><a href="mailto:{{ $inquiry->email }}" class="text-blue-600 hover:underline">{{ $inquiry->email }}</a></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Phone</dt>
                        <dd class="mt-1 text-slate-700">{{ $inquiry->phone ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Source</dt>
                        <dd class="mt-1 text-slate-700">{{ \App\Models\Inquiry::SOURCES[$inquiry->source] ?? ($inquiry->source ?: '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Linked Student</dt>
                        <dd class="mt-1">
                            @if($inquiry->student)
                                <a href="{{ route('admin.students.show', $inquiry->student) }}" class="text-blue-600 hover:underline">
                                    {{ $inquiry->student->name }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</dt>
                        <dd class="mt-1 text-slate-500 text-xs">{{ $inquiry->created_at->format('d M Y, H:i') }}</dd>
                    </div>
                </dl>
            </div>

            <div class="eims-card p-6">
                <h3 class="text-base font-semibold text-slate-800 mb-4">Program Interest</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Institution</dt>
                        <dd class="mt-1">
                            @if($inquiry->institution)
                                <a href="{{ route('admin.institutions.show', $inquiry->institution) }}" class="text-blue-600 hover:underline">
                                    {{ $inquiry->institution->name }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-slate-500 uppercase tracking-wide">Program</dt>
                        <dd class="mt-1 text-slate-700">
                            @if($inquiry->institutionProgram)
                                <a href="{{ route('admin.institution-programs.show', $inquiry->institutionProgram) }}" class="text-blue-600 hover:underline">
                                    {{ $inquiry->institutionProgram->title ?: ($inquiry->institutionProgram->program->name ?? '—') }}
                                </a>
                            @else
                                <span class="text-slate-400">—</span>
                            @endif
                        </dd>
                    </div>
                </dl>

                @if($inquiry->message)
                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <h4 class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Message</h4>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $inquiry->message }}</p>
                    </div>
                @endif
            </div>

            {{-- Notes Timeline --}}
            <div class="eims-card overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <h3 class="text-base font-semibold text-slate-800">Notes</h3>
                    <span class="text-xs text-slate-400">{{ $inquiry->notes->count() }} note(s)</span>
                </div>

                {{-- Quick Add Form --}}
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <form action="{{ route('admin.lead-notes.store') }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="inquiry_id" value="{{ $inquiry->id }}">
                        <input type="hidden" name="user_id" value="{{ auth('web')->id() }}">
                        <input type="hidden" name="redirect_to" value="inquiry">
                        <textarea name="note" rows="3" required
                            class="form-control text-sm w-full @error('note') is-invalid @enderror"
                            placeholder="Add a note about this inquiry..."></textarea>
                        @error('note') <p class="form-error">{{ $message }}</p> @enderror
                        <div class="flex justify-end">
                            <button type="submit" class="btn btn-primary text-sm">Add Note</button>
                        </div>
                    </form>
                </div>

                {{-- Notes List --}}
                @if($inquiry->notes->isEmpty())
                    <div class="px-6 py-8 text-center text-slate-400 text-sm">No notes yet. Use the form above to add the first note.</div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($inquiry->notes as $note)
                            <div class="px-6 py-4">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="min-w-0 flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-sm font-medium text-slate-700">{{ $note->user->name ?? 'Unknown' }}</span>
                                            <span class="text-xs text-slate-400">{{ $note->created_at->format('d M Y, H:i') }}</span>
                                            @if($note->updated_at->ne($note->created_at))
                                                <span class="text-xs text-slate-300 italic">edited</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $note->note }}</p>
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <a href="{{ route('admin.lead-notes.edit', $note) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                        <form action="{{ route('admin.lead-notes.destroy', $note) }}" method="POST" onsubmit="return confirm('Delete this note?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon btn-icon-delete" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($inquiry->notes->count() > 8)
                        <div class="px-6 py-3 border-t border-slate-100 text-right">
                            <a href="{{ route('admin.lead-notes.index', ['inquiry_id' => $inquiry->id]) }}" class="text-sm text-blue-600 hover:underline">
                                View all notes →
                            </a>
                        </div>
                    @endif
                @endif
            </div>

            {{-- Follow Ups --}}
            <div class="eims-card overflow-hidden">
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                    <div class="flex items-center gap-2">
                        <h3 class="text-base font-semibold text-slate-800">Follow Ups</h3>
                        <span class="text-xs text-slate-400">{{ $inquiry->followUps->count() }} scheduled</span>
                    </div>
                    <a href="{{ route('admin.lead-follow-ups.create', ['inquiry_id' => $inquiry->id]) }}" class="btn btn-primary text-xs py-1.5">
                        Schedule
                    </a>
                </div>

                @if($inquiry->followUps->isEmpty())
                    <div class="px-6 py-6 text-center text-slate-400 text-sm">No follow-ups scheduled yet.</div>
                @else
                    <div class="divide-y divide-slate-100">
                        @foreach($inquiry->followUps as $followUp)
                            <div class="px-6 py-3 flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="text-sm {{ $followUp->follow_up_at->isPast() && $followUp->status === 'pending' ? 'text-red-600 font-semibold' : 'text-slate-700 font-medium' }}">
                                            {{ $followUp->follow_up_at->format('d M Y, H:i') }}
                                        </span>
                                        <span class="badge">{{ \App\Models\LeadFollowUp::STATUSES[$followUp->status] ?? $followUp->status }}</span>
                                        @if($followUp->follow_up_at->isPast() && $followUp->status === 'pending')
                                            <span class="text-xs text-red-500 font-medium">Overdue</span>
                                        @endif
                                    </div>
                                    @if($followUp->assignedTo)
                                        <p class="text-xs text-slate-400 mt-0.5">Assigned to {{ $followUp->assignedTo->name }}</p>
                                    @endif
                                    @if($followUp->remarks)
                                        <p class="text-xs text-slate-600 mt-1 line-clamp-2">{{ $followUp->remarks }}</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-1 shrink-0">
                                    <a href="{{ route('admin.lead-follow-ups.show', $followUp) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.lead-follow-ups.edit', $followUp) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if($inquiry->followUps->count() > 5)
                        <div class="px-6 py-3 border-t border-slate-100 text-right">
                            <a href="{{ route('admin.lead-follow-ups.index', ['inquiry_id' => $inquiry->id]) }}" class="text-sm text-blue-600 hover:underline">
                                View all follow-ups →
                            </a>
                        </div>
                    @endif
                @endif
            </div>

        </div>

        {{-- Status + Assigned --}}
        <div class="space-y-5">
            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Status</h3>
                <span class="badge text-sm">{{ \App\Models\Inquiry::STATUSES[$inquiry->status] ?? $inquiry->status }}</span>

                <form action="{{ route('admin.inquiries.update-status', $inquiry) }}" method="POST" class="mt-4 space-y-2">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control text-sm">
                        @foreach(\App\Models\Inquiry::STATUSES as $value => $label)
                            <option value="{{ $value }}" {{ $inquiry->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-primary w-full text-sm">Update Status</button>
                </form>
            </div>

            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Assignment</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-xs text-slate-400 mb-0.5">Assigned To</dt>
                        <dd class="font-medium text-slate-800">{{ $inquiry->assignedTo->name ?? 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-slate-400 mb-0.5">Last Contacted</dt>
                        <dd class="text-slate-700">{{ $inquiry->last_contacted_at?->format('d M Y, H:i') ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            <div class="eims-card p-5">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.inquiries.edit', $inquiry) }}" class="btn btn-secondary w-full text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        Edit Inquiry
                    </a>
                    <form action="{{ route('admin.inquiries.destroy', $inquiry) }}" method="POST" onsubmit="return confirm('Delete this inquiry? This cannot be undone.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn w-full text-sm text-red-600 border border-red-200 hover:bg-red-50">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            Delete Inquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
