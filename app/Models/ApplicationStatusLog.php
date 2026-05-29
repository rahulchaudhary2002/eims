<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApplicationStatusLog extends Model
{
    protected $fillable = [
        'application_id',
        'from_status',
        'to_status',
        'changed_by_type',
        'changed_by_id',
        'remarks',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function changedBy(): MorphTo
    {
        return $this->morphTo();
    }
}
