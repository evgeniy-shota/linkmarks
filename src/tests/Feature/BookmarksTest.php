<?php

namespace Tests\Feature;

use App\Models\Context;
use App\Models\Thumbnail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookmarksTest extends TestCase
{
    use RefreshDatabase;

    private function prepareDependencies(): array
    {
        $user = User::factory()->create();
        $rootContext = Context::create([
            'is_root' => true,
            'user_id' => $user->id,
        ]);
        $thumbnail = Thumbnail::factory()->create();

        return [
            'user' => $user,
            'rootContext' => $rootContext,
            'thumbnail' => $thumbnail,
        ];
    }

    /**
     * bookmarks test
     */
    public function test_create_bookmark_with_correct_data_success(): void
    {
        $dependencies = $this->prepareDependencies();

        $bookmarkData = [
            'context_id' => $dependencies['rootContext']->id,
            'link' => fake()->url(),
            'name' => fake()->word(),
            'thumbnail_id' => $dependencies['thumbnail']->id,
        ];
        $response = $this->actingAs($dependencies['user'])->post(
            route('bookmarks.store'),
            $bookmarkData
        );

        $response->assertCreated();

        $this->assertDatabaseHas('bookmarks', [
            'link' => $bookmarkData['link'],
            'name' => $bookmarkData['name'],
            'context_id' => $bookmarkData['context_id'],
        ]);
    }

    public function test_create_bookmark_with_incorrect_data_fail(): void
    {
        $dependencies = $this->prepareDependencies();

        $bookmarkData = [
            'context_id' => $dependencies['rootContext']->id,
            'link' => 'somelink..online',
            'name' => fake()->word(),
            'thumbnail_id' => $dependencies['thumbnail']->id,
        ];
        $response = $this->actingAs($dependencies['user'])->post(
            route('bookmarks.store'),
            $bookmarkData
        );

        $response->assertSessionHasErrors(['link']);

        $this->assertDatabaseMissing('bookmarks', [
            'link' => $bookmarkData['link'],
            'name' => $bookmarkData['name'],
            'context_id' => $bookmarkData['context_id'],
        ]);
    }
}
