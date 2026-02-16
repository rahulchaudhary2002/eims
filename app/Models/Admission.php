<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Admission extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'institution_id',
        'description',
        'start_date',
        'end_date',
    ];

    protected $dates = [
        'start_date',
        'end_date',
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'admission_program');
    }

    public function getIsOpenAttribute(): bool
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    public function applications()
    {
        return $this->hasMany(AdmissionApplication::class);
    }
}
