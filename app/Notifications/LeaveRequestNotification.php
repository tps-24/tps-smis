<?php
namespace App\Notifications;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;


// app/Notifications/LeaveRequestNotification.php
namespace App\Notifications;

use App\Models\LeaveRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LeaveRequestNotification extends Notification
{
    use Queueable;

    public $leaveRequest;

    public function __construct(LeaveRequest $leaveRequest)
    {
        $this->leaveRequest = $leaveRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('A leave request has been processed.')
                    ->action('View Leave Request', url('/leave/requests/'.$this->leaveRequest->id))
                    ->line('Status: ' . $this->leaveRequest->status)
                    ->line($this->leaveRequest->rejection_reason ? 'Rejection Reason: ' . $this->leaveRequest->rejection_reason : '');
    }

    public function toDatabase($notifiable)
    {
        return [
            'leave_request_id' => $this->leaveRequest->id,
            'status' => $this->leaveRequest->status,
            'rejection_reason' => $this->leaveRequest->rejection_reason,
        ];
    }
}
