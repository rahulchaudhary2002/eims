<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdmissionGrade extends Model
{
    protected $fillable = ['admission_id', 'grade', 'order'];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }
}
