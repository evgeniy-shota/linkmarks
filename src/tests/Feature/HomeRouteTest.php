<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeRouteTest extends TestCase
{

    public function test_authorized_user_can_browse_home(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_unauthorized_user_redirect_to_welcome_when_browse_home(): void
    {
        $response = $this->get('/');

        $response->assertRedirectToRoute('welcome');
    }

    public function test_authorized_user_see_addBookmark_button_home(): void
    {
        $response = $this->get('/');

        $response->assertRedirectToRoute('welcome');
    }
}
