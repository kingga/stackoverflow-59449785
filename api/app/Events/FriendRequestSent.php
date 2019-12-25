<?php

namespace App\Events;

use App\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FriendRequestSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The user who sent the friend request.
     *
     * @var User
     */
    public $user;

    /**
     * The person who is receiving the friend request.
     *
     * @var User
     */
    public $friend;

    /**
     * The route which this user needs for accepting this request.
     *
     * @var string
     */
    public $acceptRoute;

    /**
     * The route which this user needs for denying this request.
     *
     * @var string
     */
    public $denyRoute;

    /**
     * The route which this user needs to block the user.
     *
     * @var string
     */
    public $blockRoute;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, User $friend)
    {
        $this->user = $user;
        $this->friend = $friend;
        // $this->acceptRoute = route('friend.accept', ['id' => $user->id]);
        // $this->denyRoute = route('friend.deny', ['id' => $user->id]);
        // $this->blockRoute = route('friend.block', ['id' => $user->id]);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel("App.User.{$this->friend->id}");
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'friend.request';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'user' => $this->user->id,
            'acceptRoute' => $this->acceptRoute,
            'denyRoute' => $this->denyRoute,
            'blockRoute' => $this->blockRoute,
        ];
    }
}
