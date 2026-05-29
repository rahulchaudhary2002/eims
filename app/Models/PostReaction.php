<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostReaction extends Model
{
    use HasFactory, SoftDeletes;

    const REACTIONS = [
        'like'       => 'Like',
        'love'       => 'Love',
        'celebrate'  => 'Celebrate',
        'insightful' => 'Insightful',
        'curious'    => 'Curious',
    ];

    const REACTABLE_TYPES = [
        'App\Models\Student' => 'Student',
        'App\Models\User'    => 'User',
    ];

    protected $fillable = [
        'post_id',
        'reactable_type',
        'reactable_id',
        'reaction',
    ];

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function reactable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }
}
