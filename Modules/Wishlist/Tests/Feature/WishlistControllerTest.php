<?php

namespace Modules\Wishlist\Tests\Feature;

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

    public function testUserCanGetListWishlist()
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

    public function testUserCanAddWishlist()
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
    }

    public function testUserCannotAddAProductToWishlistWhenHaveAdded()
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

        // Create wishlist again with the same product1 id
        $response = $this->actingAs($this->user)->postJson("/api/v1/wishlist/create/{$this->product1->id}");
        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'message' => 'Product already in your wishlist'
        ]);
    }

    public function testUserCanRemoveWishlist()
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
