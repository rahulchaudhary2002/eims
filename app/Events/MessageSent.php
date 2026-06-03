<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Message $message)
    {
        $this->message->load('sender');
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('conversation.' . $this->message->conversation_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }

    public function broadcastWith(): array
    {
        $msg = $this->message;

        return [
            'id'              => $msg->id,
            'conversation_id' => $msg->conversation_id,
            'sender_type'     => $msg->sender_type,
            'sender_id'       => $msg->sender_id,
            'sender_name'     => $msg->sender?->name ?? 'Unknown',
            'sender_avatar'   => $msg->sender?->avatar ?? null,
            'message'         => $msg->message,
            'attachment'      => $msg->attachment,
            'created_at'      => $msg->created_at->format('M d · h:i A'),
            'created_at_diff' => $msg->created_at->diffForHumans(),
        ];
    }
}
