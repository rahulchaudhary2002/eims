<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_id',
        'user_id',
        'full_name',
        'email',
        'phone',
        'status',
        'notes',
        'course_id',
        'grade',
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
}
