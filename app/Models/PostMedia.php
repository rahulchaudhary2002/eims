<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostMedia extends Model
{
    use HasFactory, SoftDeletes;

    const TYPES = [
        'image'    => 'Image',
        'video'    => 'Video',
        'document' => 'Document',
        'audio'    => 'Audio',
        'other'    => 'Other',
    ];

    protected $fillable = [
        'post_id',
        'type',
        'file_path',
        'caption',
    ];

    public function post(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
