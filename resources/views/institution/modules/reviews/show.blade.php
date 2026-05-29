@extends('institution.layouts.app')
@section('title', 'Review Details')
@section('page-title', 'Review Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Review Details"
        :subtitle="$activeInstitution->name"
        :breadcrumbs="[
            ['label' => 'Dashboard', 'route' => 'institution.dashboard'],
            ['label' => 'Reviews', 'route' => 'institution.reviews.index'],
            ['label' => 'Review #' . $record->id],
        ]">
        <x-slot:actions>
            <a href="{{ route('institution.reviews.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-1 xl:grid-cols-[360px_1fr] gap-5">
        <div class="eims-card p-6 space-y-5">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Student</p>
                <h2 class="mt-1 text-lg font-semibold text-slate-900">{{ $record->student->name ?? $record->student->full_name ?? 'Student #' . $record->student_id }}</h2>
                <p class="text-sm text-slate-500">{{ $record->student->email ?? '-' }}</p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Rating</p>
                    <div class="mt-1 flex items-center gap-1 text-amber-500">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= (int) $record->rating ? 'fas' : 'far' }} fa-star text-xs"></i>
                        @endfor
                    </div>
                    <p class="mt-1 text-sm font-semibold text-slate-700">{{ $record->rating }}/5</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Status</p>
                    <span class="mt-2 inline-flex badge {{ $record->is_approved ? 'badge-green' : 'badge-secondary' }}">
                        {{ $record->is_approved ? 'Approved' : 'Pending' }}
                    </span>
                </div>
            </div>

            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Submitted</p>
                <p class="mt-1 text-sm text-slate-700">{{ $record->created_at?->format('d M Y, h:i A') ?? '-' }}</p>
            </div>
        </div>

        <div class="eims-card p-6">
            <p class="text-xs font-medium uppercase tracking-wider text-slate-400">Review</p>
            <div class="mt-3 rounded-lg border border-slate-100 bg-slate-50 p-5 text-slate-700 leading-7 whitespace-pre-line">
                {{ $record->review ?: 'No written review was provided.' }}
            </div>
        </div>
    </div>
</div>
@endsection
