<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SubjectTeacherControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_subject_teacher_store()
{
    // Arrange: Create a user and a subject
    $teacher = \App\Models\User::factory()->create();
    $subject = \App\Models\Subject::factory()->create();

    // Act: Make a POST request to assign the subject to the teacher
    $response = $this->postJson('/api/subject-teachers', [
        'subject_id' => $subject->id,
        'user_id' => $teacher->id,
    ]);

    // Assert: Ensure the response status is 201 and the teacher was assigned to the subject
    $response->assertStatus(201)
             ->assertJson(['message' => 'Subject Teacher Added']);

    // Verify that the teacher is associated with the subject
    $teacher->refresh();
    $this->assertTrue($teacher->subjects->contains($subject));
}

}