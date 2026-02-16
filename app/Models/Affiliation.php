<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Affiliation extends Model
{
    use HasSlug;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Scope a query to only include active affiliations.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function institutions()
    {
        return $this->belongsToMany(Institution::class, 'affiliation_institution');
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
