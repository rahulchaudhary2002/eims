@extends('admin.layouts.app')
@section('title', 'Lead Follow Ups')
@section('page-title', 'Lead Follow Ups')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Lead Follow Ups"
        subtitle="Scheduled follow-ups for inquiry leads."
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'admin.dashboard'],
            ['label' => 'Lead Follow Ups'],
        ]">
        <x-slot:actions>
            <a href="{{ route('admin.lead-follow-ups.create') }}" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Schedule Follow Up
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <x-admin.alert type="success" :message="session('success')" />

    <div class="eims-card p-4">
        <form method="GET" action="{{ route('admin.lead-follow-ups.index') }}" class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-3 items-end">
            <div>
                <label class="form-label text-xs">Inquiry</label>
                <select name="inquiry_id" class="form-control">
                    <option value="">All Inquiries</option>
                    @foreach($inquiries as $inquiry)
                        <option value="{{ $inquiry->id }}" {{ request('inquiry_id') == $inquiry->id ? 'selected' : '' }}>
                            {{ $inquiry->name }} - {{ $inquiry->email }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label text-xs">Assigned To</label>
                <select name="assigned_to" class="form-control">
                    <option value="">All Users</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
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
                <label class="form-label text-xs">Follow Up From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
            </div>
            <div>
                <label class="form-label text-xs">Follow Up To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
            </div>
            <div class="flex gap-2 md:col-span-3 xl:col-span-4">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('admin.lead-follow-ups.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="eims-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="eims-table">
                <thead>
                    <tr>
                        <th>Inquiry</th>
                        <th>Follow Up At</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($followUps as $followUp)
                        <tr>
                            <td>
                                @if($followUp->inquiry)
                                    <a href="{{ route('admin.inquiries.show', $followUp->inquiry) }}" class="font-medium text-blue-600 hover:underline text-sm">
                                        {{ $followUp->inquiry->name }}
                                    </a>
                                    <div class="text-xs text-slate-400">{{ $followUp->inquiry->email }}</div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="text-sm">
                                <span class="{{ $followUp->follow_up_at->isPast() && $followUp->status === 'pending' ? 'text-red-600 font-medium' : 'text-slate-700' }}">
                                    {{ $followUp->follow_up_at->format('d M Y, H:i') }}
                                </span>
                                @if($followUp->follow_up_at->isPast() && $followUp->status === 'pending')
                                    <div class="text-xs text-red-500">Overdue</div>
                                @elseif($followUp->follow_up_at->isToday())
                                    <div class="text-xs text-amber-600">Today</div>
                                @endif
                            </td>
                            <td class="text-sm">{{ $followUp->assignedTo->name ?? '-' }}</td>
                            <td><span class="badge">{{ $statuses[$followUp->status] ?? $followUp->status }}</span></td>
                            <td class="text-sm text-slate-600 max-w-xs">
                                @if($followUp->remarks)
                                    <p class="line-clamp-2">{{ $followUp->remarks }}</p>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <a href="{{ route('admin.lead-follow-ups.show', $followUp) }}" class="btn-icon btn-icon-view" title="View">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </a>
                                    <a href="{{ route('admin.lead-follow-ups.edit', $followUp) }}" class="btn-icon btn-icon-edit" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.lead-follow-ups.destroy', $followUp) }}" method="POST" onsubmit="return confirm('Delete this follow-up?')">
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
                            <td colspan="6" class="text-center text-slate-400 py-10">No lead follow-ups found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($followUps->hasPages())
            <div class="px-4 py-3 border-t border-slate-100">{{ $followUps->links() }}</div>
        @endif
    </div>
</div>
@endsection
