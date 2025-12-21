<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register_via_jwt()
    {
        // Arrange
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        // Act
        $response = $this->postJson('/api/auth/jwt/register', $userData);

        // Assert
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'user',
                'token'
            ]
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'role' => 'user'
        ]);
    }

    /** @test */
    public function user_can_login_via_jwt()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Act
        $response = $this->postJson('/api/auth/jwt/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'user',
                'token',
                'token_type'
            ]
        ]);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Act
        $response = $this->postJson('/api/auth/jwt/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert
        $response->assertStatus(401);
        $response->assertJsonPath('success', false);
    }

    /** @test */
    public function authenticated_user_can_get_profile()
    {
        // Arrange
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        // Act
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->getJson('/api/auth/me');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('data.user.email', $user->email);
    }

    /** @test */
    public function user_can_refresh_token()
    {
        // Arrange
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        // Act
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson('/api/auth/refresh');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                'token',
                'token_type'
            ]
        ]);
    }

    /** @test */
    public function user_can_logout()
    {
        // Arrange
        $user = User::factory()->create();
        $token = auth('api')->login($user);

        // Act
        $response = $this->withHeaders([
            'Authorization' => "Bearer {$token}"
        ])->postJson('/api/auth/logout');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
    }

    /** @test */
    public function inactive_user_cannot_login()
    {
        // Arrange
        $user = User::factory()->create([
            'email' => 'inactive@example.com',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        // Act
        $response = $this->postJson('/api/auth/jwt/login', [
            'email' => 'inactive@example.com',
            'password' => 'password123',
        ]);

        // Assert
        $response->assertStatus(403);
        $response->assertJsonPath('success', false);
    }

    /** @test */
    public function registration_validates_password_confirmation()
    {
        // Act
        $response = $this->postJson('/api/auth/jwt/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
        ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['password']);
    }

    /** @test */
    public function registration_validates_unique_email()
    {
        // Arrange
        User::factory()->create(['email' => 'existing@example.com']);

        // Act
        $response = $this->postJson('/api/auth/jwt/register', [
            'name' => 'Test User',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Assert
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['email']);
    }
}
