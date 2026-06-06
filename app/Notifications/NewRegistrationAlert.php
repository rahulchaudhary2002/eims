<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class NewRegistrationAlert extends Notification
{
    public function __construct(
        public string $type,   // 'student' | 'institution'
        public string $name,
        public string $email,
        public string $url,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'    => $this->type,
            'name'    => $this->name,
            'email'   => $this->email,
            'message' => ucfirst($this->type) . ' ' . $this->name . ' just registered.',
            'url'     => $this->url,
        ];
    }
}
