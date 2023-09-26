<?php

namespace Modules\Permission\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Permission\Database\Factories\PermissionFactory;
use Modules\Role\Database\Factories\RoleFactory;
use Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

class PermissionControllerTest extends TestCase
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
        $permissionAction1 = PermissionFactory::new()->create([
            'name' => 'Action 1',
            'action' => 'admin.actions.action1',
            'module_name' => 'Actions'
        ]);

        $permissionAction2 = PermissionFactory::new()->create([
            'name' => 'Action 2',
            'action' => 'admin.actions.action2',
            'module_name' => 'Actions'
        ]);

        $permissionAction3 = PermissionFactory::new()->create([
            'name' => 'Action 3',
            'action' => 'admin.actions.action3',
            'module_name' => 'Actions'
        ]);

        $permissionAction4 = PermissionFactory::new()->create([
            'name' => 'Action 4',
            'action' => 'admin.actions.action3',
            'module_name' => 'Actions'
        ]);

        // Assign role to permissions
        $role->permissions()->attach([
            $permissionAction1->id,
            $permissionAction2->id,
            $permissionAction3->id,
            $permissionAction4->id
        ]);
    }

    public function testUserCanGetListPermissions()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        $response = $this->post('/api/v1/permissions');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'Actions' => [
                [
                    'id',
                    'name',
                    'action'
                ],
                [
                    'id',
                    'name',
                    'action'
                ],
                [
                    'id',
                    'name',
                    'action'
                ],
                [
                    'id',
                    'name',
                    'action'
                ]
            ],
        ]);
    }

    public function tearDown(): void
    {
        $this->user->tokens()->delete();
        parent::tearDown();
    }
}
