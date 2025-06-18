<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    /** 
     *  logout test
     */
    public function test_authorized_user_logout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->withSession(['testLogout' => 'testLogout-value'])->post(route('logout'));

        $response->assertSessionMissing('test');
    }

    public function test_unauthorized_user_logout_redirect_to_login(): void
    {
        $response = $this->post(route('logout'));

        $response->assertRedirectToRoute('login');
    }
}
