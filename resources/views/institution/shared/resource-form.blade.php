@extends('institution.layouts.app')
@php $isEdit = $record->exists; @endphp
@section('title', ($isEdit ? 'Edit ' : 'Add ') . $title)
@section('page-title', ($isEdit ? 'Edit ' : 'Add ') . $title)

@section('content')
<form method="POST" action="{{ $isEdit ? route("institution.{$routeBase}.update", $record) : route("institution.{$routeBase}.store") }}" enctype="multipart/form-data" class="space-y-5">
    @csrf
    @if($isEdit)
        @method('PUT')
    @endif

    <x-admin.page-header
        :title="($isEdit ? 'Edit ' : 'Add ') . $title"
        :subtitle="$activeInstitution->name"
        :breadcrumbs="[['label' => 'Dashboard', 'route' => 'institution.dashboard'], ['label' => \Illuminate\Support\Str::plural($title), 'route' => 'institution.' . $routeBase . '.index'], ['label' => $isEdit ? 'Edit' : 'Add']]">
        <x-slot:actions>
        <a href="{{ route("institution.{$routeBase}.index") }}" class="btn btn-secondary">Back</a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="eims-card overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-base font-semibold text-slate-800">{{ $title }} Information</h2>
            <p class="text-sm text-slate-500 mt-1">Fields are scoped to {{ $activeInstitution->name }}.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 p-6">
            @foreach($fields as $field => $config)
                @continue(in_array($field, $readOnlyFields, true) || $field === 'institution_id')
                @php
                    $type = $config['type'] ?? 'text';
                    $label = $config['label'] ?? \Illuminate\Support\Str::headline($field);
                    $value = old($field, $record->{$field});
                @endphp
                <div class="{{ in_array($type, ['textarea', 'ckeditor']) ? 'md:col-span-2' : '' }}">
                    <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                    @if($type === 'textarea')
                        <textarea name="{{ $field }}" id="{{ $field }}" rows="5" class="form-control @error($field) is-invalid @enderror">{{ $value }}</textarea>
                    @elseif($type === 'ckeditor')
                        <textarea name="{{ $field }}" id="{{ $field }}" rows="6" class="form-control ckeditor @error($field) is-invalid @enderror">{{ $value }}</textarea>
                    @elseif($type === 'select')
                        <select name="{{ $field }}" id="{{ $field }}" class="form-control @error($field) is-invalid @enderror">
                            <option value="">Select {{ strtolower($label) }}</option>
                            @foreach(($selectOptions[$field] ?? []) as $optionValue => $optionLabel)
                                <option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>
                            @endforeach
                        </select>
                    @elseif($type === 'checkbox')
                        <label class="flex items-center gap-2 text-sm text-slate-600 mt-2">
                            <input type="checkbox" name="{{ $field }}" value="1" class="rounded border-slate-300" @checked(old($field, $record->{$field}))>
                            Enabled
                        </label>
                    @elseif($type === 'file')
                        <input type="file" name="{{ $field }}" id="{{ $field }}" class="form-control @error($field) is-invalid @enderror">
                        @if($record->{$field})
                            <p class="text-xs text-slate-500 mt-1">Current: <a class="text-blue-600 hover:underline" href="{{ Storage::url($record->{$field}) }}" target="_blank">View file</a></p>
                        @endif
                    @else
                        <input type="{{ $type }}" name="{{ $field }}" id="{{ $field }}" value="{{ $value instanceof \Carbon\CarbonInterface ? $value->format($type === 'date' ? 'Y-m-d' : 'Y-m-d\TH:i') : $value }}" class="form-control @error($field) is-invalid @enderror">
                    @endif
                    @error($field)<p class="form-error">{{ $message }}</p>@enderror
                </div>
            @endforeach
        </div>
    </div>

    <div class="flex justify-end gap-2">
        <a href="{{ route("institution.{$routeBase}.index") }}" class="btn btn-secondary">Cancel</a>
        <button class="btn btn-primary">{{ $isEdit ? 'Update' : 'Create' }} {{ $title }}</button>
    </div>
</form>
@endsection
