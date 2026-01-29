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
        'course_id',
        'grade',
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

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function reward()
    {
        return $this->hasOne(AdmissionReward::class)->where('user_id', Auth::id());
    }
}
