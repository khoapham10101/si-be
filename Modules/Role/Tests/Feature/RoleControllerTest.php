<?php

namespace Modules\Role\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Permission\Database\Factories\PermissionFactory;
use Modules\Role\Database\Factories\RoleFactory;
use Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

class RoleControllerTest extends TestCase
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
            'name' => 'List Roles',
            'action' => 'admin.roles.index',
            'module_name' => 'Roles'
        ]);

        $permissionCreate = PermissionFactory::new()->create([
            'name' => 'Create Role',
            'action' => 'admin.roles.create',
            'module_name' => 'Roles'
        ]);

        $permissionUpdate = PermissionFactory::new()->create([
            'name' => 'Update Role',
            'action' => 'admin.roles.edit',
            'module_name' => 'Roles'
        ]);

        $permissionDelete = PermissionFactory::new()->create([
            'name' => 'Delete Role',
            'action' => 'admin.roles.destroy',
            'module_name' => 'Roles'
        ]);

        // Assign role to permissions
        $role->permissions()->attach([
            $permissionList->id,
            $permissionCreate->id,
            $permissionUpdate->id,
            $permissionDelete->id
        ]);
    }

    public function test_user_can_get_list_roles()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Create permissions
        $permission1 = PermissionFactory::new()->create([
            'name' => 'Permission One',
            'action' => 'admin.roles.action1',
            'module_name' => 'Roles'
        ]);
        $permission2 = PermissionFactory::new()->create([
            'name' => 'Permission Two',
            'action' => 'admin.roles.action2',
            'module_name' => 'Roles'
        ]);

        // Add role 1
        $response = $this->postJson('/api/v1/roles/create', [
            'name' => 'Supper User 1',
            'list_permission' => [$permission1->id, $permission2->id]
        ]);
        // Add role 2
        $response = $this->postJson('/api/v1/roles/create', [
            'name' => 'Supper User 2',
            'list_permission' => [$permission1->id, $permission2->id]
        ]);

        $response = $this->post('/api/v1/roles');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'permissions' => []
                ],
                [
                    'id',
                    'name',
                    'permissions' => []
                ]
            ],
        ]);
    }

    public function test_user_cannot_get_list_roles_without_permission()
    {
        $this->actingAs($this->user);

        $response = $this->post('/api/v1/roles');
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'code' => 403,
            'message' => 'Forbidden'
        ]);
    }

    public function test_user_can_add_user()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Create permissions
        $permission1 = PermissionFactory::new()->create([
            'name' => 'Permission One',
            'action' => 'admin.roles.action1',
            'module_name' => 'Roles'
        ]);
        $permission2 = PermissionFactory::new()->create([
            'name' => 'Permission Two',
            'action' => 'admin.roles.action2',
            'module_name' => 'Roles'
        ]);

        // Add role 1
        $response = $this->postJson('/api/v1/roles/create', [
            'name' => 'Supper User 1',
            'list_permission' => [$permission1->id, $permission2->id]
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Role created successfully'
        ]);
    }

    public function test_user_cannot_add_role_without_validate_pass()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user
        $response = $this->actingAs($this->user)->postJson('/api/v1/roles/create', [
            'name' => '',
            'list_permission' => []
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'list_permission']);
    }

    public function test_user_cannot_add_role_without_permission()
    {
        $this->actingAs($this->user);

        // Create permissions
        $permission1 = PermissionFactory::new()->create([
            'name' => 'Permission One',
            'action' => 'admin.roles.action1',
            'module_name' => 'Roles'
        ]);
        $permission2 = PermissionFactory::new()->create([
            'name' => 'Permission Two',
            'action' => 'admin.roles.action2',
            'module_name' => 'Roles'
        ]);

        // Add role 1
        $response = $this->postJson('/api/v1/roles/create', [
            'name' => 'Supper User 1',
            'list_permission' => [$permission1->id, $permission2->id]
        ]);
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'code' => 403,
            'message' => 'Forbidden'
        ]);
    }

    public function test_user_can_update_role()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Create permissions
        $permission1 = PermissionFactory::new()->create([
            'name' => 'Permission One',
            'action' => 'admin.roles.action1',
            'module_name' => 'Roles'
        ]);
        $permission2 = PermissionFactory::new()->create([
            'name' => 'Permission Two',
            'action' => 'admin.roles.action2',
            'module_name' => 'Roles'
        ]);
        $permission3 = PermissionFactory::new()->create([
            'name' => 'Permission Three',
            'action' => 'admin.roles.action3',
            'module_name' => 'Roles'
        ]);

        // Add role 1
        $response = $this->postJson('/api/v1/roles/create', [
            'name' => 'Supper User 1',
            'list_permission' => [$permission1->id, $permission2->id]
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Role created successfully'
        ]);

        // Get the ID of the newly created role
        $newRoleId = $response->json('id');
        // Edit user
        $response = $this->patchJson("/api/v1/roles/update/{$newRoleId}", [
            'name' => 'Super User 1 edit',
            'list_permission' => [$permission1->id, $permission2->id, $permission3->id]
        ]);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Role updated successfully'
        ]);
    }

    public function test_user_can_delete_role()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Create permissions
        $permission1 = PermissionFactory::new()->create([
            'name' => 'Permission One',
            'action' => 'admin.roles.action1',
            'module_name' => 'Roles'
        ]);
        $permission2 = PermissionFactory::new()->create([
            'name' => 'Permission Two',
            'action' => 'admin.roles.action2',
            'module_name' => 'Roles'
        ]);

        // Add role 1
        $response = $this->postJson('/api/v1/roles/create', [
            'name' => 'Supper User 1',
            'list_permission' => [$permission1->id, $permission2->id]
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Role created successfully'
        ]);

        // Get the ID of the newly created role
        $newRoleId = $response->json('id');
        // Remove role
        $remove = $this->delete("/api/v1/roles/delete/{$newRoleId}");
        $remove->assertStatus(200);
    }

    public function test_user_cannot_delete_user_when_does_not_exist()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Create permissions
        $permission1 = PermissionFactory::new()->create([
            'name' => 'Permission One',
            'action' => 'admin.roles.action1',
            'module_name' => 'Roles'
        ]);
        $permission2 = PermissionFactory::new()->create([
            'name' => 'Permission Two',
            'action' => 'admin.roles.action2',
            'module_name' => 'Roles'
        ]);

        // Add role 1
        $response = $this->postJson('/api/v1/roles/create', [
            'name' => 'Supper User 1',
            'list_permission' => [$permission1->id, $permission2->id]
        ]);
        $response->assertStatus(201);
        $response->assertJson([
            'message' => 'Role created successfully'
        ]);

        // Get the ID of the newly created role
        $newRoleId = 123456789;
        // Remove role
        $remove = $this->delete("/api/v1/roles/delete/{$newRoleId}");
        $remove->assertStatus(404);
        $remove->assertJson([
            'success' => false,
            'message' => 'Role 123456789 not found'
        ]);
    }

    public function tearDown(): void
    {
        $this->user->tokens()->delete();
        parent::tearDown();
    }
}
