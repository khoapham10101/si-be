<?php

namespace Modules\Brand\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

class BrandControllerTest extends TestCase
{
    use RefreshDatabase;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
    }

    public function test_user_can_get_list_brands()
    {
        $response = $this->actingAs($this->user);

        // Add brand1
        $response = $this->postJson('/api/v1/brands/create', [
            'name' => 'My brand 1'
        ]);

        // Add brand2
        $response = $this->postJson('/api/v1/brands/create', [
            'name' => 'My brand 2'
        ]);

        // Get list brands
        $response = $this->post('/api/v1/brands');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'name' => 'My brand 1'
                ],
                [
                    'name' => 'My brand 2'
                ]
            ]
        ]);
    }

    public function test_user_can_add_brand()
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/brands/create', [
            'name' => 'My brand'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'My brand']);
    }

    public function test_user_cannot_add_brand_without_validate_pass()
    {
        $response = $this->actingAs($this->user)->postJson('/api/v1/brands/create', [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_get_dropdown_brand()
    {
        $response = $this->actingAs($this->user);

        // Add brand1
        $response = $this->postJson('/api/v1/brands/create', [
            'name' => 'My brand 1'
        ]);

        // Add brand2
        $response = $this->postJson('/api/v1/brands/create', [
            'name' => 'My brand 2'
        ]);

        // Get dropdown
        $response = $this->postJson('/api/v1/brands/dropdown', [
            'filters' => [
                'name' => ''
            ]
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'name' => 'My brand 1'
                ],
                [
                    'name' => 'My brand 2'
                ]
            ]
        ]);
    }

    public function test_update_a_brand()
    {
        $response = $this->actingAs($this->user);

        // Add new brand
        $response = $this->postJson('/api/v1/brands/create', [
            'name' => 'My brand'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'My brand']);

        // Get the ID of the newly created brand
        $newBrandId = $response->json('id');
        // Edit brand
        $response = $this->patchJson("/api/v1/brands/update/{$newBrandId}", [
            'name' => 'My brand edit'
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'My brand edit']);
    }

    public function test_user_can_delete_brand()
    {
        $response = $this->actingAs($this->user);

        // Add new brand
        $response = $this->postJson('/api/v1/brands/create', [
            'name' => 'My brand'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'My brand']);

        // Get the ID of the newly created brand
        $newBrandId = $response->json('id');
        // Remove brand
        $remove = $this->delete("/api/v1/brands/delete/{$newBrandId}");
        $remove->assertStatus(204);
    }

    public function test_user_cannot_delete_brand_when_does_not_exist()
    {
        $response = $this->actingAs($this->user);

        // Add new brand
        $response = $this->postJson('/api/v1/brands/create', [
            'name' => 'My brand'
        ]);

        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'My brand']);

        // Get the ID of the newly created brand
        $newBrandId = 123456789;
        // Remove brand
        $remove = $this->delete("/api/v1/brands/delete/{$newBrandId}");
        $remove->assertStatus(404);
        $remove->assertJson([
            'success' => false,
            'message' => 'Brand 123456789 not found'
        ]);
    }

    public function tearDown(): void
    {
        $this->user->tokens()->delete();
        parent::tearDown();
    }
}
