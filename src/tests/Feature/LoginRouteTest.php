<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Tests\TestCase;

class LoginRouteTest extends TestCase
{

    public function test_authorized_user_can_browse_login(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/login');

        $response->assertRedirectToRoute('home');
    }

    public function test_unauthorized_user_can_browse_login(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
