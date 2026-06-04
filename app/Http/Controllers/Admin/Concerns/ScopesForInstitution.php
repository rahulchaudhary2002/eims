<?php

namespace App\Http\Controllers\Admin\Concerns;

trait ScopesForInstitution
{
    /**
     * Returns null for super admins (no restriction).
     * Returns the current institution ID (> 0) for normal users with an active institution selected.
     * Returns 0 for normal users with no institution selected (all queries will return empty).
     */
    protected function institutionScope(): ?int
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return null;
        }

        return (int) session('current_institution_id', 0);
    }

    protected function isSuperAdmin(): bool
    {
        return (bool) auth('web')->user()?->is_super_admin;
    }
}
