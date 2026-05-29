{{--
    Form Textarea
--}}
@props([
    'name'        => '',
    'label'       => null,
    'value'       => '',
    'placeholder' => '',
    'required'    => false,
    'rows'        => 4,
    'help'        => null,
])
<div>
    @if($label)
    <label for="{{ $name }}" class="form-label {{ $required ? 'form-label-required' : '' }}">{{ $label }}</label>
    @endif
    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'form-control' . ($errors->has($name) ? ' is-invalid' : '')]) }}>{{ old($name, $value) }}</textarea>
    @if($help)
    <p class="form-help">{{ $help }}</p>
    @endif
    @error($name)
    <p class="form-error">{{ $message }}</p>
    @enderror
</div>
