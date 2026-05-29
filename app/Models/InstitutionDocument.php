<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionDocument extends Model
{
    const DOCUMENT_TYPES = [
        'registration'    => 'Registration',
        'accreditation'   => 'Accreditation',
        'noc'             => 'NOC',
        'license'         => 'License',
        'affiliation'     => 'Affiliation',
        'tax'             => 'Tax / PAN',
        'other'           => 'Other',
    ];

    const STATUSES = [
        'active'   => 'Active',
        'inactive' => 'Inactive',
        'expired'  => 'Expired',
    ];

    protected $fillable = [
        'institution_id',
        'document_type',
        'title',
        'file_path',
        'status',
        'remarks',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
