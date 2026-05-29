@php
    $value = data_get($record, $field);
    $display = $value;

    if ($value instanceof \Carbon\CarbonInterface) {
        $display = $value->format('d M Y, h:i A');
    } elseif (is_bool($value)) {
        $display = $value ? 'Yes' : 'No';
    } elseif (is_array($value)) {
        $display = implode(', ', $value);
    }

    if (str_ends_with($field, '_id')) {
        $display = $value ? '#' . $value : null;
    }
@endphp

@if(is_string($value) && (str_contains($field, 'file') || str_contains($field, 'image') || str_contains($field, 'thumbnail') || str_contains($field, 'proof') || str_contains($field, 'attachment')))
    <a href="{{ Storage::url($value) }}" target="_blank" class="text-blue-600 hover:underline">View file</a>
@else
    {{ $display ?: '—' }}
@endif
