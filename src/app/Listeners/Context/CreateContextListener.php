<?php

namespace App\Listeners\Context;

use App\Events\User\CreatedEvent;
use App\Models\Context;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateContextListener
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
        Context::firstOrCreate(
            [
                'user_id' => $event->user->id,
                'is_root' => true,
            ],
            [
                'name' => "Root",
                'parent_context_id' => null,
                'is_enabled' => true,
                'order' => null,
            ]
        );
    }
}
