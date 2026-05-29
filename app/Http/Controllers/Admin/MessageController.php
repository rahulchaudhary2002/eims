<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Institution;
use App\Models\Message;
use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MessageController extends Controller
{
    use ScopesForInstitution;

    public function index(Request $request): View
    {
        $query = Message::with(['conversation', 'sender']);

        $this->applyConversationScope($query);

        if ($conversationId = $request->input('conversation_id')) {
            $query->where('conversation_id', $conversationId);
        }
        if ($senderType = $request->input('sender_type')) {
            $query->where('sender_type', $senderType);
        }
        if ($request->filled('read_status')) {
            if ($request->input('read_status') === 'read') {
                $query->whereNotNull('read_at');
            } else {
                $query->whereNull('read_at');
            }
        }
        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $messages      = $query->latest()->paginate(30)->withQueryString();
        $conversations = $this->conversationDropdownQuery()->get(['id']);
        $senderTypes   = Message::SENDER_TYPES;

        return view('admin.modules.messages.index', compact('messages', 'conversations', 'senderTypes'));
    }

    public function show(Message $message): View
    {
        $this->authorizeMessageAccess($message);
        $message->load(['conversation.student', 'conversation.institution', 'sender']);

        return view('admin.modules.messages.show', compact('message'));
    }

    public function store(StoreMessageRequest $request, Conversation $conversation): RedirectResponse
    {
        $this->authorizeConversationAccess($conversation);

        $data = $request->validated();
        $data['conversation_id'] = $conversation->id;

        if ($request->hasFile('attachment')) {
            $data['attachment'] = $request->file('attachment')->store('messages', 'public');
        }

        Message::create($data);

        return redirect()->route('admin.conversations.show', $conversation)
            ->with('success', 'Message sent.');
    }

    public function destroy(Message $message): RedirectResponse
    {
        $this->authorizeMessageAccess($message);

        if ($message->attachment) {
            Storage::disk('public')->delete($message->attachment);
        }

        $conversationId = $message->conversation_id;
        $message->delete();

        return redirect()->route('admin.conversations.show', $conversationId)
            ->with('success', 'Message deleted.');
    }

    private function applyConversationScope(Builder $query): void
    {
        $scope = $this->institutionScope();
        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->whereHas('conversation', fn (Builder $q) => $q->where('institution_id', $scope));
            }
        }
    }

    private function conversationDropdownQuery(): Builder
    {
        $query = Conversation::orderByDesc('id');
        $scope = $this->institutionScope();

        if ($scope !== null) {
            if (! $this->currentInstitutionIsAssigned()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where('institution_id', $scope);
            }
        }

        return $query;
    }

    private function authorizeConversationAccess(Conversation $conversation): void
    {
        if ($conversation->institution_id) {
            $this->authorizeInstitution((int) $conversation->institution_id);
            return;
        }

        $user = auth('web')->user();
        abort_unless($user?->is_super_admin, 403, 'You do not have access to this conversation.');
    }

    private function authorizeMessageAccess(Message $message): void
    {
        $message->loadMissing('conversation');
        $this->authorizeConversationAccess($message->conversation);
    }

    private function authorizeInstitution(int $institutionId): void
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return;
        }

        abort_unless(
            (int) session('current_institution_id', 0) === $institutionId
                && $user?->activeInstitutions()->where('institutions.id', $institutionId)->exists(),
            403,
            'You do not have access to this institution.'
        );
    }

    private function currentInstitutionIsAssigned(): bool
    {
        $user = auth('web')->user();

        if ($user?->is_super_admin) {
            return true;
        }

        $scope = (int) session('current_institution_id', 0);

        return $scope > 0
            && (bool) $user?->activeInstitutions()->where('institutions.id', $scope)->exists();
    }
}
