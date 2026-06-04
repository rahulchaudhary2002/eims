<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referral extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = [
        'sent'                   => 'Sent',
        'preview_viewed'         => 'Preview Viewed',
        'full_profile_requested' => 'Full Profile Requested',
        'full_profile_unlocked'  => 'Full Profile Unlocked',
        'accepted'               => 'Accepted',
        'rejected'               => 'Rejected',
        'expired'                => 'Expired',
        'converted_to_admission' => 'Converted to Admission',
        'disputed'               => 'Disputed',
    ];

    public const ACTIONS = [
        'preview_viewed'         => 'Preview Viewed',
        'full_profile_requested' => 'Full Profile Requested',
        'agreement_accepted'     => 'Agreement Accepted',
        'full_profile_unlocked'  => 'Full Profile Unlocked',
        'document_viewed'        => 'Document Viewed',
        'contact_viewed'         => 'Contact Viewed',
        'rejected'               => 'Rejected',
        'accepted'               => 'Accepted',
    ];

    public const PROTECTION_DAYS = 90;

    protected $fillable = [
        'referral_number',
        'application_id',
        'student_id',
        'institution_id',
        'institution_program_id',
        'referral_agreement_id',
        'referred_by',
        'status',
        'referred_at',
        'viewed_at',
        'is_profile_unlocked',
        'profile_unlocked_at',
        'profile_unlocked_by',
        'agreement_accepted_at',
        'protection_starts_at',
        'protection_expires_at',
        'institution_response_note',
        'platform_note',
        'unlock_ip',
        'unlock_user_agent',
    ];

    protected function casts(): array
    {
        return [
            'referred_at'           => 'datetime',
            'viewed_at'             => 'datetime',
            'profile_unlocked_at'   => 'datetime',
            'agreement_accepted_at' => 'datetime',
            'protection_starts_at'  => 'date',
            'protection_expires_at' => 'date',
            'is_profile_unlocked'   => 'boolean',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
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

    public function referralAgreement(): BelongsTo
    {
        return $this->belongsTo(ReferralAgreement::class);
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    public function profileUnlockedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'profile_unlocked_by');
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(ReferralAccessLog::class);
    }

    public function admission(): HasOne
    {
        return $this->hasOne(Admission::class);
    }

    public function rewardClaims(): HasMany
    {
        return $this->hasMany(StudentRewardClaim::class);
    }

    public function isProtectionActive(): bool
    {
        if (! $this->is_profile_unlocked) {
            return false;
        }
        if (! $this->protection_expires_at) {
            return false;
        }

        return $this->protection_expires_at->isFuture() || $this->protection_expires_at->isToday();
    }
}
