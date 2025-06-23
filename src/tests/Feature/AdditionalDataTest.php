<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdditionalDataTest extends TestCase
{
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
}
