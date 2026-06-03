<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentRewardClaimDocument extends Model
{
    protected $fillable = [
        'student_reward_claim_id',
        'document_type',
        'file_path',
        'original_name',
        'mime_type',
        'file_size',
        'is_verified',
        'verified_by',
        'verified_at',
        'verification_note',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    public function claim(): BelongsTo
    {
        return $this->belongsTo(StudentRewardClaim::class, 'student_reward_claim_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
