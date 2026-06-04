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
        'platform_referral'       => 'Platform Referral',
        'scholarship'             => 'Scholarship',
        'featured_listing'        => 'Featured Listing',
        'campaign'                => 'Campaign',
        'consultancy_referral'    => 'Consultancy Referral',
    ];

    public const STATUSES = [
        'draft'                           => 'Draft',
        'submitted'                       => 'Submitted',
        'under_platform_review'           => 'Under Platform Review',
        'more_info_requested'             => 'More Info Requested',
        'platform_rejected'               => 'Platform Rejected',
        'approved_for_referral'           => 'Approved for Referral',
        'referred_to_institution'         => 'Referred to Institution',
        'institution_reviewing'           => 'Institution Reviewing',
        'institution_requested_documents' => 'Institution Requested Documents',
        'institution_requested_interview' => 'Institution Requested Interview',
        'institution_rejected'            => 'Institution Rejected',
        'admitted'                        => 'Admitted',
        'cancelled'                       => 'Cancelled',
        'withdrawn'                       => 'Withdrawn',
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
        'student_note',
        'institution_remarks',
        'admin_remarks',
        'platform_review_note',
        'platform_reviewed_by',
        'submitted_at',
        'reviewed_at',
        'referred_at',
        'admitted_at',
        'platform_reviewed_at',
        'more_info_requested_at',
        'approved_for_referral_at',
        'institution_rejected_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at'             => 'datetime',
            'reviewed_at'              => 'datetime',
            'referred_at'              => 'datetime',
            'admitted_at'              => 'datetime',
            'platform_reviewed_at'     => 'datetime',
            'more_info_requested_at'   => 'datetime',
            'approved_for_referral_at' => 'datetime',
            'institution_rejected_at'  => 'datetime',
            'cancelled_at'             => 'datetime',
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

    public function platformReviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'platform_reviewed_by');
    }

    public function statusLogs(): HasMany
    {
        return $this->hasMany(ApplicationStatusLog::class);
    }

    public function admission(): HasOne
    {
        return $this->hasOne(Admission::class);
    }

    public function referral(): HasOne
    {
        return $this->hasOne(\App\Models\Referral::class);
    }

    public function allReferrals(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    public function latestReferral(): HasOne
    {
        return $this->hasOne(Referral::class)->latestOfMany();
    }

    public function rewardClaim(): HasOne
    {
        return $this->hasOne(StudentRewardClaim::class);
    }
}
