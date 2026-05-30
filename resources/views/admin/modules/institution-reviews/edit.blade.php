@extends('admin.layouts.app')

@section('title', 'Edit Review')

@section('content')
<div class="space-y-5">
<x-admin.page-header title="Edit Review" subtitle="Update review #{{ $institutionReview->id }}."
    :breadcrumbs="[
        ['label'=>'Dashboard','route'=>'admin.dashboard'],
        ['label'=>'Institution Reviews','route'=>'admin.institution-reviews.index'],
        ['label'=>'Review #'.$institutionReview->id,'route'=>'admin.institution-reviews.show','params'=>$institutionReview],
        ['label'=>'Edit'],
    ]">
    <x-slot:actions>
        <a href="{{ route('admin.institution-reviews.show', $institutionReview) }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back
        </a>
        <a href="{{ route('admin.institution-reviews.index') }}" class="btn btn-secondary">All Reviews</a>
    </x-slot:actions>
</x-admin.page-header>

<div class="eims-card p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.institution-reviews.update', $institutionReview) }}">
            @csrf @method('PUT')
            @include('admin.modules.institution-reviews.partials.form', [
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
