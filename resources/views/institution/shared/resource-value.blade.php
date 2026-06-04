@php
    $value = data_get($record, $field);
    $display = $value;
    $relationName = null;

    if (str_ends_with($field, '_id')) {
        $relationName = \Illuminate\Support\Str::camel(substr($field, 0, -3));

        if ($record->relationLoaded($relationName) && ($related = $record->{$relationName})) {
            $display = $related->display_name
                ?? $related->name
                ?? $related->title
                ?? $related->full_name
                ?? $related->email
                ?? $related->invoice_number
                ?? $related->admission_number
                ?? $related->application_number
                ?? $related->referral_number
                ?? ('#' . $related->getKey());
        } elseif ($value !== null && isset($selectOptions[$field]) && array_key_exists($value, $selectOptions[$field])) {
            $display = $selectOptions[$field][$value];
        }
    }

    if ($value instanceof \Carbon\CarbonInterface) {
        $display = $value->format('d M Y, h:i A');
    } elseif (is_bool($value)) {
        $display = $value ? 'Yes' : 'No';
    } elseif (is_array($value)) {
        $display = implode(', ', $value);
    }

    if (str_ends_with($field, '_id') && $relationName && !$record->relationLoaded($relationName) && $display === $value) {
        $display = $value ? '#' . $value : null;
    }
@endphp

@if(is_string($value) && (str_contains($field, 'file') || str_contains($field, 'image') || str_contains($field, 'thumbnail') || str_contains($field, 'proof') || str_contains($field, 'attachment')))
    <a href="{{ Storage::url($value) }}" target="_blank" class="text-blue-600 hover:underline">View file</a>
@elseif(is_bool($value))
    <span class="badge {{ $value ? 'badge-green' : 'badge-secondary' }}">{{ $display }}</span>
@elseif(($fieldType ?? null) === 'ckeditor')
    <div class="prose prose-sm max-w-none">{!! $display ?: '-' !!}</div>
@else
    {{ $display ?: '-' }}
@endif
