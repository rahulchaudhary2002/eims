<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsultancyService extends Model
{
    use HasFactory, SoftDeletes;

    const SERVICE_TYPES = [
        'visa_assistance'      => 'Visa Assistance',
        'document_preparation' => 'Document Preparation',
        'test_preparation'     => 'Test Preparation',
        'application_support'  => 'Application Support',
        'career_counseling'    => 'Career Counseling',
        'accommodation'        => 'Accommodation',
        'financial_guidance'   => 'Financial Guidance',
        'other'                => 'Other',
    ];

    protected $fillable = [
        'institution_id',
        'service_type',
        'title',
        'description',
        'service_fee',
        'is_active',
    ];

    protected $casts = [
        'service_fee' => 'decimal:4',
        'is_active'   => 'boolean',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
