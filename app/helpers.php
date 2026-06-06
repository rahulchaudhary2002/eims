<?php

use Illuminate\Support\Facades\Storage;

/**
 * Return the public URL for a storage path only if the file exists.
 * Returns $default (empty string by default) when the path is empty or the file is missing.
 */
function storage_url(?string $path, string $default = ''): string
{
    if (!$path) return $default;

    try {
        if (!Storage::disk('public')->exists($path)) return $default;
    } catch (\Exception) {
        return $default;
    }

    return Storage::url($path);
}

/**
 * Return true only if the path is non-empty and the file actually exists in storage.
 */
function storage_exists(?string $path): bool
{
    if (!$path) return false;

    try {
        return Storage::disk('public')->exists($path);
    } catch (\Exception) {
        return false;
    }
}
