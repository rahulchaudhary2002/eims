<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AdmissionApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_id',
        'application_uuid',
        'user_id',
        'full_name',
        'email',
        'phone',
        'status',
        'notes',
        'program_id',
        'academic_documents',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function reward()
    {
        return $this->hasOne(AdmissionReward::class)->where('user_id', Auth::id());
    }
}
