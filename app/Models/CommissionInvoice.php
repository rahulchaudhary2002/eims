<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionInvoice extends Model
{
    use HasFactory, SoftDeletes;

    const COMMISSION_TYPES = [
        'percentage' => 'Percentage',
        'flat_fee'   => 'Flat Fee',
        'tiered'     => 'Tiered',
    ];

    const STATUSES = [
        'draft'     => 'Draft',
        'issued'    => 'Issued',
        'paid'      => 'Paid',
        'overdue'   => 'Overdue',
        'cancelled' => 'Cancelled',
    ];

    protected $fillable = [
        'invoice_number',
        'institution_id',
        'admission_id',
        'referral_agreement_id',
        'admission_paid_amount',
        'commission_type',
        'commission_value',
        'commission_amount',
        'student_cashback_amount',
        'platform_revenue_amount',
        'status',
        'invoice_date',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'admission_paid_amount'   => 'decimal:4',
        'commission_value'        => 'decimal:4',
        'commission_amount'       => 'decimal:4',
        'student_cashback_amount' => 'decimal:4',
        'platform_revenue_amount' => 'decimal:4',
        'invoice_date'            => 'date',
        'due_date'                => 'date',
        'paid_at'                 => 'datetime',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function admission(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Admission::class);
    }

    public function referralAgreement(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ReferralAgreement::class);
    }

    public function payments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CommissionPayment::class);
    }

    public function scholarshipCashback(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(ScholarshipCashback::class);
    }
}
