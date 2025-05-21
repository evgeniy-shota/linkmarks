<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\Foreach_;

class GetUserData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:get-user-data {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (isset($email)) {
            $user = User::where('email', $email)->first();

            if ($user) {
                $this->info($this->userDataToString($user));
                return;
            } else if (!$this->confirm('User not founded, display information about all users?', true)) {
                return;
            }
        }

        $users = User::all();

        if ($users) {

            foreach ($users as $user) {
                $this->info($this->userDataToString($user));
            }
            return;
        }

        $this->info('Noting found...');
    }

    public function userDataToString(User $user)
    {
        return "User - {$user->name}: email: {$user->email} | pass: " . $this->checkPassword($user);
    }

    public function checkPassword(User $user, string $password = ''): ?string
    {
        if ($password && Hash::check($user->name,  $password)) {
            return $password;
        }

        if (Hash::check($user->name,  $user->password)) {
            return $user->name;
        }

        return null;
    }
}
