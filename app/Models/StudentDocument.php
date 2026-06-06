<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    const DOCUMENT_TYPES = [
        'citizenship'   => 'Citizenship',
        'passport'      => 'Passport',
        'birth_cert'    => 'Birth Certificate',
        'nid'           => 'National ID',
        'pp_photo'      => 'Passport Photo',
        'migration'     => 'Migration Certificate',
        'equivalence'   => 'Equivalence Certificate',
        'recommendation'=> 'Recommendation Letter',
        'medical'       => 'Medical Certificate',
        'other'         => 'Other',
    ];

    const STATUSES = [
        'active'   => 'Active',
        'inactive' => 'Inactive',
        'expired'  => 'Expired',
    ];

    protected $fillable = [
        'student_id',
        'academic_record_id',
        'document_type',
        'title',
        'file_path',
        'status',
        'remarks',
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicRecord(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(StudentAcademicRecord::class, 'academic_record_id');
    }
}
