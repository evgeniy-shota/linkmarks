<?php

namespace App\Listeners\User;

use App\Events\User\CreatedEvent;
use App\Models\Profile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateProfileListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CreatedEvent $event): void
    {
        Profile::firstOrCreate([
            'user_id' => $event->user->id,
        ]);
    }
}
