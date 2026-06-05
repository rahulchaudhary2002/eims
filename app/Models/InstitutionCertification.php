<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class InstitutionCertification extends Model
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
        'fee'       => 'decimal:2',
        'is_active' => 'boolean',
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

    public function applications(): MorphMany
    {
        return $this->morphMany(Application::class, 'applicable');
    }

    public function referrals(): MorphMany
    {
        return $this->morphMany(Referral::class, 'applicable');
    }

    public function admissions(): MorphMany
    {
        return $this->morphMany(Admission::class, 'applicable');
    }
}
