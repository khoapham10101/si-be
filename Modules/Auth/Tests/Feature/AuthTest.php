<?php

namespace Modules\Auth\Tests\Feature;

use Tests\TestCase;
use Modules\Auth\Database\Factories\UserFactory;

/**
 * Tests for AuthController.
 * php artisan test Modules/Auth/Tests/Feature/AuthTest.php
 */
class AuthTest extends TestCase
{
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
    }

    public function testUserCanLoginWithValidCredentials()
    {
        $response = $this->post('/api/v1/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'access_token',
                'user',
            ],
        ]);
    }

    public function testUserCannotLoginWithInvalidCredentials()
    {
        $response = $this->post('/api/v1/login', [
            'email' => $this->user->email,
            'password' => 'invalidpassword',
        ]);

        $response->assertStatus(401);
    }

    public function testUserCanLogout()
    {
        $token = $this->user->createToken('testToken')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")->post('/api/v1/logout');

        $response->assertStatus(200);
        $response->assertJson(['message' => 'OK']);
    }

    public function tearDown(): void
    {
        // Clear user tokens
        $this->user->tokens()->delete();

        parent::tearDown();
    }
}
