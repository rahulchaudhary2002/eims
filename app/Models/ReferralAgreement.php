<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferralAgreement extends Model
{
    use HasFactory, SoftDeletes;

    const COMMISSION_TYPES = [
        'percentage' => 'Percentage',
        'flat_fee'   => 'Flat Fee',
        'tiered'     => 'Tiered',
    ];

    const STATUSES = [
        'draft'    => 'Draft',
        'active'   => 'Active',
        'inactive' => 'Inactive',
        'expired'  => 'Expired',
    ];

    protected $fillable = [
        'institution_id',
        'commission_type',
        'commission_value',
        'student_cashback_percentage',
        'platform_revenue_percentage',
        'start_date',
        'end_date',
        'agreement_file',
        'status',
    ];

    protected $casts = [
        'commission_value'            => 'decimal:4',
        'student_cashback_percentage' => 'decimal:4',
        'platform_revenue_percentage' => 'decimal:4',
        'start_date'                  => 'date',
        'end_date'                    => 'date',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function commissionInvoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\CommissionInvoice::class);
    }
}
