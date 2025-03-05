<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Room;

class RoomControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_rooms_index()
{
    // Arrange: Create rooms
    Room::factory()->count(3)->create();

    // Act: Make a GET request to fetch rooms
    $response = $this->getJson('/api/rooms');

    // Assert: Ensure the response contains rooms data
    $response->assertStatus(200)
             ->assertJsonCount(3, 'data'); // Check if 3 rooms are returned
}
public function test_room_show()
{
    // Arrange: Create a room
    $room = Room::factory()->create();

    // Act: Make a GET request to fetch the specific room
    $response = $this->getJson('/api/rooms/' . $room->id);

    // Assert: Ensure the response contains the correct room data
    $response->assertStatus(200)
             ->assertJson([
                 'id' => $room->id,
                 'name' => $room->name,
             ]);
}
public function test_room_store()
{
    // Arrange: Prepare room data
    $data = [
        'name' => 'Room 101',
        'capacity' => 50
    ];

    // Act: Make a POST request to create a new room
    $response = $this->postJson('/api/rooms', $data);

    // Assert: Ensure the room is created and response status is 201
    $response->assertStatus(201)
             ->assertJson([
                 'name' => 'Room 101',
                 'capacity' => 50,
             ]);

    // Optionally, verify the room was added to the database
    $this->assertDatabaseHas('rooms', $data);
}
public function test_room_update()
{
    // Arrange: Create a room
    $room = Room::factory()->create();
    
    // Prepare updated data
    $updatedData = [
        'name' => 'Updated Room 101',
        'capacity' => 60
    ];

    // Act: Make a PUT request to update the room
    $response = $this->putJson('/api/rooms/' . $room->id, $updatedData);

    // Assert: Ensure the room is updated and response is as expected
    $response->assertStatus(201)
             ->assertJson([
                 'message' => 'Room updated successfully',
             ]);

    // Optionally, verify the room data is updated in the database
    $this->assertDatabaseHas('rooms', $updatedData);
}
public function test_room_destroy()
{
    // Arrange: Create a room
    $room = Room::factory()->create();

    // Act: Make a DELETE request to delete the room
    $response = $this->deleteJson('/api/rooms/' . $room->id);

    // Assert: Ensure the room is deleted and response contains the correct message
    $response->assertStatus(200)
             ->assertJson([
                 'message' => 'Room destroy',
             ]);

    // Optionally, verify the room is deleted from the database
    $this->assertDatabaseMissing('rooms', ['id' => $room->id]);
}

}
