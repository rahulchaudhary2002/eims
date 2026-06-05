<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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

    public const APPLICABLE_TYPES = [
        \App\Models\InstitutionProgram::class       => 'Program',
        \App\Models\InstitutionCourse::class        => 'Course',
        \App\Models\InstitutionCertification::class => 'Certification',
        \App\Models\ConsultancyService::class       => 'Service',
    ];

    public const SCHOLARSHIP_STATUSES = [
        'pending'      => 'Pending',
        'under_review' => 'Under Review',
        'approved'     => 'Approved',
        'rejected'     => 'Rejected',
        'withdrawn'    => 'Withdrawn',
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
        'applicable_type',
        'applicable_id',
        'scholarship_id',
        'scholarship_status',
        'scholarship_approved_amount',
        'scholarship_remarks',
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
            'cancelled_at'                  => 'datetime',
            'scholarship_approved_amount'   => 'decimal:4',
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

    public function applicable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getApplicableLabelAttribute(): string
    {
        $item = $this->applicable;
        if (!$item) return '-';
        if ($item instanceof \App\Models\InstitutionProgram) {
            return $item->title ?: ($item->program?->name ?? 'Program');
        }
        return $item->title ?? '-';
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
