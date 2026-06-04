<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionPayment extends Model
{
    use HasFactory, SoftDeletes;

    const PAYMENT_METHODS = [
        'bank_transfer' => 'Bank Transfer',
        'cheque'        => 'Cheque',
        'cash'          => 'Cash',
        'online'        => 'Online',
    ];

    protected $fillable = [
        'commission_invoice_id',
        'amount',
        'payment_method',
        'transaction_reference',
        'payment_proof',
        'payment_date',
        'remarks',
    ];

    protected $casts = [
        'amount'       => 'decimal:4',
        'payment_date' => 'date',
    ];

    public function commissionInvoice(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(CommissionInvoice::class);
    }
}
