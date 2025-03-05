<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ScheduleControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_schedule()
{
    // Arrange: Create necessary records (subject, teacher, group)
    $subject = \App\Models\Subject::factory()->create();
    $teacher = \App\Models\User::factory()->create();
    $group = \App\Models\Group::factory()->create();

    // Act: Make a POST request to create a schedule
    $response = $this->postJson('/api/schedules', [
        'subject_id' => $subject->id,
        'teacher_id' => $teacher->id,
        'group_id' => $group->id,
        'pair' => 1,
        'week_day' => 'Monday',
        'date' => '2025-03-10',
    ]);

    // Assert: Ensure the schedule is created successfully
    $response->assertStatus(201)
             ->assertJson(['message' => 'Schedule created successfully']);

    // Verify: Check if the schedule is stored in the database
    $this->assertDatabaseHas('schedules', [
        'subject_id' => $subject->id,
        'teacher_id' => $teacher->id,
        'group_id' => $group->id,
    ]);
}
public function test_create_duplicate_schedule()
{
    // Arrange: Create a schedule in the database
    $subject = \App\Models\Subject::factory()->create();
    $teacher = \App\Models\User::factory()->create();
    $group = \App\Models\Group::factory()->create();
    
    $schedule = \App\Models\Schedule::factory()->create([
        'subject_id' => $subject->id,
        'teacher_id' => $teacher->id,
        'group_id' => $group->id,
    ]);

    // Act: Try to create a duplicate schedule
    $response = $this->postJson('/api/schedules', [
        'subject_id' => $subject->id,
        'teacher_id' => $teacher->id,
        'group_id' => $group->id,
        'pair' => $schedule->pair,
        'week_day' => $schedule->week_day,
        'date' => $schedule->date,
    ]);

    // Assert: Check for conflict response
    $response->assertStatus(409)
             ->assertJson(['message' => 'Schedule already exists']);
}
public function test_update_schedule()
{
    // Arrange: Create a schedule
    $schedule = \App\Models\Schedule::factory()->create();

    // Act: Make a PUT request to update the schedule
    $response = $this->putJson('/api/schedules/' . $schedule->id, [
        'subject_id' => $schedule->subject_id,
        'teacher_id' => $schedule->teacher_id,
        'group_id' => $schedule->group_id,
        'pair' => 2,  // Change the pair
        'week_day' => 'Tuesday',
        'date' => '2025-03-11',
    ]);

    // Assert: Check if the update was successful
    $response->assertStatus(200)
             ->assertJson(['message' => 'Schedule updated successfully']);

    // Verify: Check if the database was updated
    $schedule->refresh();
    $this->assertEquals(2, $schedule->pair);
    $this->assertEquals('Tuesday', $schedule->week_day);
}
public function test_delete_schedule()
{
    // Arrange: Create a schedule
    $schedule = \App\Models\Schedule::factory()->create();

    // Act: Make a DELETE request to remove the schedule
    $response = $this->deleteJson('/api/schedules/' . $schedule->id);

    // Assert: Ensure the schedule was deleted
    $response->assertStatus(200)
             ->assertJson(['message' => 'Schedule deleted successfully']);

    // Verify: Check if the schedule is removed from the database
    $this->assertDatabaseMissing('schedules', [
        'id' => $schedule->id,
    ]);
}
public function test_show_schedule()
{
    // Arrange: Create a schedule
    $schedule = \App\Models\Schedule::factory()->create();

    // Act: Make a GET request to view the schedule
    $response = $this->getJson('/api/schedules/' . $schedule->id);

    // Assert: Ensure the schedule data is returned correctly
    $response->assertStatus(200)
             ->assertJson([
                 'id' => $schedule->id,
                 'subject_id' => $schedule->subject_id,
                 'teacher_id' => $schedule->teacher_id,
                 'group_id' => $schedule->group_id,
             ]);
}

}
