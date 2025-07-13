<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class pruneDeleteAccountTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:prune-delete-account-tokens {--days=7}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes expired account deletion tokens. --days=7';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days') ?? 7;
        $date = now()->subDays($days > 0 ? $days : 7);
        DB::table('delete_account_tokens')
            ->where('created_at', '<', $date)->delete();
    }
}
