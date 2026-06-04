<?php

namespace App\Http\Controllers\Student;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentMessageController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('student.conversations.index');
    }

    public function show(Message $message): RedirectResponse
    {
        return redirect()->route('student.conversations.show', $message->conversation_id);
    }

    public function store(StoreStudentMessageRequest $request, Conversation $conversation): RedirectResponse|JsonResponse
    {
        abort_if($conversation->student_id !== $request->user('student')->id, 403);

        $student  = $request->user('student');
        $data     = $request->validated();
        $data['conversation_id'] = $conversation->id;
        $data['sender_type']     = \App\Models\Student::class;
        $data['sender_id']       = $student->id;

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')
                ->store("students/{$student->id}/messages", 'public');
        }

        $message = Message::create($data);
        $message->load('sender');

        broadcast(new MessageSent($message))->toOthers();

        if ($request->expectsJson()) {
            return response()->json([
                'id'              => $message->id,
                'conversation_id' => $message->conversation_id,
                'sender_type'     => $message->sender_type,
                'sender_id'       => $message->sender_id,
                'sender_name'     => $message->sender?->name ?? 'Unknown',
                'sender_avatar'   => $message->sender?->avatar ?? null,
                'message'         => $message->message,
                'attachment'      => $message->attachment,
                'created_at'      => $message->created_at->format('M d · h:i A'),
                'created_at_diff' => $message->created_at->diffForHumans(),
            ]);
        }

        return back();
    }

    public function destroy(Request $request, Message $message): RedirectResponse
    {
        $studentId = $request->user('student')->id;
        abort_if($message->conversation->student_id !== $studentId, 403);
        abort_if($message->sender_type !== \App\Models\Student::class || $message->sender_id !== $studentId, 403);

        if ($message->attachment) {
            Storage::disk('public')->delete($message->attachment);
        }

        $message->delete();

        return back()->with('success', 'Message deleted.');
    }
}
