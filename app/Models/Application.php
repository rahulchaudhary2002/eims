<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Application extends Model
{
    public const SOURCES = [
        'direct'                  => 'Direct',
        'platform_recommendation' => 'Platform Recommendation',
        'scholarship'             => 'Scholarship',
        'featured_listing'        => 'Featured Listing',
        'campaign'                => 'Campaign',
        'consultancy_referral'    => 'Consultancy Referral',
    ];

    public const STATUSES = [
        'draft'        => 'Draft',
        'submitted'    => 'Submitted',
        'under_review' => 'Under Review',
        'referred'     => 'Referred',
        'admitted'     => 'Admitted',
        'rejected'     => 'Rejected',
        'withdrawn'    => 'Withdrawn',
    ];

    protected $fillable = [
        'application_number',
        'student_id',
        'institution_id',
        'institution_program_id',
        'scholarship_id',
        'source',
        'status',
        'student_message',
        'institution_remarks',
        'admin_remarks',
        'submitted_at',
        'reviewed_at',
        'referred_at',
        'admitted_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at'  => 'datetime',
            'referred_at'  => 'datetime',
            'admitted_at'  => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function institutionProgram(): BelongsTo
    {
        return $this->belongsTo(InstitutionProgram::class);
    }

    public function scholarship(): BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ApplicationStatusLog::class);
    }

    public function admission(): HasOne
    {
        return $this->hasOne(Admission::class);
    }
}
