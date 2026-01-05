<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affiliation extends Model
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
}
