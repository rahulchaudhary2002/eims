<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class InstitutionMessageController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Message::class;
        $this->routeBase = 'messages';
        $this->title = 'Message';
        $this->fileFields = ['attachment' => 'messages'];
        $this->relationships = ['conversation', 'sender'];
        $this->readOnlyFields = ['sender_type', 'sender_id'];
        $this->fields = [
            'conversation_id' => ['label' => 'Conversation', 'type' => 'select', 'rules' => ['required', 'integer', 'exists:conversations,id']],
            'message' => ['label' => 'Message', 'type' => 'textarea', 'rules' => ['required', 'string']],
            'attachment' => ['label' => 'Attachment', 'type' => 'file', 'rules' => ['nullable', 'file', 'max:10240']],
        ];
    }

    protected function resourceQuery(): Builder
    {
        return Message::query()->whereHas('conversation', fn ($query) => $query->where('institution_id', $this->activeInstitutionId()));
    }

    protected function forceInstitutionScope(array $data, ?Model $record = null): array
    {
        abort_unless(Conversation::where('institution_id', $this->activeInstitutionId())->whereKey($data['conversation_id'] ?? null)->exists(), 403);
        $data['sender_type'] = \App\Models\User::class;
        $data['sender_id'] = auth('web')->id();

        return $data;
    }

    protected function selectOptions(): array
    {
        return [
            'conversation_id' => Conversation::where('institution_id', $this->activeInstitutionId())->latest()->get()->mapWithKeys(fn ($row) => [$row->id => 'Conversation #' . $row->id])->all(),
        ];
    }
}
