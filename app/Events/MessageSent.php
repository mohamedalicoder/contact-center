<?php 
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $chat;
    public $message;

    public function __construct($chat, $message)
    {
        $this->chat = $chat;
        $this->message = $message;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->chat->id);
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'user_name' => $this->message->user->name,
            'chat_id' => $this->message->chat_id
        ];
    }

    public function broadcastAs()
    {
        return 'message-sent';
    }
}
