@extends('institution.layouts.app')
@section('title', 'Reviews')
@section('page-title', 'Reviews')

@section('content')
@php
    $stats = [
        ['label' => 'Total Reviews', 'value' => number_format($summary['total'] ?? 0), 'tone' => 'primary'],
        ['label' => 'Average Rating', 'value' => number_format($summary['average'] ?? 0, 1), 'tone' => 'warning'],
        ['label' => 'Approved', 'value' => number_format($summary['approved'] ?? 0), 'tone' => 'success'],
        ['label' => 'Pending', 'value' => number_format($summary['pending'] ?? 0), 'tone' => 'slate'],
    ];
@endphp

<div class="space-y-5">
    <x-admin.page-header
        title="Reviews"
        :subtitle="$activeInstitution->name"
        :breadcrumbs="[['label' => 'Dashboard', 'route' => 'institution.dashboard'], ['label' => 'Reviews']]">
    </x-admin.page-header>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        @foreach($stats as $stat)
            <div class="eims-card p-5">
                <p class="text-sm font-medium text-slate-500">{{ $stat['label'] }}</p>
                <p class="mt-2 text-2xl font-bold text-slate-900">{{ $stat['value'] }}</p>
            </div>
        @endforeach
    </div>

    <div class="eims-card overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-semibold text-slate-800">Institution Reviews</h2>
            <span class="text-sm text-slate-500">{{ $records->total() }} total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="eims-table w-full">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $review)
                        <tr>
                            <td>
                                <div class="font-medium text-slate-800">{{ $review->student->name ?? $review->student->full_name ?? 'Student #' . $review->student_id }}</div>
                                <div class="text-xs text-slate-500">{{ $review->student->email ?? '-' }}</div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1 text-amber-500">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="{{ $i <= (int) $review->rating ? 'fas' : 'far' }} fa-star text-xs"></i>
                                    @endfor
                                    <span class="ml-1 text-xs font-semibold text-slate-600">{{ $review->rating }}/5</span>
                                </div>
                            </td>
                            <td class="max-w-md">
                                <p class="text-sm text-slate-600 line-clamp-2">{{ $review->review ?: '-' }}</p>
                            </td>
                            <td>
                                <span class="badge {{ $review->is_approved ? 'badge-green' : 'badge-secondary' }}">
                                    {{ $review->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </td>
                            <td class="text-sm text-slate-500">{{ $review->created_at?->format('d M Y') }}</td>
                            <td class="text-right">
                                <a href="{{ route('institution.reviews.show', $review) }}" class="btn btn-secondary btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <x-admin.empty-state title="No reviews yet" description="Student reviews for this institution will appear here." />
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($records->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
