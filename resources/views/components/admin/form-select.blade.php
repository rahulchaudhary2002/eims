{{--
    Form Select
    Usage:
    <x-admin.form-select name="type" label="Type" :value="old('type')" required>
        <option value="">Select type</option>
        @foreach($types as $t)
        <option value="{{ $t->id }}">{{ $t->name }}</option>
        @endforeach
    </x-admin.form-select>
--}}
@props([
    'name'     => '',
    'label'    => null,
    'value'    => '',
    'required' => false,
    'help'     => null,
    'disabled' => false,
])
<div>
    @if($label)
    <label for="{{ $name }}" class="form-label {{ $required ? 'form-label-required' : '' }}">{{ $label }}</label>
    @endif
    <select
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $required ? 'required' : '' }}
        {{ $disabled ? 'disabled' : '' }}
        {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>
        {{ $slot }}
    </select>
    @if($help)
    <p class="form-help">{{ $help }}</p>
    @endif
    @error($name)
    <p class="form-error">{{ $message }}</p>
    @enderror
</div>
