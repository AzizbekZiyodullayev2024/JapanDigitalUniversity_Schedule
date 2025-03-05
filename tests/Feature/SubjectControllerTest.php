<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_subjects_index()
{
    // Arrange: Create multiple subjects
    \App\Models\Subject::factory(5)->create();

    // Act: Make a GET request to fetch the list of subjects
    $response = $this->getJson('/api/subjects');

    // Assert: Ensure the response status is 200 and the list contains subjects
    $response->assertStatus(200)
             ->assertJsonCount(5, 'data');  // Assert there are 5 subjects in the response
}
public function test_subject_show()
{
    // Arrange: Create a subject
    $subject = \App\Models\Subject::factory()->create();

    // Act: Make a GET request to fetch the specific subject
    $response = $this->getJson('/api/subjects/' . $subject->id);

    // Assert: Ensure the response status is 200 and contains the correct subject data
    $response->assertStatus(200)
             ->assertJson([
                 'id' => $subject->id,
                 'name' => $subject->name,  // Assuming the 'name' field exists
             ]);
}
public function test_subject_store()
{
    // Arrange: Prepare the data for the new subject
    $data = [
        'name' => 'New Subject',
        'description' => 'Description of the new subject',
    ];

    // Act: Make a POST request to store the subject
    $response = $this->postJson('/api/subjects', $data);

    // Assert: Ensure the response status is 201 and the subject is created
    $response->assertStatus(201)
             ->assertJson([
                 'message' => 'Subject created successfully',
             ]);

    // Optionally, verify that the subject is stored in the database
    $this->assertDatabaseHas('subjects', [
        'name' => 'New Subject',
    ]);
}
public function test_subject_update()
{
    // Arrange: Create a subject
    $subject = \App\Models\Subject::factory()->create();

    // Prepare the new data for updating the subject
    $data = [
        'name' => 'Updated Subject Name',
        'description' => 'Updated description of the subject',
    ];

    // Act: Make a PUT request to update the subject
    $response = $this->putJson('/api/subjects/' . $subject->id, $data);

    // Assert: Ensure the response status is 201 and the subject is updated
    $response->assertStatus(201)
             ->assertJson([
                 'message' => 'Subject updated successfully',
             ]);

    // Optionally, verify that the subject is updated in the database
    $subject->refresh();
    $this->assertEquals('Updated Subject Name', $subject->name);
}
public function test_subject_destroy()
{
    // Arrange: Create a subject
    $subject = \App\Models\Subject::factory()->create();

    // Act: Make a DELETE request to remove the subject
    $response = $this->deleteJson('/api/subjects/' . $subject->id);

    // Assert: Ensure the response status is 201 and the subject is deleted
    $response->assertStatus(201)
             ->assertJson([
                 'message' => 'Subject deleted successfully',
             ]);

    // Optionally, verify that the subject is deleted from the database
}

    
}
