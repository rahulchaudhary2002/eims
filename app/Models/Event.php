<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'title',
        'slug',
        'start_date',
        'end_date',
        'description',
        'institution_id',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getStatusAttribute()
    {
        $now = now();

        if ($this->end_date < $now) {
            return 'Past';
        } elseif ($this->start_date > $now) {
            return 'Upcoming';
        } else {
            return 'Ongoing';
        }
    }

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
