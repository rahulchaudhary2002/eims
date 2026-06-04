<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Post;
use App\Models\PostReaction;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentPostReactionController extends Controller
{
    public function toggle(Request $request, Post $post): RedirectResponse
    {
        $request->validate([
            'reaction' => ['required', 'in:' . implode(',', array_keys(PostReaction::REACTIONS))],
        ]);

        $student = auth('student')->user();
        $existing = PostReaction::where('post_id', $post->id)
            ->where('reactable_type', Student::class)
            ->where('reactable_id', $student->id)
            ->first();

        if ($existing) {
            if ($existing->reaction === $request->reaction) {
                $existing->delete();
            } else {
                $existing->update(['reaction' => $request->reaction]);
            }
        } else {
            PostReaction::create([
                'post_id'        => $post->id,
                'reactable_type' => Student::class,
                'reactable_id'   => $student->id,
                'reaction'       => $request->reaction,
            ]);
        }

        return back();
    }

    public function shareToChat(Request $request, Post $post): RedirectResponse
    {
        $request->validate([
            'conversation_id' => ['required', 'exists:conversations,id'],
            'note'            => ['nullable', 'string', 'max:500'],
        ]);

        $student = auth('student')->user();
        $conversation = Conversation::where('id', $request->conversation_id)
            ->where('student_id', $student->id)
            ->firstOrFail();

        $post->loadMissing('media');

        $postUrl = route('website.posts.show', $post->slug);

        $lines = [];
        if ($request->note) {
            $lines[] = $request->note;
            $lines[] = '';
        }
        $lines[] = '📎 ' . $post->title;
        $lines[] = $postUrl;

        // Append public URLs of each media item
        if ($post->media->isNotEmpty()) {
            $lines[] = '';
            foreach ($post->media as $media) {
                $lines[] = \Illuminate\Support\Facades\Storage::url($media->file_path);
            }
        }

        // Use the post thumbnail (stored path) as the message attachment so it renders inline
        $attachment = $post->thumbnail ?: $post->media->where('type', 'image')->first()?->file_path;

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_type'     => Student::class,
            'sender_id'       => $student->id,
            'message'         => implode("\n", $lines),
            'attachment'      => $attachment,
        ]);

        return back()->with('success', 'Post shared to conversation.');
    }
}
