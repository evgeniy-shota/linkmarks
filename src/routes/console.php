<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('app:remove-unused-images')->weekly();

Schedule::command('app:prune-delete-account-tokens')->daily();

// creates (if does not exist) test users with test data
Schedule::command('app:init-test-users')->everyThirtyMinutes();
