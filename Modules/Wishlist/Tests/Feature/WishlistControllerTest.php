<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Product\Entities\Product;
use Modules\User\Entities\User;
use Modules\Wishlist\Entities\Wishlist;
use Tests\TestCase;
use Modules\Auth\Database\Factories\UserFactory;

class WishlistControllerTest extends TestCase
{
    use DatabaseTransactions; // Use this to run tests within a database transaction
    private $user;
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = UserFactory::new()->create();

        // You may set up any necessary data for your tests here.
    }

    /**
     * Test creating a new wishlist.
     */
    public function testCreateWishlist()
    {
        // Create a product for testing
        $product = Product::factory()->create();

        // Send a POST request to the 'store' endpoint with valid data
        $response = $this->actingAs($this->user)
            ->json('POST', '/api/v1/wishlist/create' . $product->id);

        // Assert that the response status is HTTP 201 (Created)
        $response->assertStatus(201);

        // You can add more assertions to check the response data, database state, etc.
    }

    /**
     * Test deleting a wishlist item.
     */
    public function testDeleteWishlist()
    {
        // Create a user for testing
        $user = User::factory()->create();

        // Create a product for testing
        $product = Product::factory()->create();

        // Create a wishlist item for the user
        $wishlistItem = Wishlist::factory()->create([
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // Send a DELETE request to the 'delete' endpoint
        $response = $this->actingAs($user)
            ->json('DELETE', '/api/v1/wishlist/delete' . $product->id);

        // Assert that the response status is HTTP 204 (No Content)
        $response->assertStatus(204);

        // Ensure the wishlist item is deleted from the database
        $this->assertDatabaseMissing('wishlist', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    // You can add more test methods for other controller actions here.
}
