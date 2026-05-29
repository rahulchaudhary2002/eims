<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    protected $fillable = [
        'student_id',
        'guardian_name',
        'guardian_phone',
        'province',
        'district',
        'city',
        'address',
        'budget_min',
        'budget_max',
        'preferred_location',
        'career_interests',
        'preferred_faculties',
    ];

    protected $casts = [
        'budget_min'          => 'integer',
        'budget_max'          => 'integer',
        'career_interests'    => 'array',
        'preferred_faculties' => 'array',
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
