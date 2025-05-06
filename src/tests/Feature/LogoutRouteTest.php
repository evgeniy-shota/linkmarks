<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRouteTest extends TestCase
{

    public function test_authorized_user_can_browse_logout(): void
    {
        $response = $this->post('/logout');

        $response->assertStatus(200);
    }

    public function test_unauthorized_user_can_not_browse_logout(): void
    {
        $response = $this->post('/logout');

        $response->assertRedirectToRoute('home');
    }
}
