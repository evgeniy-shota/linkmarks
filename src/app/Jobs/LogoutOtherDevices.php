<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Auth;

class LogoutOtherDevices implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private string $password)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Auth::logoutOtherDevices($this->password);
    }
}
