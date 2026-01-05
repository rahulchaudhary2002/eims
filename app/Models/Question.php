<?php

namespace App\Models;

use App\Enums\QuestionCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'user_id',
        'category',
        'title',
        'body',
        'is_anonymous',
        'is_draft',
        'published_at',
        'views_count',
        'replies_count',
    ];

    protected $casts = [
        'category'     => QuestionCategory::class,
        'is_anonymous' => 'boolean',
        'is_draft'     => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Reply::class)
            ->whereNull('parent_id')
            ->orderBy('created_at');
    }

    public function scopeLatestFirst($query)
    {
        return $query->orderByDesc('created_at');
    }

    public function scopeTrending($query)
    {
        return $query->orderByDesc('views_count');
    }

    public function scopeOwnedBy($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
