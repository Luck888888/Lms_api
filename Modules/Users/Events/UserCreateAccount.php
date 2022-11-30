<?php

namespace Modules\Users\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Users\Entities\User;

class UserCreateAccount
{
    use SerializesModels;
    use Dispatchable;

    public $user;
    public $password;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
