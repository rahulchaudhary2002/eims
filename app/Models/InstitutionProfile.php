<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InstitutionProfile extends Model
{
    protected $fillable = [
        'institution_id',
        'facilities',
        'infrastructure',
        'achievements',
        'accreditations',
        'has_hostel',
        'has_transportation',
        'has_library',
        'has_lab',
        'has_cafeteria',
        'has_sports',
        'has_scholarship',
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'youtube_url',
    ];

    protected $casts = [
        'facilities'       => 'array',
        'infrastructure'   => 'array',
        'achievements'     => 'array',
        'accreditations'   => 'array',
        'has_hostel'       => 'boolean',
        'has_transportation' => 'boolean',
        'has_library'      => 'boolean',
        'has_lab'          => 'boolean',
        'has_cafeteria'    => 'boolean',
        'has_sports'       => 'boolean',
        'has_scholarship'  => 'boolean',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
