@props([
    'type' => 'submit',
    'loading' => false,
    'disabled' => false,
])

<button
    type="{{ $type }}"
    class="w-full flex items-center justify-center gap-2
            py-3 px-6
            bg-blue-500 hover:bg-blue-600
            text-white rounded-lg
            text-sm font-medium
            transition-all duration-200
            shadow-sm hover:shadow-md
            disabled:opacity-70 disabled:cursor-not-allowed"
    @if($loading || $disabled) disabled @endif
>
    @if($loading)
        <span class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
        <span>Submitting...</span>
    @else
        {{ $slot }}
    @endif
</button>
