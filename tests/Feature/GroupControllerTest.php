<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Group;

class GroupControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_group_show()
    {
    // Arrange: Create a group
    $group = Group::factory()->create();

    // Act: Make a GET request to fetch the specific group
    $response = $this->getJson('/api/groups/' . $group->id);

    // Assert: Ensure the response contains the correct group data
    $response->assertStatus(200)
             ->assertJson([
                 'id' => $group->id,
                 'name' => $group->name,
             ]);
    }
    public function test_groups_index()
    {
        // Arrange: Create some groups
        Group::factory()->count(3)->create();
    
        // Act: Make a GET request to the index route
        $response = $this->getJson('/api/groups?per_page=3');
    
        // Assert: Ensure the response is successful and contains the correct data
        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'name', 'created_at', 'updated_at'],
                     ],
                 ])
                 ->assertJsonCount(3, 'data'); // Ensure 3 groups are returned
    }
    public function test_group_create()
    {
        // Arrange: Define data to create a new group
        $data = ['name' => 'New Group'];
    
        // Act: Make a POST request to create the group
        $response = $this->postJson('/api/groups', $data);
    
        // Assert: Ensure the response contains a success message
        $response->assertStatus(201)
                 ->assertJson(['message' => 'Group created successfully']);
    
        // Verify the group is in the database
        $this->assertDatabaseHas('groups', ['name' => 'New Group']);
    }
    public function test_group_update()
    {
        // Arrange: Create a group and define new data to update it
        $group = Group::factory()->create();
        $newData = ['name' => 'Updated Group Name'];
    
        // Act: Make a PUT request to update the group
        $response = $this->putJson('/api/groups/' . $group->id, $newData);
    
        // Assert: Ensure the response contains a success message
        $response->assertStatus(201)
                 ->assertJson(['message' => 'Group updated successfully']);
    
        // Verify the group is updated in the database
        $this->assertDatabaseHas('groups', ['id' => $group->id, 'name' => 'Updated Group Name']);
    }
    public function test_group_delete()
    {
        // Arrange: Create a group to delete
        $group = Group::factory()->create();
    
        // Act: Make a DELETE request to remove the group
        $response = $this->deleteJson('/api/groups/' . $group->id);
    
        // Assert: Ensure the response contains the success message
        $response->assertStatus(200)
                 ->assertJson(['message' => 'Group destroy']);
    
        // Verify the group has been deleted from the database
        $this->assertDatabaseMissing('groups', ['id' => $group->id]);
    }
                
    }
