<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class InstitutionProgram extends Model
{
    use HasSlug;

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($model) {
                return $model->title ?: ($model->program?->name ?? 'program');
            })
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
    const STATUSES = [
        'open' => 'Open',
        'closed' => 'Closed',
        'upcoming' => 'Upcoming',
        'suspended' => 'Suspended',
    ];

    protected $fillable = [
        'institution_id',
        'program_id',
        'title',
        'slug',
        'admission_fee',
        'monthly_fee',
        'semester_fee',
        'annual_fee',
        'total_fee',
        'duration_months',
        'total_seats',
        'available_seats',
        'minimum_gpa',
        'minimum_percentage',
        'admission_start_date',
        'admission_end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'admission_fee' => 'decimal:2',
            'monthly_fee' => 'decimal:2',
            'semester_fee' => 'decimal:2',
            'annual_fee' => 'decimal:2',
            'total_fee' => 'decimal:2',
            'minimum_gpa' => 'decimal:2',
            'minimum_percentage' => 'decimal:2',
            'admission_start_date' => 'date',
            'admission_end_date' => 'date',
        ];
    }

    public function getDisplayNameAttribute(): string
    {
        return $this->title
            ?: $this->program?->name
            ?: 'Program #'.$this->getKey();
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(InstitutionProgramSubject::class);
    }

    public function scholarships(): HasMany
    {
        return $this->hasMany(Scholarship::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }
}
