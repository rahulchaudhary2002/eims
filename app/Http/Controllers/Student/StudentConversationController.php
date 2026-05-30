<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentConversationController extends Controller
{
    public function index(Request $request): View
    {
        $studentId     = $request->user('student')->id;
        $conversations = Conversation::where('student_id', $studentId)
            ->with(['institution', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn($q) => $q->whereNull('read_at')
                ->where('sender_type', '!=', \App\Models\Student::class)])
            ->latest()
            ->paginate(15);

        return view('student.conversations.index', compact('conversations'));
    }

    public function create(Request $request): View
    {
        $institutions = Institution::active()->orderBy('name')->get();

        $selected = null;
        if ($request->has('institution')) {
            $selected = Institution::where('slug', $request->institution)->first();
        }

        return view('student.conversations.create', compact('institutions', 'selected'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'institution_id' => ['required', 'integer', 'exists:institutions,id'],
            'type'           => ['required', 'in:' . implode(',', array_keys(Conversation::TYPES))],
        ]);

        $studentId = $request->user('student')->id;

        $existing = Conversation::where('student_id', $studentId)
            ->where('institution_id', $request->institution_id)
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            return redirect()->route('student.conversations.show', $existing);
        }

        $conversation = Conversation::create([
            'student_id'     => $studentId,
            'institution_id' => $request->institution_id,
            'type'           => $request->type,
        ]);

        return redirect()->route('student.conversations.show', $conversation);
    }

    public function show(Request $request, Conversation $conversation): View
    {
        abort_if($conversation->student_id !== $request->user('student')->id, 403);

        $conversation->load(['institution', 'messages.sender']);

        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_type', '!=', \App\Models\Student::class)
            ->update(['read_at' => now()]);

        return view('student.conversations.show', compact('conversation'));
    }
}
