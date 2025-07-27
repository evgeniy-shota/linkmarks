<?php

namespace App\Services;

use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserServices
{
    public function __construct(protected ContextService $contextService) {}

    /**
     * Create new user with profile and root context
     * 
     * @return User|null
     */
    public function create(array $data)
    {
        try {
            $newUser = DB::transaction(function () use ($data) {
                $user = User::create($data);

                Profile::create([
                    'user_id' => $user->id,
                ]);

                $this->contextService->createContext([
                    'name' => 'Root',
                    'is_root' => true,
                    'parent_context_id' => null,
                    'is_enabled' => true,
                    'order' => null,
                ], $user->id);

                return $user;
            }, 3);
        } catch (Exception $e) {
            Log::error($e);
            return null;
        }

        return $newUser;
    }
}
