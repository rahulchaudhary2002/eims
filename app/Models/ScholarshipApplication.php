<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScholarshipApplication extends Model
{
    use HasFactory, SoftDeletes;

    const STATUSES = [
        'pending'      => 'Pending',
        'under_review' => 'Under Review',
        'approved'     => 'Approved',
        'rejected'     => 'Rejected',
        'withdrawn'    => 'Withdrawn',
    ];

    protected $fillable = [
        'scholarship_id',
        'student_id',
        'application_id',
        'status',
        'approved_amount',
        'remarks',
    ];

    protected $casts = [
        'approved_amount' => 'decimal:4',
    ];

    public function scholarship(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Scholarship::class);
    }

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function application(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}
