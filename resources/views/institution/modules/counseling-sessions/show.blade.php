@extends('institution.layouts.app')
@section('title', 'Counseling Session Details')
@section('page-title', 'Counseling Session Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        title="Counseling Session Details"
        :subtitle="$activeInstitution->name"
        :breadcrumbs="[['label' => 'Dashboard', 'route' => 'institution.dashboard'], ['label' => 'Counseling Sessions', 'route' => 'institution.counseling-sessions.index'], ['label' => 'Details']]">
        <x-slot:actions>
            @if($record->status === 'pending')
            <form action="{{ route('institution.counseling-sessions.approve', $record) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Approve & Schedule
                </button>
            </form>
            @endif
            <a href="{{ route('institution.counseling-sessions.edit', $record) }}" class="btn btn-secondary">Edit</a>
            <a href="{{ route('institution.counseling-sessions.index') }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">Session Information</h2>
        </div>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-5 p-6">
            @foreach($fields as $field => $config)
                @php $fieldType = $config['type'] ?? null; @endphp
                <div class="{{ in_array($fieldType, ['textarea', 'ckeditor']) ? 'md:col-span-2' : '' }}">
                    <dt class="text-xs font-medium text-slate-400 uppercase tracking-wider">{{ $config['label'] ?? \Illuminate\Support\Str::headline($field) }}</dt>
                    <dd class="mt-1 text-slate-800">@include('institution.shared.resource-value', ['record' => $record, 'field' => $field, 'fieldType' => $fieldType])</dd>
                </div>
            @endforeach
        </dl>
    </div>
</div>
@endsection
