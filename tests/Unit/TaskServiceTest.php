<?php

namespace Tests\Unit;

use App\Services\TaskService;
use App\Models\Task;
use App\Models\Status;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(RefreshDatabase::class);

class TaskServiceTest extends TestCase
{
    /** @test */
    public function it_updates_task_status_correctly()
    {
        // Seed statuses
        $this->seed('Database\Seeders\StatusSeeder');

        // Fetch the statuses
        $statusNew = Status::where('name', 'New')->first();
        $statusInProgress = Status::where('name', 'In Progress')->first();

        // Create a task
        $task = Task::factory()->create();
        
        $taskService = app(TaskService::class);
        
        // Set initial status to 'New'
        $task->status()->associate($statusNew);
        $task->save();
        
        $taskService->updateStatus($task, 'In Progress');
        
        $task->refresh();
        $this->assertEquals('In Progress', $task->status->name);
        $statusChangeLog = json_decode($task->status_change_log, true);
        $this->assertNotEmpty($statusChangeLog);
        $this->assertEquals('In Progress', end($statusChangeLog)['status']);
    }

    /** @test */
    public function it_throws_exception_for_invalid_status_transition()
    {
        // Seed statuses
        $this->seed('Database\Seeders\StatusSeeder');

        // Fetch the statuses
        $statusNew = Status::where('name', 'New')->first();
        $statusCompleted = Status::where('name', 'Completed')->first();
        
        // Create a task
        $task = Task::factory()->create();
        
        $taskService = app(TaskService::class);
        
        // Set initial status to 'New'
        $task->status()->associate($statusNew);
        $task->save();
        
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Invalid status transition.');
        $taskService->updateStatus($task, 'Completed');
    }
}
