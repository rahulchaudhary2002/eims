<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inquiry extends Model
{
    use HasFactory, SoftDeletes;

    const SOURCES = [
        'direct'       => 'Direct',
        'website'      => 'Website',
        'referral'     => 'Referral',
        'social_media' => 'Social Media',
        'event'        => 'Event',
        'other'        => 'Other',
    ];

    const STATUSES = [
        'new'           => 'New',
        'contacted'     => 'Contacted',
        'qualified'     => 'Qualified',
        'not_qualified' => 'Not Qualified',
        'converted'     => 'Converted',
        'closed'        => 'Closed',
    ];

    protected $fillable = [
        'student_id',
        'institution_id',
        'applicable_type',
        'applicable_id',
        'name',
        'email',
        'phone',
        'message',
        'source',
        'status',
        'assigned_to',
        'last_contacted_at',
    ];

    protected $casts = [
        'last_contacted_at' => 'datetime',
    ];

    public function student(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function institution(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function applicable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignedTo(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function notes(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadNote::class);
    }

    public function followUps(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadFollowUp::class);
    }
}
