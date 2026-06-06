<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewRegistrationNotification implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $type,  // 'student' | 'institution'
        public string $name,
        public string $email,
        public int $id,
        public string $url,
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('platform.notifications')];
    }

    public function broadcastAs(): string
    {
        return 'new.registration';
    }

    public function broadcastWith(): array
    {
        return [
            'type'    => $this->type,
            'name'    => $this->name,
            'email'   => $this->email,
            'message' => ucfirst($this->type) . ' ' . $this->name . ' just registered.',
            'time'    => now()->diffForHumans(),
            'url'     => $this->url,
        ];
    }
}
