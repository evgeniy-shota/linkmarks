<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\UserServices;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUser extends Command
{

    public function __construct(protected UserServices $userServices)
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user {name=SomeUser} {email?} {password?} {--admin} {--count=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->argument('email');
        $defaultEmailDomain = "mail.com";
        $isAdmin = $this->option('admin');
        $count = $this->option('count');
        $generateEmail = false;

        while (true) {
            if ($generateEmail) {
                $email = strtolower(Str::random(5)) . ".$name@$defaultEmailDomain";

                if (User::where('email', $email)->exists()) {
                    continue;
                }

                $this->info("Email generated: $email");
                break;
            } else if ($email) {
                if (
                    !filter_var($email, FILTER_VALIDATE_EMAIL)
                    || User::where('email', $email)->exists()
                ) {
                    $this->warn('Email is invalid or already taken');

                    if (!$this->confirm('Confirm with generated email?', true)) {
                        $this->info("Stopped");
                        return;
                    } else {
                        $generateEmail = true;
                        continue;
                    }
                } else {
                    break;
                }
            } else {
                $generateEmail = true;
            }
        }

        $email = explode('@', $email);

        for ($i = 0; $i < $count; $i++) {

            $uName = $name . ($count > 1 ? $i : '');
            $uPassword = $this->argument('password') ?? $uName;

            $user = $this->userServices->create([
                'name' => $uName,
                'email' => $email[0] . ($count > 1 ? $i + 1 : '') . '@' . $email[1],
                'password' => $uPassword,
                'is_admin' => $isAdmin,
            ]);
            $this->userInfoOutput($user, $uPassword);
        }
    }

    protected function userInfoOutput(User $user, string $unhashedPassword): void
    {
        $role = $user->is_admin ? 'Admin' : 'User';
        $password = $unhashedPassword ?? $user->password;
        $info = "Created $role: $user->name  pass: $password  email: $user->email" . PHP_EOL;

        $this->info($info);
    }
}
