@extends('admin.layouts.app')

@section('title', 'New Conversation')

@section('content')
<div class="content-header">
    <div>
        <h1 class="content-title">New Conversation</h1>
        <p class="content-subtitle">Start a new conversation thread.</p>
    </div>
    <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">← Back to Conversations</a>
</div>

<div class="card max-w-2xl">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.conversations.store') }}">
            @csrf
            @include('admin.conversations.partials.form', [
                'conversation'          => null,
                'selectedInstitutionId' => $selectedInstitutionId ?? null,
                'selectedStudentId'     => $selectedStudentId ?? null,
            ])
            <div class="flex gap-3 mt-6">
                <button type="submit" class="btn btn-primary">Create Conversation</button>
                <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
