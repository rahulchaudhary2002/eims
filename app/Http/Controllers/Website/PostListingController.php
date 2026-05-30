<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\Post;
use Illuminate\Http\Request;

class PostListingController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::where('is_published', true)
            ->with(['institution', 'media'])
            ->orderByDesc('published_at');

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($institution = $request->input('institution')) {
            $query->where('institution_id', $institution);
        }

        if ($from = $request->input('from')) {
            $query->whereDate('published_at', '>=', $from);
        }

        if ($to = $request->input('to')) {
            $query->whereDate('published_at', '<=', $to);
        }

        $posts = $query->paginate(12)->withQueryString();

        $types        = Post::TYPES;
        $institutions = Institution::active()->orderBy('name')->get(['id', 'name']);

        return view('website.posts.index', compact('posts', 'types', 'institutions'));
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published, 404);

        $post->load(['institution', 'media', 'reactions', 'comments' => fn($q) => $q->orderByDesc('created_at')]);

        $related = Post::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where(function ($q) use ($post) {
                $q->where('institution_id', $post->institution_id)
                  ->orWhere('type', $post->type);
            })
            ->with('institution')
            ->orderByDesc('published_at')
            ->limit(3)
            ->get();

        return view('website.posts.show', compact('post', 'related'));
    }
}
