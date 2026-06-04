@extends('institution.layouts.app')
@section('title', $title . ' Details')
@section('page-title', $title . ' Details')

@section('content')
<div class="space-y-5">
    <x-admin.page-header
        :title="$title . ' Details'"
        :subtitle="$activeInstitution->name"
        :breadcrumbs="[['label' => 'Dashboard', 'route' => 'institution.dashboard'], ['label' => \Illuminate\Support\Str::plural($title), 'route' => 'institution.' . $routeBase . '.index'], ['label' => 'Details']]">
        <x-slot:actions>
            <a href="{{ route("institution.{$routeBase}.index") }}" class="btn btn-secondary">Back</a>
            @if(Route::has("institution.{$routeBase}.edit"))
                <a href="{{ route("institution.{$routeBase}.edit", $record) }}" class="btn btn-primary">Edit</a>
            @endif
        </x-slot:actions>
    </x-admin.page-header>

    @if(session('success'))
        <x-admin.alert type="success" :message="session('success')" />
    @endif

    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">{{ $title }} Information</h2>
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
