<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Admission extends Model
{
    public const VERIFICATION_STATUSES = [
        'pending'  => 'Pending',
        'verified' => 'Verified',
        'rejected' => 'Rejected',
    ];

    public const SOURCES = [
        'platform_referral'  => 'Platform Referral',
        'direct_institution' => 'Direct Institution',
        'manual_admin'       => 'Manual (Admin)',
        'imported'           => 'Imported',
    ];

    public const COMMISSION_STATUSES = [
        'pending'      => 'Pending',
        'eligible'     => 'Eligible',
        'not_eligible' => 'Not Eligible',
        'disputed'     => 'Disputed',
        'paid'         => 'Paid',
        'cancelled'    => 'Cancelled',
    ];

    protected $fillable = [
        'application_id',
        'application_referral_id',
        'student_id',
        'institution_id',
        'applicable_type',
        'applicable_id',
        'admission_number',
        'admission_date',
        'paid_amount',
        'payment_proof',
        'verification_status',
        'verified_by',
        'verified_at',
        'remarks',
        'source',
        'is_commission_claimable',
        'commission_status',
    ];

    protected function casts(): array
    {
        return [
            'admission_date'          => 'date',
            'paid_amount'             => 'decimal:2',
            'verified_at'             => 'datetime',
            'is_commission_claimable' => 'boolean',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class, 'application_referral_id');
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

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function commissionInvoice(): HasOne
    {
        return $this->hasOne(\App\Models\CommissionInvoice::class);
    }

    public function rewardClaims(): HasMany
    {
        return $this->hasMany(StudentRewardClaim::class);
    }
}
