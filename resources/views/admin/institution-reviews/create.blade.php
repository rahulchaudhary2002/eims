@extends('admin.layouts.app')

@section('title', 'Add Institution Review')

@section('content')
<div class="content-header">
    <div>
        <h1 class="content-title">Add Review</h1>
        <p class="content-subtitle">Create a new institution review.</p>
    </div>
    <a href="{{ route('admin.institution-reviews.index') }}" class="btn btn-secondary">← Back to Reviews</a>
</div>

<div class="card max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.institution-reviews.store') }}">
            @csrf
            @include('admin.institution-reviews.partials.form', [
                'institutionReview' => null,
                'selectedInstitutionId' => $selectedInstitutionId ?? null,
            ])
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary">Create Review</button>
                <a href="{{ route('admin.institution-reviews.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
