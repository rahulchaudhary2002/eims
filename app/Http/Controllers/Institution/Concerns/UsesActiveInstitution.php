<?php

namespace App\Http\Controllers\Institution\Concerns;

use App\Models\Institution;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait UsesActiveInstitution
{
    protected function activeInstitutionId(): int
    {
        return (int) session('active_institution_id');
    }

    protected function activeInstitution(): Institution
    {
        return Institution::query()->findOrFail($this->activeInstitutionId());
    }

    protected function assignedInstitutionUsers()
    {
        return $this->activeInstitution()
            ->activeUsers()
            ->orderBy('users.name')
            ->get(['users.id', 'users.name']);
    }

    protected function abortUnlessInstitutionRecord(Model $model, ?string $relation = null): void
    {
        if ($relation) {
            abort_unless((int) data_get($model, "{$relation}.institution_id") === $this->activeInstitutionId(), 403);

            return;
        }

        abort_unless((int) $model->getAttribute('institution_id') === $this->activeInstitutionId(), 403);
    }

    protected function scopedInstitutionQuery(string $modelClass): Builder
    {
        return $modelClass::query()->where('institution_id', $this->activeInstitutionId());
    }
}
