<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Reply;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
    public function store(Request $request, Question $question)
    {
        $validated = $request->validate([
            'body'         => 'required|string',
            'parent_id'    => 'nullable|exists:replies,id',
            'is_anonymous' => 'sometimes|boolean',
        ]);

        Reply::create([
            'question_id'  => $question->id,
            'user_id'      => $request->user()->id,
            'parent_id'    => $validated['parent_id'] ?? null,
            'body'         => $validated['body'],
            'is_anonymous' => $validated['is_anonymous'] ?? false,
        ]);

        return back()->with('status', 'Reply added.');
    }
}
