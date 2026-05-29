<?php

namespace App\Http\Controllers\Institution;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Institution\Concerns\HandlesInstitutionResources;
use App\Models\Conversation;

class InstitutionConversationController extends Controller
{
    use HandlesInstitutionResources;

    public function __construct()
    {
        $this->modelClass = Conversation::class;
        $this->routeBase = 'conversations';
        $this->title = 'Conversation';
        $this->relationships = ['student', 'messages'];
        $this->selectOptions = ['type' => Conversation::TYPES];
        $this->fields = [
            'student_id' => ['label' => 'Student'],
            'type' => ['label' => 'Type'],
        ];
    }
}
