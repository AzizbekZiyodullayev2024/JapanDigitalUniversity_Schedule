<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{

    public function it_can_register_a_new_user()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'data' => ['user', 'token']
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }
    public function it_can_login_a_user()
{
    $user = User::factory()->create([
        'password' => bcrypt('password')
    ]);

    $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password'
    ]);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'token',
                 'user' => ['id', 'name', 'email']
             ]);
}
public function it_returns_error_when_login_credentials_are_invalid()
{
    $response = $this->postJson('/api/login', [
        'email' => 'nonexistent@example.com',
        'password' => 'wrongpassword'
    ]);

    $response->assertStatus(401)
             ->assertJson([
                 'message' => 'The provided credentials are incorrect'
             ]);
}
}
