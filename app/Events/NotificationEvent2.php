<?php

namespace App\Events;

use App\Models\NotificationAudience;
use App\Models\SharedNotification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationEvent2 implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;
    public $body;
    public $title;
    public $type;
    public $category;
    public $notification;
    public $id;
    public NotificationAudience $audience;

    /**
     * Create a new event instance.
     */
    public function __construct(int $id, NotificationAudience $audience, string $type, string $category, string $title, $data, string $body)
    {
        $this->title = $title;
        $this->type = $type;
        $this->category = $category;
        $this->id = $id;
        $this->audience = $audience;
        $this->data = $data;
        $this->body = $body;
        Log::info('NotificationEvent created', [
            'id' => $this->audience,
            // 'title' => $this->title,
            // 'type' => $this->type,
            // 'category' => $this->category,
            // 'notification' => $this->notification
        ]);

       $notification = SharedNotification::create([
            'notification_audience_id' => $this->audience->id,
            'notification_type_id'=> $this->type,
            'notification_category_id' => $this->category,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data
        ]);
        $notification->users()->attach([1]);

    }

    /**
     * Get the channel the event should broadcast on.
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('notifications.' . $this->audience->id);
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
            'notification_audience_id' => $this->audience->id,
            'notification_type_id'=> $this->type,
            'notification_category_id' => $this->category,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data
        ];
    }
}
