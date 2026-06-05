<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class InstitutionCourse extends Model
{
    use HasSlug;

    protected $fillable = [
        'institution_id',
        'title',
        'slug',
        'fee',
        'duration_hours',
        'description',
        'is_active',
    ];

    protected $casts = [
        'fee'        => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
