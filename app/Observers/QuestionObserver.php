<?php

namespace App\Observers;

use App\Models\Question;

class QuestionObserver
{
    public function creating(Question $question): void
    {
        if (! $question->is_draft && ! $question->published_at) {
            $question->published_at = now();
        }
    }
}
