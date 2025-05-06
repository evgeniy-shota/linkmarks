<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRouteTest extends TestCase
{

    public function test_authorized_user_can_browse_login(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_unauthorized_user_can_browse_login(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }
}
