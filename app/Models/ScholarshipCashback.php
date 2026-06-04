<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScholarshipCashback extends Model
{
    use HasFactory, SoftDeletes;

    const STATUSES = [
        'pending'    => 'Pending',
        'processing' => 'Processing',
        'paid'       => 'Paid',
        'failed'     => 'Failed',
        'cancelled'  => 'Cancelled',
    ];

    const PAYMENT_METHODS = [
        'bank_transfer' => 'Bank Transfer',
        'cheque'        => 'Cheque',
        'cash'          => 'Cash',
        'online'        => 'Online',
    ];

    protected $fillable = [
        'student_id',
        'application_id',
        'commission_invoice_id',
        'commission_received_amount',
        'cashback_percentage',
        'cashback_amount',
        'status',
        'payment_method',
        'transaction_reference',
        'paid_at',
        'remarks',
    ];

    protected $casts = [
        'commission_received_amount' => 'decimal:4',
        'cashback_percentage'        => 'decimal:4',
        'cashback_amount'            => 'decimal:4',
        'paid_at'                    => 'datetime',
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function commissionInvoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CommissionInvoice::class);
    }
}
