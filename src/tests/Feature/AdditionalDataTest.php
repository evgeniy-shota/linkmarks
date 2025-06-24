<?php

namespace Tests\Feature;

use App\Models\Thumbnail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdditionalDataTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_get_all_user_contexts_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $numberContexts = random_int(10, 20);
        $contexts = $this->createContexts(
            $dependencies['user']->id,
            $dependencies['rootContext']->id,
            $numberContexts,
        );
        $response = $this->actingAs($dependencies['user'])
            ->getJson(route('additionalData.contexts'));

        // $numberContexts + 1 (root context)
        $response->assertStatus(200)->assertJsonCount($numberContexts + 1, 'data');
    }

    public function test_get_associated_thumbnails_success(): void
    {
        $user = $this->createUser();
        $user2 = $this->createUser();
        $numberThumbnails = random_int(10, 20);
        $url = 'https://google.com';
        $this->createThumbnails(
            $user->id,
            'google.com',
            $numberThumbnails,
        );
        $this->createThumbnails(
            $user->id,
            'duckduckgo.com',
            $numberThumbnails,
        );
        $this->createThumbnails(
            $user2->id,
            'google.com',
            $numberThumbnails,
        );
        $this->createThumbnails(
            null,
            'google.com',
            2,
        );
        $response = $this->actingAs($user)
            ->getJson(route('additionalData.thumbnails', "url=$url"));

        $response->assertStatus(200);
        $response->assertJsonCount(
            Thumbnail::where('user_id', $user->id)
                ->where('associations', 'google.com')->count(),
            'data'
        );
    }

    public function test_bookmark_form_autocomplete_success(): void
    {
        $user = $this->createUser();
        $url = 'https://duckduckgo.com';
        $response = $this->actingAs($user)
            ->get(route('additionalData.autocomplete', "url=$url"));

        $response->assertSuccessful()->assertJson([
            'data' => [],
        ]);
    }
}
