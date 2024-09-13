<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

class TaskControllerTest extends TestCase
{
    /** @test */
    public function it_allows_authenticated_users_to_access_task_index_page()
    {
        // Seed statuses
        $this->seed('Database\Seeders\StatusSeeder');

        // Create a user
        $user = User::factory()->create();

        // Act as the user and visit the tasks index page
        $response = $this->actingAs($user)->get('/tasks');

        $response->assertStatus(200);
        $response->assertSee('Tasks');
    }

    /** @test */
    public function it_allows_a_user_to_create_a_task()
    {
        // Seed statuses and create a category
        $this->seed('Database\Seeders\StatusSeeder');
        $category = Category::factory()->create();
        $status = Status::where('name', 'New')->first();
    
        // Create a user
        $user = User::factory()->create();
    
        // Act as the user and post a request to create a task
        $response = $this->actingAs($user)->post('/tasks', [
            'title' => 'Test Task',
            'description' => 'Test',
            'category_id' => $category->id,
            'status_id' => $status->id
        ]);
    
        // Assert that the response redirects to the correct URL
        $response->assertRedirect('/tasks'); // Ensure this matches the redirect URL in the controller
    
        // Assert that the task is in the database
        $this->assertDatabaseHas('tasks', ['title' => 'Test Task']);
    }

    /** @test */
    public function it_allows_a_user_to_update_a_task()
    {
        // Seed statuses
        $this->seed('Database\Seeders\StatusSeeder');
        $status = Status::where('name', 'New')->first();

        // Create a user and a task
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id, 'status_id' => $status->id]);

        // Act as the user and put a request to update the task
        $response = $this->actingAs($user)->put('/tasks/' . $task->id, [
            'name' => 'Updated Task',
            'status_id' => $status->id,
        ]);

        $response->assertRedirect('/tasks/' . $task->id . '/edit');
        $this->assertDatabaseHas('tasks', ['name' => 'Updated Task']);
    }

    /** @test */
    public function it_allows_a_user_to_delete_a_task()
    {
        // Seed statuses
        $this->seed('Database\Seeders\StatusSeeder');

        // Create a user and a task
        $user = User::factory()->create();
        $task = Task::factory()->create(['user_id' => $user->id]);

        // Act as the user and delete the task
        $response = $this->actingAs($user)->delete('/tasks/' . $task->id);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
