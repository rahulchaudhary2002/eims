<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
*/

// Private conversation channel — authorizes students, institution users, and super admins.
Broadcast::channel('conversation.{conversationId}', function ($user, int $conversationId) {
    $conversation = Conversation::find($conversationId);

    if (! $conversation) {
        return false;
    }

    // Super admin (web guard) can access any conversation
    if ($user instanceof \App\Models\User && $user->is_super_admin) {
        return true;
    }

    // Institution user (web guard) — must belong to the conversation's institution
    if ($user instanceof \App\Models\User) {
        return $user->institutions()
            ->where('institutions.id', $conversation->institution_id)
            ->where('user_institutions.is_active', true)
            ->exists();
    }

    // Student (student guard) — must own the conversation
    if ($user instanceof \App\Models\Student) {
        return (int) $user->id === (int) $conversation->student_id;
    }

    return false;
}, ['guards' => ['web', 'student']]);
