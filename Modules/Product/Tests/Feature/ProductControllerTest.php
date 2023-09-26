<?php

namespace Modules\Product\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Brand\Database\Factories\BrandFactory;
use Modules\Permission\Database\Factories\PermissionFactory;
use Modules\Role\Database\Factories\RoleFactory;
use Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;
    private $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = UserFactory::new()->create();
    }

    protected function setUpCommonData()
    {
        // Create a new role
        $role = RoleFactory::new()->create();

        // Assign role to user
        $this->user->roles()->attach([$role->id]);
        $this->assertTrue($this->user->roles->contains($role));

        // Create permissions
        $permissionList = PermissionFactory::new()->create([
            'name' => 'List Products',
            'action' => 'admin.products.index',
            'module_name' => 'Products'
        ]);

        $permissionCreate = PermissionFactory::new()->create([
            'name' => 'Create Product',
            'action' => 'admin.products.create',
            'module_name' => 'Products'
        ]);

        $permissionUpdate = PermissionFactory::new()->create([
            'name' => 'Update Product',
            'action' => 'admin.products.edit',
            'module_name' => 'Products'
        ]);

        $permissionDelete = PermissionFactory::new()->create([
            'name' => 'Delete Product',
            'action' => 'admin.products.destroy',
            'module_name' => 'Products'
        ]);

        // Assign role to permissions
        $role->permissions()->attach([
            $permissionList->id,
            $permissionCreate->id,
            $permissionUpdate->id,
            $permissionDelete->id
        ]);
    }

    public function testUserCanGetListProducts()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Add product
        $response = $this->postJson('/api/v1/products/create', [
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);

        $response = $this->post('/api/v1/products');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'brand_id',
                    'brand' => []
                ]
            ],
        ]);
    }

    public function testUserCannotGetListProductsWithoutPermission()
    {
        $this->actingAs($this->user);

        $response = $this->post('/api/v1/products');
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'code' => 403,
            'message' => 'Forbidden'
        ]);
    }

    public function testUserCanAddProduct()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Add new product
        $response = $this->postJson('/api/v1/products/create', [
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Product 1']);
    }

    public function testUserCannotAddProductWithoutValidatePass()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Add new product
        $response = $this->postJson('/api/v1/products/create', [
            'name' => '',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function testUserCannotAddProductWhenTheSkuAlreadyExits()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Add new product
        $response = $this->postJson('/api/v1/products/create', [
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Product 1']);

        // Add new product again with the same sku
        $response = $this->postJson('/api/v1/products/create', [
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('sku');
    }

    public function testUserCannotAddProductWithoutPermission()
    {
        $this->actingAs($this->user);

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Add new product
        $response = $this->postJson('/api/v1/products/create', [
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'code' => 403,
            'message' => 'Forbidden'
        ]);
    }

    public function testUserCanUpdateProduct()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Add new product
        $response = $this->postJson('/api/v1/products/create', [
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Product 1']);

        // Get the ID of the newly created product
        $newProductId = $response->json('id');
        // Edit product
        $response = $this->postJson("/api/v1/products/update/{$newProductId}", [
            'name' => 'Product 1 edit',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Product 1 edit']);
    }

    public function testUserCanDeleteProduct()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Add new product
        $response = $this->postJson('/api/v1/products/create', [
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Product 1']);

        // Get the ID of the newly created product
        $newProductId = $response->json('id');
        // Remove product
        $remove = $this->delete("/api/v1/products/delete/{$newProductId}");
        $remove->assertStatus(204);
    }

    public function testUserCannotDeleteProductWhenDoesNotExist()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Craete brand
        $brand = BrandFactory::new()->create([
            'name' => 'Brand 1'
        ]);

        // Add new product
        $response = $this->postJson('/api/v1/products/create', [
            'name' => 'Product 1',
            'brand_id' => $brand->id,
            'sku' => '1234MVC',
            'description' => '',
            'warranty_information' => '',
            'quantity' => '123',
            'price' => '123'
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'Product 1']);
        // Get the ID of the newly created product
        $newProductId = 123456789;
        // Remove product
        $remove = $this->delete("/api/v1/products/delete/{$newProductId}");
        $remove->assertStatus(404);
        $remove->assertJson([
            'success' => false,
            'message' => 'Product 123456789 not found'
        ]);
    }

    public function tearDown(): void
    {
        $this->user->tokens()->delete();
        parent::tearDown();
    }
}
