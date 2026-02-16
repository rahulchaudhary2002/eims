<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Institution extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'address',
        'phone',
        'email',
        'website',
        'established_year',
        'institution_type_id',
        'institution_category_id',
        'logo',
        'cover_image',
        'is_active'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $typeSlug)
    {
        return $query->whereHas('institutionType', function ($q) use ($typeSlug) {
            $q->where('slug', $typeSlug);
        });
    }

    public function institutionType()
    {
        return $this->belongsTo(InstitutionType::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }

    public function affiliations()
    {
        return $this->belongsToMany(Affiliation::class, 'affiliation_institution');
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'institution_program')->withPivot('commission_amount');
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function commissions()
    {
        return $this->hasMany(InstitutionAdmissionCommission::class);
    }

    public function category()
    {
        return $this->belongsTo(InstitutionCategory::class, 'institution_category_id');
    }

    public function getDueCommissionAttribute()
    {
        $totalCommission = $this->commissions()->sum('commission_amount');
        $totalPaid = $this->commissions()->where('is_paid', true)->sum('commission_amount');

        return $totalCommission - $totalPaid;
    }

    public function getTypeAttribute(): ?string
    {
        if (array_key_exists('type', $this->attributes)) {
            return $this->attributes['type'];
        }

        if ($this->relationLoaded('institutionType')) {
            return optional($this->institutionType)->slug;
        }

        return $this->institutionType()->value('slug');
    }
}
