<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $this->postJson(route('auth.register'), [
            'name' => 'Raihanhori',
            'email' => 'raihan@gmail.com',
            'password' => 'password'
        ])->assertCreated()->json();

        $this->assertDatabaseHas('users', ['name' => 'Raihanhori', 'email' => 'raihan@gmail.com']);
    }

    public function test_user_can_login(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'password'
        ])->assertOk()->json();

        $this->assertArrayHasKey('token', $response);
    }

    public function test_user_cannot_get_token_if_email_wrong(): void
    {
        $response = $this->postJson(route('auth.login'), [
            'email' => fake()->email,
            'password' => 'passwrd'
        ])->assertBadRequest()->json();
    }

    public function test_user_cannot_get_token_if_password_wrong(): void
    {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $this->postJson(route('auth.login'), [
            'email' => $user->email,
            'password' => 'random'
        ])->assertBadRequest()->json();
    }
}
