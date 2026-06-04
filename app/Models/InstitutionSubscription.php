<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstitutionSubscription extends Model
{
    use HasFactory, SoftDeletes;

    const BILLING_CYCLES = [
        'monthly' => 'Monthly',
        'yearly'  => 'Yearly',
    ];

    const STATUSES = [
        'active'    => 'Active',
        'expired'   => 'Expired',
        'cancelled' => 'Cancelled',
        'trial'     => 'Trial',
        'suspended' => 'Suspended',
    ];

    protected $fillable = [
        'institution_id',
        'subscription_plan_id',
        'starts_at',
        'ends_at',
        'billing_cycle',
        'amount',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
        'amount'    => 'decimal:4',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function subscriptionPlan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
