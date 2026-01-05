@props([
'name' => '',
'label' => '',
'type' => 'text',
'error' => null,
'placeholder' => null,
'readonly' => false,
'required' => false,
'value' => null,
])

<div class="flex flex-col relative">
    @if($label)
    <label for="{{ $name }}" class="text-sm font-medium mb-1 text-gray-700 flex items-center">
        {{ $label }}
        @if($required)
        <span class="text-red-500 ml-1">*</span>
        @endif
    </label>
    @endif

    <div class="relative">
        <input
            type="{{ $type === 'password' ? 'password' : $type }}"
            id="{{ $name }}"
            name="{{ $name }}"
            value="{{ $value ?? old($name) }}"
            placeholder="{{ $placeholder ?? 'Enter ' . strtolower($label) }}"
            @if($readonly) readonly @endif
            aria-invalid="{{ $error ? 'true' : 'false' }}"
            aria-describedby="{{ $error ? $name . '-error' : '' }}"
            class="w-full p-2.5 border rounded-lg transition-all duration-200 text-gray-800 outline-none
                {{ $error ? 'border-red-500 focus:border-red-500' : 'border-gray-300 focus:border-blue-400' }}
                {{ $readonly ? 'bg-gray-100 cursor-not-allowed text-gray-500' : 'bg-white hover:border-gray-400' }}">
    </div>

    @if($error)
    <p id="{{ $name }}-error" class="text-red-600 text-xs mt-1.5 font-medium flex items-center">
        <x-lucide-alert-triangle class="w-3.5 h-3.5 mr-1 text-red-600" />
        {{ $error }}
    </p>
    @endif
</div>