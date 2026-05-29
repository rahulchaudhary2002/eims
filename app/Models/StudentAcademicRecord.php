<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAcademicRecord extends Model
{
    /**
     * Education levels (used in dropdowns and filters).
     */
    public const LEVELS = [
        'slc_see'   => 'SLC / SEE',
        'plus2'     => '+2 / A-Level',
        'bachelor'  => 'Bachelor',
        'master'    => 'Master',
        'mphil'     => 'M.Phil',
        'phd'       => 'PhD',
        'diploma'   => 'Diploma / TSLC',
        'other'     => 'Other',
    ];

    /**
     * Examination boards / affiliating bodies.
     */
    public const BOARDS = [
        'neb'    => 'NEB',
        'ctevt'  => 'CTEVT',
        'tu'     => 'Tribhuvan University (TU)',
        'ku'     => 'Kathmandu University (KU)',
        'pu'     => 'Pokhara University (PU)',
        'purbanchal' => 'Purbanchal University',
        'mid_western' => 'Mid-Western University',
        'far_western' => 'Far-Western University',
        'agriculture' => 'Agriculture & Forestry University',
        'lumbini' => 'Lumbini Buddhist University',
        'mwu'     => 'Mid-Western University',
        'foreign' => 'Foreign Board / University',
        'other'   => 'Other',
    ];

    protected $fillable = [
        'student_id',
        'level',
        'institution_name',
        'board',
        'faculty',
        'passed_year',
        'gpa',
        'percentage',
        'symbol_number',
        'transcript_file',
        'character_certificate_file',
        'is_verified',
    ];

    protected $casts = [
        'gpa'        => 'decimal:2',
        'percentage' => 'decimal:2',
        'is_verified' => 'boolean',
        'passed_year' => 'integer',
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
