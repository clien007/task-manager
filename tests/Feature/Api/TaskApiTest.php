<?php

// tests/Feature/Api/TaskApiTest.php

namespace Tests\Feature\Api;

use App\Models\Task;
use App\Models\User;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(RefreshDatabase::class);

class TaskApiTest extends TestCase
{
    /** @test */
    public function it_requires_authentication_for_api_endpoints()
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);
    }

    /** @test */
    public function it_allows_a_user_to_create_a_task_via_api()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $category = Category::factory()->create();
        $status = Status::factory()->create(['name' => 'New']);
        
        $response = $this->postJson('/api/tasks', [
            'name' => 'API Task',
            'category_id' => $category->id,
            'status_id' => $status->id,
        ]);
        
        $response->assertStatus(201);
        $this->assertDatabaseHas('tasks', ['name' => 'API Task']);
    }

    /** @test */
    public function it_allows_a_user_to_update_a_task_via_api()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $task = Task::factory()->create(['user_id' => $user->id]);
        
        $response = $this->putJson('/api/tasks/' . $task->id, [
            'name' => 'Updated API Task',
            'status_id' => $task->status_id,
        ]);
        
        $response->assertStatus(200);
        $this->assertDatabaseHas('tasks', ['name' => 'Updated API Task']);
    }

    /** @test */
    public function it_allows_a_user_to_delete_a_task_via_api()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'api');
        $task = Task::factory()->create(['user_id' => $user->id]);
        
        $response = $this->deleteJson('/api/tasks/' . $task->id);
        
        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
