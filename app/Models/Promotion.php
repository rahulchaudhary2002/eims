<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use HasFactory, SoftDeletes;

    const TYPES = [
        'banner'    => 'Banner',
        'spotlight' => 'Spotlight',
        'discount'  => 'Discount',
        'cashback'  => 'Cashback',
        'event'     => 'Event',
        'other'     => 'Other',
    ];

    const STATUSES = [
        'draft'    => 'Draft',
        'active'   => 'Active',
        'paused'   => 'Paused',
        'expired'  => 'Expired',
        'cancelled'=> 'Cancelled',
    ];

    protected $fillable = [
        'institution_id',
        'type',
        'title',
        'image',
        'target_url',
        'start_date',
        'end_date',
        'amount',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
        'amount'     => 'decimal:4',
    ];

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }
}
