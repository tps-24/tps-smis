<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $title;
    public $type;
    public $category;
    public $notification;
    public $id;
    public $audience;

    /**
     * Create a new event instance.
     */
    public function __construct(string $title, string $type, string $category, $notification, int $id, $audience)
    {
        $this->title = $title;
        $this->type = $type;
        $this->category = $category;
        $this->notification = $notification;
        $this->id = $id;
        $this->audience = $audience;

        // Log::info('NotificationEvent created', [
        //     'id' => $this->audience,
        //     // 'title' => $this->title,
        //     // 'type' => $this->type,
        //     // 'category' => $this->category,
        //     // 'notification' => $this->notification
        // ]);
    }

    /**
     * Get the channel the event should broadcast on.
     */
public function broadcastOn(): PrivateChannel
{
    return new PrivateChannel('notifications.' . $this->audience);
}


    /**
     * Get the event name to broadcast as.
     */
    public function broadcastAs(): string
    {
        return 'notification';
    }

    /**
     * Data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'category' => $this->category,
            'title' => $this->title,
            'type' => $this->type,
            'id' => $this->id,
            'data' => $this->notification,
        ];
    }
}
