@extends('admin.layouts.app')

@section('title', 'New Conversation')

@section('content')
<div class="space-y-5">
<x-admin.page-header title="New Conversation" subtitle="Start a new conversation thread."
    :breadcrumbs="[
        ['label'=>'Dashboard','route'=>'admin.dashboard'],
        ['label'=>'Conversations','route'=>'admin.conversations.index'],
        ['label'=>'New Conversation'],
    ]">
    <x-slot:actions>
        <a href="{{ route('admin.conversations.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/></svg>
            Back
        </a>
    </x-slot:actions>
</x-admin.page-header>

<div class="eims-card p-6 max-w-2xl">
        <form method="POST" action="{{ route('admin.conversations.store') }}">
            @csrf
            @include('admin.modules.conversations.partials.form', [
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
