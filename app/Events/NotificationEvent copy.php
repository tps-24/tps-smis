<?php
/*
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotificationEvent  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $notification;
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */

    /**
     * Create a new event instance.
     *
     * @param $notification
     * @return void
     */
     /*private $user_id;
     private $title;
     private $type;
     private $category;
     public function __construct($title,$type,$category,$notification)
     {
        $this->type = $type;
        $this->title = $title;
        $this->category = $category;
         $this->notification = $notification;
     }
 
     public function broadcastOn()
     {
         return new Channel('notifications');
     }
     public function broadcastAs()
     {
         return 'notification';
     }

     public function broadcastWith()
     {
         return [
            'category' => $this->category,
            'title' => $this->title,
            'type' => $this->type,
            'data' => $this->notification
         ];
     }
     
 }


