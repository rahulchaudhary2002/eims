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
        'admission_type',
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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'admission_course');
    }

    public function grades()
    {
        return $this->hasMany(AdmissionGrade::class)->orderBy('order');
    }

    public function getIsOpenAttribute(): bool
    {
        $now = now();
        return $now->between($this->start_date, $this->end_date);
    }

    public function isForCourse(): bool
    {
        return $this->admission_type === 'course';
    }

    public function isForGrade(): bool
    {
        return $this->admission_type === 'grade';
    }

    public function applications()
    {
        return $this->hasMany(AdmissionApplication::class);
    }
}
