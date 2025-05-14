<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Tests\TestCase;

class LogoutRouteTest extends TestCase
{

    public function test_authorized_user_can_browse_logout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirectToRoute('welcome');
    }

    public function test_unauthorized_user_can_not_browse_logout(): void
    {
        $response = $this->post('/logout');

        $response->assertRedirectToRoute('home');
    }
}
