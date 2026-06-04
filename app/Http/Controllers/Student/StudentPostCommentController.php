<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentPostCommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $request->validate([
            'comment'   => ['required', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'exists:post_comments,id'],
        ]);

        PostComment::create([
            'post_id'          => $post->id,
            'parent_id'        => $request->input('parent_id'),
            'commentable_type' => Student::class,
            'commentable_id'   => auth('student')->id(),
            'comment'          => $request->input('comment'),
        ]);

        return back()->with('success', 'Comment posted.');
    }

    public function destroy(PostComment $postComment): RedirectResponse
    {
        abort_unless(
            $postComment->commentable_type === Student::class &&
            $postComment->commentable_id === auth('student')->id(),
            403
        );
        $postComment->delete();
        return back()->with('success', 'Comment deleted.');
    }
}
