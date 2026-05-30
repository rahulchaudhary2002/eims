<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreStudentMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentMessageController extends Controller
{
    public function index(Request $request): View
    {
        $studentId     = $request->user('student')->id;
        $conversations = Conversation::where('student_id', $studentId)
            ->with(['institution', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn($q) => $q->whereNull('read_at')
                ->where('sender_type', '!=', \App\Models\Student::class)])
            ->latest()
            ->get();

        return view('student.messages.index', compact('conversations'));
    }

    public function show(Request $request, Message $message): View
    {
        $studentId = $request->user('student')->id;
        abort_if($message->conversation->student_id !== $studentId, 403);

        return view('student.messages.show', compact('message'));
    }

    public function store(StoreStudentMessageRequest $request, Conversation $conversation): RedirectResponse
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

        Message::create($data);

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
