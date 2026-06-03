<?php

namespace App\Http\Controllers\Admin;

use App\Events\MessageSent;
use App\Http\Controllers\Admin\Concerns\ScopesForInstitution;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    use ScopesForInstitution;

    public function index(): RedirectResponse
    {
        return redirect()->route('admin.conversations.index');
    }

    public function show(Message $message): RedirectResponse
    {
        return redirect()->route('admin.conversations.show', $message->conversation_id);
    }

    public function store(StoreMessageRequest $request, Conversation $conversation): RedirectResponse|JsonResponse
    {
        $this->authorizeConversationAccess($conversation);

        $data = $request->validated();
        $data['conversation_id'] = $conversation->id;

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
