{{--
    Form Card - wraps a form section with a card
    Usage:
    <x-admin.form-card title="Basic Information">
        ... form fields ...
    </x-admin.form-card>
--}}
@props(['title' => null, 'description' => null])
<div class="eims-card mb-6">
    @if($title)
    <div class="eims-card-header">
        <div>
            <h2 class="eims-card-title">{{ $title }}</h2>
            @if($description)
            <p class="text-sm text-slate-500 mt-0.5">{{ $description }}</p>
            @endif
        </div>
    </div>
    @endif
    <div class="space-y-5">
        {{ $slot }}
    </div>
</div>
