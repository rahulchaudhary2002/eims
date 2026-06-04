<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRewardPayment extends Model
{
    public const STATUSES = [
        'pending'    => 'Pending',
        'processing' => 'Processing',
        'paid'       => 'Paid',
        'failed'     => 'Failed',
        'cancelled'  => 'Cancelled',
    ];

    protected $fillable = [
        'student_reward_claim_id',
        'student_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'status',
        'payment_details',
        'note',
        'paid_at',
        'paid_by',
    ];

    protected function casts(): array
    {
        return [
            'amount'          => 'decimal:2',
            'payment_details' => 'array',
            'paid_at'         => 'datetime',
        ];
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(StudentRewardClaim::class, 'student_reward_claim_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function paidBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }
}
