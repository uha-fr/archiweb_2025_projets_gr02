<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Illuminate\Notifications\Messages\DatabaseMessage;
use App\Models\Message;

class NewMessageNotification extends Notification
{
    use Queueable;

    protected $message;

    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'type' => 'message', 
            'sender_id' => $this->message->sender_id,
            'sender_name' => $this->message->sender->name,
            'route' => route('chatcenter', ['id' => $this->message->sender->id]), 
        ];
    }
}
