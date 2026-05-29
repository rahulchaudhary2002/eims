@extends('admin.layouts.app')

@section('title', 'Review #' . $institutionReview->id)

@section('content')
<div class="content-header">
    <div>
        <h1 class="content-title">Review #{{ $institutionReview->id }}</h1>
        <p class="content-subtitle">
            {{ $institutionReview->institution->name ?? '—' }}
            @if($institutionReview->student) · {{ $institutionReview->student->name }} @endif
        </p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.institution-reviews.edit', $institutionReview) }}" class="btn btn-secondary">Edit</a>
        <a href="{{ route('admin.institution-reviews.index') }}" class="btn btn-secondary">← Back to Reviews</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-6">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Main --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Review content --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Review Content</h2>
            </div>
            <div class="card-body">
                {{-- Star rating --}}
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex gap-0.5">
                        @for($i = 1; $i <= 5; $i++)
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 {{ $i <= $institutionReview->rating ? 'text-amber-400' : 'text-slate-200' }}"
                                 viewBox="0 0 24 24" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/>
                            </svg>
                        @endfor
                    </div>
                    <span class="text-2xl font-bold text-amber-500">{{ $institutionReview->rating }}</span>
                    <span class="text-slate-400">/ 5</span>
                </div>

                @if($institutionReview->review)
                    <div class="prose prose-sm max-w-none text-slate-700 bg-slate-50 rounded-lg p-4 border border-slate-100">
                        {{ $institutionReview->review }}
                    </div>
                @else
                    <p class="text-slate-400 italic">No written review provided.</p>
                @endif
            </div>
        </div>

    </div>

    {{-- Sidebar --}}
    <div class="space-y-6">

        {{-- Status & Actions --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Status</h2>
            </div>
            <div class="card-body">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-slate-600 text-sm">Approval</span>
                    @if($institutionReview->is_approved)
                        <span class="badge badge-success">Approved</span>
                    @else
                        <span class="badge badge-warning">Pending</span>
                    @endif
                </div>

                @if(!$institutionReview->is_approved)
                    <form method="POST" action="{{ route('admin.institution-reviews.approve', $institutionReview) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success w-full mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            Approve Review
                        </button>
                    </form>
                @endif

                <form method="POST" action="{{ route('admin.institution-reviews.destroy', $institutionReview) }}"
                      onsubmit="return confirm('Delete this review permanently?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger w-full">Delete Review</button>
                </form>
            </div>
        </div>

        {{-- Details --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Details</h2>
            </div>
            <div class="card-body space-y-3">
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Institution</p>
                    @if($institutionReview->institution)
                        <a href="{{ route('admin.institutions.show', $institutionReview->institution) }}" class="text-blue-600 hover:underline text-sm font-medium">
                            {{ $institutionReview->institution->name }}
                        </a>
                    @else
                        <span class="text-slate-400 text-sm">—</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Student</p>
                    @if($institutionReview->student)
                        <a href="{{ route('admin.students.show', $institutionReview->student) }}" class="text-blue-600 hover:underline text-sm font-medium">
                            {{ $institutionReview->student->name }}
                        </a>
                    @else
                        <span class="text-slate-400 text-sm">—</span>
                    @endif
                </div>
                <div>
                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Submitted</p>
                    <p class="text-sm text-slate-700">{{ $institutionReview->created_at->format('d M Y, H:i') }}</p>
                </div>
                @if($institutionReview->updated_at->ne($institutionReview->created_at))
                    <div>
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Last Updated</p>
                        <p class="text-sm text-slate-700">{{ $institutionReview->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
