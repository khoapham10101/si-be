<?php

namespace Modules\UserStatus\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Role\Database\Factories\RoleFactory;
use Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\UserStatus\Database\Factories\UserStatusPermissionFactory;

class UserStatusControllerTest extends TestCase
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

        // Gán role cho user
        $this->user->roles()->attach([$role->id]);
        $this->assertTrue($this->user->roles->contains($role));

        // Tạo các permissions
        $permissionList = UserStatusPermissionFactory::new()->create([
            'name' => 'List User Statuses',
            'action' => 'admin.user_statuses.index',
            'module_name' => 'User Statuses'
        ]);

        $permissionCreate = UserStatusPermissionFactory::new()->create([
            'name' => 'Create User Statuses',
            'action' => 'admin.user_statuses.create',
            'module_name' => 'User Statuses'
        ]);

        $permissionUpdate = UserStatusPermissionFactory::new()->create([
            'name' => 'Update User Statuses',
            'action' => 'admin.user_statuses.edit',
            'module_name' => 'User Statuses'
        ]);

        $permissionDelete = UserStatusPermissionFactory::new()->create([
            'name' => 'Delete User Statuses',
            'action' => 'admin.user_statuses.destroy',
            'module_name' => 'User Statuses'
        ]);

        // Gán role cho permissions
        $role->permissions()->attach([
            $permissionList->id,
            $permissionCreate->id,
            $permissionUpdate->id,
            $permissionDelete->id
        ]);
    }

    public function test_user_can_get_list_user_status()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add user status 1
        $response = $this->postJson('/api/v1/userStatus/create', [
            'name' => 'User Status 1'
        ]);
        // Add user status 2
        $response = $this->postJson('/api/v1/userStatus/create', [
            'name' => 'User Status 2'
        ]);

        $response = $this->post('/api/v1/userStatus');
        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                [
                    'name' => 'User Status 1'
                ],
                [
                    'name' => 'User Status 2'
                ]
            ]
        ]);
    }

    public function test_user_cannot_get_list_user_status_without_permission()
    {
        $this->actingAs($this->user);

        $response = $this->post('/api/v1/userStatus');
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'code' => 403,
            'message' => 'Forbidden'
        ]);
    }

    public function test_user_can_add_user_status()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user status
        $response = $this->postJson('/api/v1/userStatus/create', [
            'name' => 'My user status'
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'My user status']);
    }

    public function test_user_cannot_add_user_status_without_validate_pass()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user status
        $response = $this->actingAs($this->user)->postJson('/api/v1/userStatus/create', [
            'name' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_user_cannot_add_user_status_without_permission()
    {
        $this->actingAs($this->user);

        // Add new user status
        $response = $this->postJson('/api/v1/userStatus/create', [
            'name' => 'My User Status'
        ]);
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'code' => 403,
            'message' => 'Forbidden'
        ]);
    }

    public function test_user_can_update_user_status()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user status
        $response = $this->postJson('/api/v1/userStatus/create', [
            'name' => 'My user status'
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'My user status']);

        // Get the ID of the newly created user status
        $newUserStatusId = $response->json('id');
        // Edit user status
        $response = $this->patchJson("/api/v1/userStatus/update/{$newUserStatusId}", [
            'name' => 'My user status edit'
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'My user status edit']);
    }

    public function test_user_can_delete_user_status()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user status
        $response = $this->postJson('/api/v1/userStatus/create', [
            'name' => 'My user status'
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'My user status']);

        // Get the ID of the newly created user status
        $newUserStatusId = $response->json('id');
        // Remove user status
        $remove = $this->delete("/api/v1/userStatus/delete/{$newUserStatusId}");
        $remove->assertStatus(204);
    }

    public function test_user_cannot_delete_user_status_when_does_not_exist()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user status
        $response = $this->postJson('/api/v1/userStatus/create', [
            'name' => 'My user status'
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['name' => 'My user status']);

        // Get the ID of the newly created user status
        $newUserStatusId = 123456789;
        // Remove user status
        $remove = $this->delete("/api/v1/userStatus/delete/{$newUserStatusId}");
        $remove->assertStatus(404);
        $remove->assertJson([
            'success' => false,
            'message' => 'User Status 123456789 not found'
        ]);
    }

    public function tearDown(): void
    {
        $this->user->tokens()->delete();
        parent::tearDown();
    }
}
