<?php

namespace Modules\Wishlist\Tests;

use Tests\TestCase;
use Modules\Auth\Database\Factories\UserFactory;
use Modules\Product\Database\Factories\ProductFactory;

class WishlistControllerTest extends TestCase
{
    private $user;
    private $product;
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
        $this->product = ProductFactory::new()->create();
    }

    /**
     * Test list wishlist
     */
    public function test_list_wishlist()
    {
        // Login
        $response = $this->post('/api/v1/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        // Create wishlist
        $response = $this->postJson("/api/v1/wishlist/create/{$this->product->id}");
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'user' => [],
            'product_id',
            'product' => []
        ]);

        $listWishlist = $this->post('/api/v1/wishlist');
        $listWishlist->assertStatus(200);
        $listWishlist->assertJson([
            'data' => [
                [
                    'user_id' => $this->user->id,
                    'product_id' => $this->product->id,
                ]
            ]
        ]);

        // Logout
        $this->post('/api/v1/logout');
    }

    /**
     * Test creating a new wishlist.
     */
    public function test_create_wishlist()
    {
        // Login
        $response = $this->post('/api/v1/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        // Create wishlist
        $response = $this->postJson("/api/v1/wishlist/create/{$this->product->id}");
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'user' => [],
            'product_id',
            'product' => []
        ]);

        // Logout
        $this->post('/api/v1/logout');
    }

    public function test_remove_wishlist()
    {
        // Login
        $response = $this->post('/api/v1/login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]);

        // Create wishlist
        $response = $this->postJson("/api/v1/wishlist/create/{$this->product->id}");

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'id',
            'user_id',
            'user' => [],
            'product_id',
            'product' => []
        ]);

        // Remove wishlist
        $remove = $this->delete("/api/v1/wishlist/delete/{$this->product->id}");
        $remove->assertStatus(204);

        // Logout
        $this->post('/api/v1/logout');
    }

}
