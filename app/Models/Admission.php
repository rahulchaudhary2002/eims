<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admission extends Model
{
    public const VERIFICATION_STATUSES = [
        'pending'  => 'Pending',
        'verified' => 'Verified',
        'rejected' => 'Rejected',
    ];

    protected $fillable = [
        'application_id',
        'student_id',
        'institution_id',
        'institution_program_id',
        'admission_number',
        'admission_date',
        'paid_amount',
        'payment_proof',
        'verification_status',
        'verified_by',
        'verified_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'admission_date' => 'date',
            'paid_amount'    => 'decimal:2',
            'verified_at'    => 'datetime',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function institutionProgram(): BelongsTo
    {
        return $this->belongsTo(InstitutionProgram::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function commissionInvoice(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(\App\Models\CommissionInvoice::class);
    }
}
