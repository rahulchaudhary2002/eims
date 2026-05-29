{{--
    Form Input
    Usage:
    <x-admin.form-input
        name="name"
        label="Institution Name"
        :value="old('name', $institution->name ?? '')"
        placeholder="Enter name"
        required />
--}}
@props([
    'name'        => '',
    'label'       => null,
    'type'        => 'text',
    'value'       => '',
    'placeholder' => '',
    'required'    => false,
    'help'        => null,
    'disabled'    => false,
])
<div>
    @if($label)
    <label for="{{ $name }}" class="form-label {{ $required ? 'form-label-required' : '' }}">{{ $label }}</label>
    @endif
    <input
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}
    >
    @if($help)
    <p class="form-help">{{ $help }}</p>
    @endif
    @error($name)
    <p class="form-error">{{ $message }}</p>
    @enderror
</div>
