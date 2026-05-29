<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadFollowUp extends Model
{
    use HasFactory, SoftDeletes;

    const STATUSES = [
        'pending'     => 'Pending',
        'completed'   => 'Completed',
        'cancelled'   => 'Cancelled',
        'rescheduled' => 'Rescheduled',
    ];

    protected $fillable = [
        'inquiry_id',
        'assigned_to',
        'follow_up_at',
        'status',
        'remarks',
    ];

    protected $casts = [
        'follow_up_at' => 'datetime',
    ];

    public function inquiry(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Inquiry::class);
    }

    public function assignedTo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
