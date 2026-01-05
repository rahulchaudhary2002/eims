<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'established_year',
        'type',
        'logo',
        'cover_image',
        'is_active'
    ];

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
}
