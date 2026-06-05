<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentRewardClaim extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'submitted'          => 'Submitted',
        'under_review'       => 'Under Review',
        'more_info_requested'=> 'More Info Requested',
        'verified'           => 'Verified',
        'approved'           => 'Approved',
        'rejected'           => 'Rejected',
        'disputed'           => 'Disputed',
        'payable'            => 'Payable',
        'paid'               => 'Paid',
        'cancelled'          => 'Cancelled',
    ];

    public const PAYMENT_METHODS = [
        'bank_transfer' => 'Bank Transfer',
        'esewa'         => 'eSewa',
        'khalti'        => 'Khalti',
        'ime_pay'       => 'IME Pay',
        'cash'          => 'Cash',
        'other'         => 'Other',
    ];

    public const DOCUMENT_TYPES = [
        'admission_letter'         => 'Admission Letter',
        'offer_letter'             => 'Offer Letter',
        'fee_receipt'              => 'Fee Receipt',
        'student_id_card'          => 'Student ID Card',
        'enrollment_confirmation'  => 'Enrollment Confirmation',
        'other'                    => 'Other',
    ];

    protected $fillable = [
        'student_id',
        'institution_id',
        'applicable_type',
        'applicable_id',
        'application_id',
        'referral_id',
        'admission_id',
        'claim_number',
        'status',
        'admission_date',
        'admission_number',
        'intake',
        'claimed_reward_amount',
        'approved_reward_amount',
        'payment_method',
        'payment_details',
        'student_note',
        'admin_note',
        'rejection_reason',
        'submitted_at',
        'verified_at',
        'approved_at',
        'paid_at',
        'verified_by',
        'approved_by',
        'paid_by',
    ];

    protected function casts(): array
    {
        return [
            'admission_date'          => 'date',
            'claimed_reward_amount'   => 'decimal:2',
            'approved_reward_amount'  => 'decimal:2',
            'payment_details'         => 'array',
            'submitted_at'            => 'datetime',
            'verified_at'             => 'datetime',
            'approved_at'             => 'datetime',
            'paid_at'                 => 'datetime',
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

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function referral(): BelongsTo
    {
        return $this->belongsTo(Referral::class);
    }

    public function admission(): BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(StudentRewardClaimDocument::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(StudentRewardPayment::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
