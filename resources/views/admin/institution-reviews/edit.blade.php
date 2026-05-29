@extends('admin.layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="content-header">
    <div>
        <h1 class="content-title">Edit Review</h1>
        <p class="content-subtitle">Update review #{{ $institutionReview->id }}.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.institution-reviews.show', $institutionReview) }}" class="btn btn-secondary">← Back to Review</a>
        <a href="{{ route('admin.institution-reviews.index') }}" class="btn btn-secondary">All Reviews</a>
    </div>
</div>

<div class="card max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.institution-reviews.update', $institutionReview) }}">
            @csrf @method('PUT')
            @include('admin.institution-reviews.partials.form', [
                'selectedInstitutionId' => null,
            ])
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary">Update Review</button>
                <a href="{{ route('admin.institution-reviews.show', $institutionReview) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
