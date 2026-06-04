<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentFeedController extends Controller
{
    public function index(Request $request): View
    {
        $student = auth('student')->user();
        $followedIds = $student->follows()->pluck('institution_id');

        $posts = Post::where('is_published', true)
            ->whereIn('institution_id', $followedIds)
            ->with(['institution', 'media', 'reactions', 'comments'])
            ->withCount(['reactions', 'comments'])
            ->orderByDesc('published_at')
            ->paginate(10)
            ->withQueryString();

        // The student's own reactions keyed by post_id
        $myReactions = \App\Models\PostReaction::where('reactable_type', \App\Models\Student::class)
            ->where('reactable_id', $student->id)
            ->whereIn('post_id', $posts->pluck('id'))
            ->pluck('reaction', 'post_id');

        return view('student.feed.index', compact('posts', 'myReactions', 'followedIds'));
    }
}
