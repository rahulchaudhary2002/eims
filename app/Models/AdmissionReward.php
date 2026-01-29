<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionReward extends Model
{
    protected $fillable = [
        'user_id',
        'admission_application_id',
        'admission_receipt',
        'reward',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admissionApplication()
    {
        return $this->belongsTo(AdmissionApplication::class);
    }
}
