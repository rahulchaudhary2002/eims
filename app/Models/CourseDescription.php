<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseDescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'content',
        'order',
    ];

    /**
     * Get the course this description belongs to.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
