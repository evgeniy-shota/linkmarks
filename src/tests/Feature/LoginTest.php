<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_login_succees_with_correct_data(): void
    {
        $user = User::factory()->create();
        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => $user->password,
        ]);

        $response->assertRedirectToRoute('home');
    }

    public function test_login_fail_with_missing_email(): void
    {
        $user = User::factory()->create();
        $response = $this->post(route('login.store'), [
            'email' => 'missing' . $user->email,
            'password' => $user->password,
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_fail_with_incorrect_password(): void
    {
        $user = User::factory()->create();
        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => '1234' . $user->password,
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }
}
