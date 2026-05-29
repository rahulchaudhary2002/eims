<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referral extends Model
{
    use HasFactory, SoftDeletes;

    const STATUSES = [
        'pending'   => 'Pending',
        'viewed'    => 'Viewed',
        'accepted'  => 'Accepted',
        'rejected'  => 'Rejected',
        'completed' => 'Completed',
    ];

    protected $fillable = [
        'referral_number',
        'application_id',
        'student_id',
        'institution_id',
        'referred_by',
        'status',
        'referred_at',
        'viewed_at',
    ];

    protected $casts = [
        'referred_at' => 'datetime',
        'viewed_at'   => 'datetime',
    ];

    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function referredBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_by');
    }
}
