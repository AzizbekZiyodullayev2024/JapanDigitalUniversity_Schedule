<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RoleUserControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_role_attach_to_user()
{
    // Arrange: Create a user and a role
    $user = User::factory()->create();
    $role = \App\Models\Role::factory()->create();

    // Prepare the data to attach the role
    $data = [
        'user_id' => $user->id,
        'role_id' => $role->id,
    ];

    // Act: Make a POST request to attach the role to the user
    $response = $this->postJson('/api/role-user', $data);

    // Assert: Ensure the role is attached and response status is 201
    $response->assertStatus(201)
             ->assertJson([
                 'message' => 'Role attached to user',
             ]);

    // Optionally, verify that the role was attached in the database
    $this->assertTrue($user->roles->contains($role));
}
public function test_role_detach_from_user()
{
    // Arrange: Create a user and a role
    $user = User::factory()->create();
    $role = \App\Models\Role::factory()->create();

    // Attach the role to the user first
    $user->roles()->attach($role);

    // Prepare the data to detach the role
    $data = [
        'role_id' => $role->id,
    ];

    // Act: Make a PUT request to detach the role from the user
    $response = $this->putJson('/api/role-user/' . $user->id, $data);

    // Assert: Ensure the role is detached
    $response->assertStatus(200)
             ->assertJson([
                 'message' => 'Role detached from user',
             ]);

    // Optionally, verify that the role was detached in the database
    $this->assertFalse($user->roles->contains($role));
}
public function test_role_detach_from_user_via_destroy()
{
    // Arrange: Create a user and a role
    $user = User::factory()->create();
    $role = \App\Models\Role::factory()->create();

    // Attach the role to the user first
    $user->roles()->attach($role);

    // Prepare the data to detach the role
    $data = [
        'role_id' => $role->id,
    ];

    // Act: Make a DELETE request to detach the role from the user
    $response = $this->deleteJson('/api/role-user/' . $user->id, $data);

    // Assert: Ensure the role is detached
    $response->assertStatus(200)
             ->assertJson([
                 'message' => 'Role detached from user',
             ]);

    // Optionally, verify that the role was detached in the database
    $this->assertFalse($user->roles->contains($role));
}
public function test_store_role_validation_fail()
{
    // Arrange: Create a user
    $user = User::factory()->create();

    // Act: Make a POST request with missing 'role_id'
    $response = $this->postJson('/api/role-user', [
        'user_id' => $user->id,  // Missing role_id
    ]);

    // Assert: Ensure validation error for missing role_id
    $response->assertStatus(422)
             ->assertJsonValidationErrors(['role_id']);
}
public function test_destroy_role_validation_fail()
{
    // Arrange: Create a user
    $user = User::factory()->create();

    // Act: Make a DELETE request with a non-existent role_id
    $response = $this->deleteJson('/api/role-user/' . $user->id, [
        'role_id' => 999,  // Non-existent role_id
    ]);

    // Assert: Ensure validation error for non-existent role_id
    $response->assertStatus(422)
             ->assertJsonValidationErrors(['role_id']);
}

    
}
