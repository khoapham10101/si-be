<?php

namespace Modules\Cart\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Brand\Database\Factories\BrandFactory;
use Modules\Permission\Database\Factories\PermissionFactory;
use Modules\Product\Database\Factories\ProductFactory;
use Modules\Role\Database\Factories\RoleFactory;
use Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

class CartControllerTest extends TestCase
{
    use RefreshDatabase;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
    }

    public function testUserCanGetListCarts()
    {
        $this->actingAs($this->user);

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Create product
        $product = ProductFactory::new()->create([
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);

        // Add to cart
        $response = $this->postJson('/api/v1/carts/create', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);

        // Get list cart
        $response = $this->postJson('/api/v1/carts');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'user_id',
                    'product_id',
                    'product',
                    'quantity'
                ]
            ],
        ]);
    }

    public function testUserCanAddProductToCart()
    {
        $this->actingAs($this->user);

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Create product
        $product = ProductFactory::new()->create([
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);

        // Add to cart
        $response = $this->postJson('/api/v1/carts/create', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'product' => [],
            'quantity' => 2
        ]);
    }

    public function testUserCannotAddProductToCartWithoutValidatePass()
    {
        $this->actingAs($this->user);

        // Add to cart
        $response = $this->postJson('/api/v1/carts/create', [
            'product_id' => 123456,
            'quantity' => 2
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('product_id');
    }

    public function testUserCanUpdateMultipleCarts()
    {
        $this->actingAs($this->user);

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Create product
        $product1 = ProductFactory::new()->create([
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);

        $product2 = ProductFactory::new()->create([
            'name' => 'Product 2',
            'brand_id' => $brand->id,
            'sku' => '1234MVC1',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);

        // Add to cart 1
        $cart1 = $this->postJson('/api/v1/carts/create', [
            'product_id' => $product1->id,
            'quantity' => 2
        ]);
        $cart1->assertStatus(201);
        // Get the ID of the newly created cart
        $newCaryId1 = $cart1->json('id');

        // Add to cart 2
        $cart2 = $this->postJson('/api/v1/carts/create', [
            'product_id' => $product2->id,
            'quantity' => 2
        ]);
        $cart2->assertStatus(201);
        // Get the ID of the newly created cart
        $newCaryId2 = $cart2->json('id');

        $cartItems = [
            [
                'cart_id' => $newCaryId1,
                'quantity' => 8
            ],
            [
                'cart_id' => $newCaryId2,
                'quantity' => 5
            ]
        ];

        // Update multiple
        $response = $this->putJson('/api/v1/carts/update-mutiple', [
            'cart_items' => $cartItems
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Updated My Cart Successfully.'
        ]);
    }

    public function testUserCanUpdateMultipleCartsWhenCartDoesNotExist()
    {
        $this->actingAs($this->user);

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Create product
        $product1 = ProductFactory::new()->create([
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);

        $product2 = ProductFactory::new()->create([
            'name' => 'Product 2',
            'brand_id' => $brand->id,
            'sku' => '1234MVC1',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);

        // Add to cart 1
        $cart1 = $this->postJson('/api/v1/carts/create', [
            'product_id' => $product1->id,
            'quantity' => 2
        ]);
        $cart1->assertStatus(201);
        // Get the ID of the newly created cart
        $newCaryId1 = $cart1->json('id');

        // Add to cart 2
        $cart2 = $this->postJson('/api/v1/carts/create', [
            'product_id' => $product2->id,
            'quantity' => 2
        ]);
        $cart2->assertStatus(201);
        // Get the ID of the newly created cart
        $newCaryId2 = $cart2->json('id');

        $cartItems = [
            [
                'cart_id' => $newCaryId1,
                'quantity' => 8
            ],
            [
                'cart_id' => 111111,
                'quantity' => 5
            ]
        ];

        // Update multiple
        $response = $this->putJson('/api/v1/carts/update-mutiple', [
            'cart_items' => $cartItems
        ]);
        $response->assertStatus(422)
        ->assertJson([
            'message' => 'The selected cart_items.1.cart_id is invalid.',
            'errors' => [
                'cart_items.1.cart_id' => [
                    'The selected cart_items.1.cart_id is invalid.'
                ]
            ]
        ]);
    }

    public function testUserCanDeleteCart()
    {
        $this->actingAs($this->user);

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Create product
        $product = ProductFactory::new()->create([
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);

        // Add to cart
        $response = $this->postJson('/api/v1/carts/create', [
            'product_id' => $product->id,
            'quantity' => 2
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'product' => [],
            'quantity' => 2
        ]);

        // Get the ID of the newly created cart
        $newCartId = $response->json('id');

        // Remove cart
        $response = $this->delete("/api/v1/carts/delete/{$newCartId}");
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Deleted cart item successfully.'
        ]);
    }

    public function tearDown(): void
    {
        $this->user->tokens()->delete();
        parent::tearDown();
    }
}
