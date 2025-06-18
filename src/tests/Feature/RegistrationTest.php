<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegistrationTest extends TestCase
{

    use RefreshDatabase;
    /**
     * registration test
     */
    public function test_registration_success_with_correct_data(): void
    {
        $userData = [
            'email' => 'somemail@mail.com',
            'name' => 'someuser',
            'password' => 'qwertyQ1!',
            'password_confirmation' => 'qwertyQ1!',
        ];

        $response = $this->post(route('registration.store'), $userData);

        $this->assertDatabaseHas('users', [
            'email' => $userData['email']
        ]);

        $response->assertRedirectToRoute('home');
    }

    public function test_registration_fail_with_taken_email(): void
    {
        $user = User::factory()->create();
        $userData = [
            'email' => $user->email,
            'name' => 'someuser',
            'password' => 'qwertyQ1!',
            'password_confirmation' => 'qwertyQ1!',
        ];

        $response = $this->post(route('registration.store'), $userData);
        $response->assertSessionHasErrors(['email']);
        $user->delete();
        $this->assertDatabaseMissing('users', ['email' => $userData['email']]);
    }

    public function test_registration_fail_with_incorrects_email(): void
    {
        $userData = [
            'email' => 'somemail@@mail',
            'name' => 'someuser',
            'password' => 'qwertyQ1!',
            'password_confirmation' => 'qwertyQ1!',
        ];

        $response = $this->post(route('registration.store'), $userData);

        $response->assertSessionHasErrors(['email']);

        $this->assertDatabaseMissing('users', [
            'email' => $userData['email']
        ]);
    }

    public function test_registration_fail_with_incorrects_password(): void
    {
        $userData = [
            'email' => 'somemail@mail.com',
            'name' => 'someuser',
            'password' => 'qwerty',
            'password_confirmation' => 'qwerty',
        ];

        $response = $this->post(route('registration.store'), $userData);

        $response->assertSessionHasErrors(['password']);

        $this->assertDatabaseMissing('users', [
            'email' => $userData['email']
        ]);
    }

    public function test_registration_fail_with_not_confirm_password(): void
    {
        $userData = [
            'email' => 'somemail@mail.com',
            'name' => 'someuser',
            'password' => 'qwertyQ1!',
            'password_confirmation' => 'qwertyQ1',
        ];

        $response = $this->post(route('registration.store'), $userData);

        $response->assertSessionHasErrors(['password']);

        $this->assertDatabaseMissing('users', [
            'email' => $userData['email']
        ]);
    }
}
