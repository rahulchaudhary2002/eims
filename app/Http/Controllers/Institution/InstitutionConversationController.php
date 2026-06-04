<?php

namespace App\Http\Controllers\Institution;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionConversationController extends Controller
{
    use HandlesInstitutionResources;

    public function index(): View
    {
        $institutionId = $this->activeInstitutionId();

        $conversations = Conversation::where('institution_id', $institutionId)
            ->with(['student', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn($q) => $q->whereNull('read_at')
                ->where('sender_type', Student::class)])
            ->latest()
            ->get();

        return view('institution.modules.conversations.index', compact('conversations'));
    }

    public function show(Conversation $conversation): View
    {
        $institutionId = $this->activeInstitutionId();
        abort_if($conversation->institution_id !== $institutionId, 403);

        $conversation->load(['student', 'messages.sender']);

        // Mark student messages as read
        $conversation->messages()
            ->whereNull('read_at')
            ->where('sender_type', Student::class)
            ->update(['read_at' => now()]);

        $conversations = Conversation::where('institution_id', $institutionId)
            ->with(['student', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->withCount(['messages as unread_count' => fn($q) => $q->whereNull('read_at')
                ->where('sender_type', Student::class)])
            ->latest()
            ->get();

        return view('institution.modules.conversations.show', compact('conversation', 'conversations'));
    }

    public function storeMessage(Request $request, Conversation $conversation): RedirectResponse|\Illuminate\Http\JsonResponse
    {
        abort_if($conversation->institution_id !== $this->activeInstitutionId(), 403);

        $request->validate([
            'message'    => ['required_without:attachment', 'nullable', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ]);

        $data = [
            'conversation_id' => $conversation->id,
            'sender_type'     => \App\Models\User::class,
            'sender_id'       => auth('web')->id(),
            'message'         => $request->message,
        ];

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('messages', 'public');
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
}
