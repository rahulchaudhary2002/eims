<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
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

    public function programs(): BelongsToMany
    {
        return $this->belongsToMany(Program::class, 'course_program');
    }

    /**
     * Scope a query to only include active courses.
     */
    public function scopeActive($query)
    {
        return $query->where($this->qualifyColumn('is_active'), true);
    }

    /**
     * Get the display name with code.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->code})";
    }

    /**
     * Get all descriptions for the course, ordered by 'order'.
     */
    public function descriptions()
    {
        return $this->hasMany(CourseDescription::class)
            ->orderBy('order');
    }
}
