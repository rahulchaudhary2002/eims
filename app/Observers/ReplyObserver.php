<?php

namespace App\Observers;

use App\Models\Reply;

class ReplyObserver
{
    public function creating(Reply $reply): void
    {
        if ($reply->parent_id) {
            $parent = Reply::findOrFail($reply->parent_id);

            // Ensure same question & increase depth
            $reply->question_id = $parent->question_id;
            $reply->depth = $parent->depth + 1;

            if ($reply->depth > 3) {
                throw new \DomainException('Maximum reply depth is 3.');
            }
        } else {
            $reply->depth = 1;
        }
    }

    public function created(Reply $reply): void
    {
        $reply->question()->increment('replies_count');
    }

    public function deleted(Reply $reply): void
    {
        $reply->question()->decrement('replies_count');
    }
}
