<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scholarship extends Model
{
    public const TYPES = [
        'platform_cashback'     => 'Platform Cashback',
        'institution_discount'  => 'Institution Discount',
        'merit_based'           => 'Merit Based',
        'need_based'            => 'Need Based',
        'quota_based'           => 'Quota Based',
        'campaign'              => 'Campaign',
    ];

    public const BENEFIT_TYPES = [
        'percentage'      => 'Percentage',
        'fixed_amount'    => 'Fixed Amount',
        'fee_waiver'      => 'Fee Waiver',
        'seat_reservation'=> 'Seat Reservation',
    ];

    public const STATUSES = [
        'draft'     => 'Draft',
        'active'    => 'Active',
        'inactive'  => 'Inactive',
        'expired'   => 'Expired',
    ];

    protected $fillable = [
        'institution_id',
        'institution_program_id',
        'type',
        'title',
        'slug',
        'description',
        'minimum_gpa',
        'minimum_percentage',
        'benefit_type',
        'benefit_value',
        'total_slots',
        'used_slots',
        'start_date',
        'end_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'minimum_gpa'        => 'decimal:2',
            'minimum_percentage' => 'decimal:2',
            'benefit_value'      => 'decimal:2',
            'start_date'         => 'date',
            'end_date'           => 'date',
        ];
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function institutionProgram(): BelongsTo
    {
        return $this->belongsTo(InstitutionProgram::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function scholarshipApplications(): HasMany
    {
        return $this->hasMany(\App\Models\ScholarshipApplication::class);
    }
}
