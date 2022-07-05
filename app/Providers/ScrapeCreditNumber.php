<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScrapeCreditNumber
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $creditNumber;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($creditNumber, $user, $pass)
    {
        $this->creditNumber = $creditNumber;
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
