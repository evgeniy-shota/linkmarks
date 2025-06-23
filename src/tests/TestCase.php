<?php

namespace Tests;

use App\Models\Bookmark;
use App\Models\Context;
use App\Models\Profile;
use App\Models\Tag;
use App\Models\Thumbnail;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Collection;

abstract class TestCase extends BaseTestCase
{
    protected function prepareDependencies(): array
    {
        $user = $this->createUser();
        $rootContext = $this->createRootContext($user->id);
        $thumbnails = $this->createThumbnails();

        return [
            'user' => $user,
            'rootContext' => $rootContext,
            'thumbnails' => $thumbnails,
        ];
    }

    protected function createUser(bool $createProfile = false): User
    {
        $user = User::factory()->create();

        if ($createProfile) {
            Profile::create([
                'user_id' => $user->id,
            ]);
        }
        return $user;
    }

    protected function createUsers(int $number = 2): Collection
    {
        return User::factory()->count($number)->create();
    }

    protected function createThumbnails(int $number = 10): Collection
    {
        return Thumbnail::factory()->count($number)->create();
    }

    protected function createRootContext(int $userId): Context
    {
        return Context::create([
            'is_root' => true,
            'user_id' => $userId,
            'name' => 'Root'
        ]);
    }

    protected function createContexts(
        int $userId,
        int $parentContextId,
        int $number = 5
    ): Collection {
        return Context::factory()->count($number)->create([
            'user_id' => $userId,
            'parent_context_id' => $parentContextId,
        ]);
    }

    protected function createBookmarks(
        int $userId,
        int $contextId,
        int $number = 5
    ): Collection {
        return Bookmark::factory()->count($number)->create([
            'user_id' => $userId,
            'context_id' => $contextId,
        ]);
    }

    protected function createTags(int $userId, int $number = 5): Collection
    {
        return Tag::factory()->count($number)->create([
            'user_id' => $userId,
        ]);
    }
}
