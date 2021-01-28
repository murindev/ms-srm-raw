<?php

namespace App\Events;

use App\User;
use App\Models\Messenger\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * User that sent the message
     */
    public $user;

    /**
     * Message details
     *
     * @var \App\Models\Messenger\Message
     */
    public $message;
    public $from_user;
    public $to;
    public $row;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user,$message,$from_user,$to,$row)
    {
        $this->user = $user;
        $this->message = $message;
        $this->from_user = $from_user;
        $this->to = $to;
        $this->row = $row;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('chat');
//        return new PresenceChannel('chat.'.$this->to);
    }
}
