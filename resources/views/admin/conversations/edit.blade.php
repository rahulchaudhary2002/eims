@extends('admin.layouts.app')

@section('title', 'Edit Conversation #' . $conversation->id)

@section('content')
<div class="content-header">
    <div>
        <h1 class="content-title">Edit Conversation #{{ $conversation->id }}</h1>
        <p class="content-subtitle">Update conversation details.</p>
    </div>
    <div class="flex gap-2">
        <a href="{{ route('admin.conversations.show', $conversation) }}" class="btn btn-secondary">← Back to Conversation</a>
        <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">All Conversations</a>
    </div>
</div>

<div class="card max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.conversations.update', $conversation) }}">
            @csrf @method('PUT')
            @include('admin.conversations.partials.form', [
                'selectedInstitutionId' => null,
                'selectedStudentId'     => null,
            ])
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary">Update Conversation</button>
                <a href="{{ route('admin.conversations.show', $conversation) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
