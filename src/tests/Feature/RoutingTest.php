<?php

namespace Tests\Feature;

use App\Models\Context;
use App\Models\User;
use App\Services\ContextService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoutingTest extends TestCase
{
    use RefreshDatabase;

    /** 
    *   test missing route
    */
    public function test_browse_missing_route(): void
    {
        $response = $this->get("/profile" . fake()->word());
        $response->assertNotFound();
    }

    /** 
    *   test home route
    */
    public function test_authorized_user_can_browse_home(): void
    {
        $user = User::factory()->create();
        $rootContext = Context::create(
            [
                'is_root' => true,
                'user_id' => $user->id
            ],
        );

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
    }

    public function test_unauthorized_user_redirect_to_welcome_when_browse_home(): void
    {
        $response = $this->get('/');

        $response->assertRedirectToRoute('welcome');
    }

    /** 
    *   test login route
    */
    public function test_authorized_user_redirect_to_home_when_browse_login(): void
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

    /** 
    *   test logout route
    */
    public function test_authorized_user_can_browse_logout(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirectToRoute('welcome');
    }

    public function test_unauthorized_user_redirect_to_login_when_browse_logout(): void
    {
        $response = $this->post('/logout');

        $response->assertRedirectToRoute('login');
    }


    /** 
    *   test register route
    */
    public function test_authorized_user_redirect_to_home_when_browse_register(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('registration.index'));

        $response->assertRedirectToRoute('home');
    }

    public function test_unauthorized_user_can_browse_register(): void
    {
        $response = $this->get(route('registration.index'));

        $response->assertStatus(200);
    }

    /** 
    *   test profile route
    */
    public function test_unauthorized_user_redirect_to_login_when_browse_profile(): void
    {
        $response = $this->get(route('profile'));
        $response->assertRedirectToRoute('login');
    }

    public function test_authorized_user_can_browse_profile(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('profile'));
        $response->assertStatus(200);
    }
}
