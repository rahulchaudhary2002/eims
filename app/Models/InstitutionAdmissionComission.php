<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionAdmissionComission extends Model
{
    protected $fillable = [
        'institution_id',
        'admission_reward_id',
        'comission_amount',
        'is_paid',
    ];
}
