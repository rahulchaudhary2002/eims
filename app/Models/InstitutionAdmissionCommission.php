<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionAdmissionCommission extends Model
{
    protected $fillable = [
        'institution_id',
        'admission_reward_id',
        'commission_amount',
        'is_paid',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }

    public function admissionReward()
    {
        return $this->belongsTo(AdmissionReward::class);
    }
}
