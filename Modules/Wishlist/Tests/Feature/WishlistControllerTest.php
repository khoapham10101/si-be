<?php

namespace Modules\Wishlist\Tests;

use Modules\Product\Database\Factories\ProductFactory;
use Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\Wishlist\Entities\Wishlist;

class WishlistControllerTest extends TestCase
{
    private $user;
    private $product1;
    private $product2;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->product1 = ProductFactory::new()->create();
        $this->product2 = ProductFactory::new()->create();
    }

    public function test_user_can_get_list_wishlist()
    {
        // Wishlist1
        $response = $this->actingAs($this->user)->postJson("/api/v1/wishlist/create/{$this->product1->id}");
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'user' => [],
            'product_id',
            'product' => []
        ]);

        // Wishlist2
        $response = $this->actingAs($this->user)->postJson("/api/v1/wishlist/create/{$this->product2->id}");
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'user' => [],
            'product_id',
            'product' => []
        ]);

        // Get wishlist
        $response = $this->actingAs($this->user)->post('/api/v1/wishlist');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'user_id' => $this->user->id,
                    'product_id' => $this->product1->id,
                ],
                [
                    'user_id' => $this->user->id,
                    'product_id' => $this->product2->id,
                ]
            ]
        ]);
    }

    public function test_user_can_add_wishlist()
    {
        $response = $this->actingAs($this->user)->postJson("/api/v1/wishlist/create/{$this->product1->id}");
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'user' => [],
            'product_id',
            'product' => []
        ]);
    }

    public function test_user_can_remove_wishlist()
    {
        // Create wishlist
        $response = $this->actingAs($this->user)->postJson("/api/v1/wishlist/create/{$this->product1->id}");
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'user' => [],
            'product_id',
            'product' => []
        ]);

        // Remove wishlist
        $remove = $this->delete("/api/v1/wishlist/delete/{$this->product1->id}");
        $remove->assertStatus(204);
    }

    public function tearDown(): void
    {
        $this->user->tokens()->delete();
        parent::tearDown();
    }
}
