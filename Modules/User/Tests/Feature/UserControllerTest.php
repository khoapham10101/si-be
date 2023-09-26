<?php

namespace Modules\User\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Permission\Database\Factories\PermissionFactory;
use Modules\Role\Database\Factories\RoleFactory;
use Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

class UserControllerTest extends TestCase
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
            'name' => 'List Users',
            'action' => 'admin.users.index',
            'module_name' => 'Users'
        ]);

        $permissionCreate = PermissionFactory::new()->create([
            'name' => 'Create User',
            'action' => 'admin.users.create',
            'module_name' => 'Users'
        ]);

        $permissionUpdate = PermissionFactory::new()->create([
            'name' => 'Update User',
            'action' => 'admin.users.edit',
            'module_name' => 'Users'
        ]);

        $permissionDelete = PermissionFactory::new()->create([
            'name' => 'Delete User',
            'action' => 'admin.users.destroy',
            'module_name' => 'Users'
        ]);

        // Assign role to permissions
        $role->permissions()->attach([
            $permissionList->id,
            $permissionCreate->id,
            $permissionUpdate->id,
            $permissionDelete->id
        ]);
    }

    public function testUserCanGetListUsers()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add user 1
        $response = $this->postJson('/api/v1/users/create', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'id_card' => '1234',
            'birthday' => '1999-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com',
            'password' => 'Si2023!@',
        ]);
        // Add user 2
        $response = $this->postJson('/api/v1/users/create', [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
            'id_card' => '1234',
            'birthday' => '1998-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'anna@gmail.com',
            'password' => 'Si2023!@',
        ]);

        $response = $this->post('/api/v1/users');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'full_name',
                    'gender_id',
                    'gender',
                    'user_status_id',
                    'user_status'
                ]
            ],
        ]);
    }

    public function testUserCannotGetListUsersWithoutPermission()
    {
        $this->actingAs($this->user);

        $response = $this->post('/api/v1/users');
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'code' => 403,
            'message' => 'Forbidden'
        ]);
    }

    public function testUserCanDddUser()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user
        $response = $this->postJson('/api/v1/users/create', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'id_card' => '1234',
            'birthday' => '1999-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com',
            'password' => 'Si2023!@',
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['full_name' => 'John Doe']);
    }

    public function testUserCannotDddUserWithoutValidatePass()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user
        $response = $this->actingAs($this->user)->postJson('/api/v1/users/create', [
            'first_name' => '',
            'last_name' => '',
            'id_card' => '1234',
            'birthday' => '1999-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com',
            'password' => 'Si2023!@',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['first_name', 'last_name']);
    }

    public function testUserCannotAddUserWhenTheEmailAlreadyExits()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user 1
        $response = $this->actingAs($this->user)->postJson('/api/v1/users/create', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'id_card' => '1234',
            'birthday' => '1999-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com', // email john@gmail.com
            'password' => 'Si2023!@',
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['full_name' => 'John Doe']);

        // Add user 2
        $response = $this->postJson('/api/v1/users/create', [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
            'id_card' => '1234',
            'birthday' => '1998-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654322',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com', // email john@gmail.com
            'password' => 'Si2023!@',
        ]);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors('email');
    }

    public function testUserCannotAddUserWithoutPermission()
    {
        $this->actingAs($this->user);

        // Add new user
        $response = $this->postJson('/api/v1/users/create', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'id_card' => '1234',
            'birthday' => '1999-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com',
            'password' => 'Si2023!@',
        ]);
        $response->assertStatus(403);
        $response->assertJson([
            'success' => false,
            'code' => 403,
            'message' => 'Forbidden'
        ]);
    }

    public function testUserCanUpdateUser()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user
        $response = $this->postJson('/api/v1/users/create', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'id_card' => '1234',
            'birthday' => '1999-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com',
            'password' => 'Si2023!@',
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['full_name' => 'John Doe']);

        // Get the ID of the newly created user
        $newUserId = $response->json('id');
        // Edit user
        $response = $this->postJson("/api/v1/users/update/{$newUserId}", [
            'first_name' => 'Anna',
            'last_name' => 'Smith',
            'id_card' => '1234',
            'birthday' => '1999-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com',
            'password' => 'Si2023!@',
        ]);
        $response->assertStatus(200);
        $response->assertJsonFragment(['full_name' => 'Anna Smith']);
    }

    public function testUserCanDeleteUser()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user
        $response = $this->postJson('/api/v1/users/create', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'id_card' => '1234',
            'birthday' => '1999-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com',
            'password' => 'Si2023!@',
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['full_name' => 'John Doe']);

        // Get the ID of the newly created user
        $newUserId = $response->json('id');
        // Remove user
        $remove = $this->delete("/api/v1/users/delete/{$newUserId}");
        $remove->assertStatus(204);
    }

    public function testUserCannotDeleteUserWhenDoesNotExist()
    {
        $this->actingAs($this->user);
        $this->setUpCommonData();

        // Add new user
        $response = $this->postJson('/api/v1/users/create', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'id_card' => '1234',
            'birthday' => '1999-09-09',
            'gender_id' => 1,
            'id_1' => '12345',
            'id_2' => '56789',
            'avatar' => '',
            'phone' => '0987654321',
            'address' => 'BMT, VN',
            'user_status_id' => 1,
            'email' => 'john@gmail.com',
            'password' => 'Si2023!@',
        ]);
        $response->assertStatus(201);
        $response->assertJsonFragment(['full_name' => 'John Doe']);

        // Get the ID of the newly created user
        $newUserId = 123456789;
        // Remove user
        $remove = $this->delete("/api/v1/users/delete/{$newUserId}");
        $remove->assertStatus(404);
        $remove->assertJson([
            'success' => false,
            'message' => 'User 123456789 not found'
        ]);
    }

    public function tearDown(): void
    {
        $this->user->tokens()->delete();
        parent::tearDown();
    }
}
