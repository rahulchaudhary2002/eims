<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CounselingSession extends Model
{
    use HasFactory, SoftDeletes;

    const MODES = [
        'online'     => 'Online',
        'in_person'  => 'In Person',
        'phone'      => 'Phone',
    ];

    const STATUSES = [
        'scheduled'  => 'Scheduled',
        'completed'  => 'Completed',
        'cancelled'  => 'Cancelled',
        'no_show'    => 'No Show',
        'rescheduled'=> 'Rescheduled',
    ];

    protected $fillable = [
        'student_id',
        'institution_id',
        'counselor_id',
        'mode',
        'scheduled_at',
        'status',
        'student_message',
        'counselor_notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function counselor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }
}
