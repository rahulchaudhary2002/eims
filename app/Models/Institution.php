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
        'established_year',
        'type',
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

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class);
    }

    public function affiliations()
    {
        return $this->belongsToMany(Affiliation::class, 'affiliation_institution');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'institution_course');
    }

    public function admissions()
    {
        return $this->hasMany(Admission::class);
    }
}
