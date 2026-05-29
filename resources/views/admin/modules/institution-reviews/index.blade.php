@extends('admin.layouts.app')

@section('title', 'Institution Reviews')

@section('content')
<div class="content-header">
    <div>
        <h1 class="content-title">Institution Reviews</h1>
        <p class="content-subtitle">Manage student reviews for institutions.</p>
    </div>
    <a href="{{ route('admin.institution-reviews.create') }}" class="btn btn-primary">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
        Add Review
    </a>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.institution-reviews.index') }}" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div>
                <label class="form-label">Institution</label>
                <select name="institution_id" class="form-select">
                    <option value="">All Institutions</option>
                    @foreach($institutions as $inst)
                        <option value="{{ $inst->id }}" {{ request('institution_id') == $inst->id ? 'selected' : '' }}>
                            {{ $inst->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Student</label>
                <select name="student_id" class="form-select">
                    <option value="">All Students</option>
                    @foreach($students as $st)
                        <option value="{{ $st->id }}" {{ request('student_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Rating</label>
                <select name="rating" class="form-select">
                    <option value="">All Ratings</option>
                    @foreach([1,2,3,4,5] as $r)
                        <option value="{{ $r }}" {{ request('rating') == $r ? 'selected' : '' }}>{{ $r }} Star{{ $r > 1 ? 's' : '' }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Approved</label>
                <select name="is_approved" class="form-select">
                    <option value="">All</option>
                    <option value="1" {{ request('is_approved') === '1' ? 'selected' : '' }}>Approved</option>
                    <option value="0" {{ request('is_approved') === '0' ? 'selected' : '' }}>Pending</option>
                </select>
            </div>
            <div>
                <label class="form-label">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-input">
            </div>
            <div class="flex items-end gap-2 col-span-2 md:col-span-1">
                <button type="submit" class="btn btn-primary w-full">Filter</button>
                <a href="{{ route('admin.institution-reviews.index') }}" class="btn btn-secondary w-full">Reset</a>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body p-0">
        @if($reviews->isEmpty())
            <div class="px-6 py-12 text-center text-slate-400">No reviews found.</div>
        @else
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Student</th>
                            <th>Institution</th>
                            <th>Rating</th>
                            <th>Review</th>
                            <th>Approved</th>
                            <th>Date</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reviews as $review)
                            <tr>
                                <td class="text-slate-400 text-xs">{{ $review->id }}</td>
                                <td class="text-sm">
                                    @if($review->student)
                                        <a href="{{ route('admin.students.show', $review->student) }}" class="text-blue-600 hover:underline">{{ $review->student->name }}</a>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="text-sm">
                                    <a href="{{ route('admin.institutions.show', $review->institution) }}" class="text-blue-600 hover:underline">{{ $review->institution->name }}</a>
                                </td>
                                <td>
                                    <span class="font-semibold text-amber-500">{{ $review->rating }}</span>
                                    <span class="text-slate-400 text-xs">/ 5</span>
                                </td>
                                <td class="text-sm max-w-xs truncate text-slate-600">{{ $review->review ? \Illuminate\Support\Str::limit($review->review, 60) : '—' }}</td>
                                <td>
                                    @if($review->is_approved)
                                        <span class="badge badge-success">Approved</span>
                                    @else
                                        <span class="badge badge-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="text-xs text-slate-500">{{ $review->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="flex items-center justify-center gap-1">
                                        <a href="{{ route('admin.institution-reviews.show', $review) }}" class="btn-icon btn-icon-view" title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </a>
                                        <a href="{{ route('admin.institution-reviews.edit', $review) }}" class="btn-icon btn-icon-edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                                        </a>
                                        @if(!$review->is_approved)
                                            <form method="POST" action="{{ route('admin.institution-reviews.approve', $review) }}" class="inline">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="btn-icon btn-icon-success" title="Approve">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.institution-reviews.destroy', $review) }}" class="inline"
                                              onsubmit="return confirm('Delete this review?')">
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
                {{ $reviews->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
