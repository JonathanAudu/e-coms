<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MessageNotification extends Notification
{
    use Queueable;
    private $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'subject' => $this->details['subject'],
            'sender' => $this->details['sender'],
            'actionURL' => $this->details['actionURL'],
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'subject' => $this->details['subject'],
            'sender' => $this->details['sender'],
            'actionURL' => $this->details['actionURL'],
            'time' => date('F d, Y h:i A')
        ]);
    }
}
