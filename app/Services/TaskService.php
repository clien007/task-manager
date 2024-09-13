<?php

namespace App\Services;

use App\Models\Task;
use App\Models\Archive;
use App\Models\Status;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Services\StatusService;
use App\Services\CategoryService;


class TaskService
{

    protected $validStatuses = [
        'New' => 'In Progress',
        'In Progress' => 'Under Review',
        'Under Review' => 'Completed'
    ];

    public function __construct(
        StatusService $statusService,
        CategoryService $categoryService
        )
    {
        $this->statusService = $statusService;
        $this->categoryService = $categoryService;
    }

    public function getAllTask($userId){
        return Task::where('user_id', $userId)
            ->with(['category','status'])
            ->orderBy('id','desc')
            ->get();
    }

    public function getQueryBuilderForUserTasks($userId){

        return Task::where('user_id', $userId);
    }

    public function getQueryBuilderForArchivedTasks($userId){
        
        return Archive::where('user_id', $userId);
    }

    public function findTaskById($taskId){
        return Task::findOrFail($taskId);
    } 
    
    public function getNextStatus($currentStatusName){

        $validStatuses = [
            'New' => 'In Progress',
            'In Progress' => 'Under Review',
            'Under Review' => 'Completed'
        ];

        return $validStatuses[$currentStatusName] ?? null;
    }

    public function createTask(array $data)
    {
        return Task::create($data);
    }

    public function updateTask(Task $task, array $data)
    {
        $task->update($data);
        return $task;
    }

    public function updateStatus(Task $task, string $newStatusName)
    {
        $currentStatus = $task->status->name;
        $status = Status::where('name', $newStatusName)->first();

        if (!$status) {
            throw new \Exception('Invalid status.');
        }

        // Prevent setting the status back to 'New'
        if ($newStatusName === 'New') {
            throw new \Exception('Status cannot be set back to "New" once changed.');
        }

        // Check if the new status is the next valid status
        if (!isset($this->validStatuses[$currentStatus]) || $this->validStatuses[$currentStatus] !== $newStatusName) {
            throw new \Exception('Invalid status transition.');
        }

        // Initialize status change log as an array
        $statusChangeLog = json_decode($task->status_change_log, true) ?? [];

        // Append new status change
        $statusChangeLog[] = [
            'status' => $newStatusName,
            'changed_at' => Carbon::now()->toDateTimeString(),
        ];

        // Update status and log the status change with a timestamp
        $task->status()->associate($status);
        $task->status_change_log = json_encode($statusChangeLog);

        // If the status is 'Completed', set the completion date
        if ($newStatusName === 'Completed') {
            $task->completion_date = Carbon::now();
        }

        // Save the updated task
        $task->save();

        // Log the status change for auditing
        Log::info('Task status changed', [
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'new_status' => $newStatusName,
            'changed_at' => Carbon::now()->toDateTimeString(),
        ]);

        return $task;
    }

    public function changeStatus(Task $task, string $status)
    {
        $task->status = $status;
        $task->save();
        return $task;
    }

    public function deleteTask(int $taskId)
    {
        $task = Task::findOrFail($taskId);

        if ($task->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $task->delete();
    }

    public function getTaskStatistics($userId)
    {
        $statuses = $this->statusService->getAllStatus();
        $taskCountsByStatus = [];
        
        foreach ($statuses as $status) {
            $taskCountsByStatus[$status->name] = $this->getQueryBuilderForUserTasks($userId)
                ->where('status_id', $status->id)
                ->count();
        }

        $categories = $this->categoryService->getAllCategory(10);
        $taskCountsByCategory = [];

        foreach ($categories as $category) {
            $taskCountsByCategory[$category->name] = $this->getQueryBuilderForUserTasks($userId)
                ->where('category_id', $category->id)
                ->count();
        }

        $recentTasks = $this->getQueryBuilderForUserTasks($userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $nearArchivedTasks = $this->getQueryBuilderForUserTasks($userId)
                ->whereBetween('completion_date', [Carbon::now()->subDays(30), Carbon::now()->subDays(25)])
                ->count();

        $archivedTasks = $this->getQueryBuilderForArchivedTasks($userId)
                ->count();

        return [
            'taskCountsByStatus' => $taskCountsByStatus,
            'taskCountsByCategory' => $taskCountsByCategory,
            'recentTasks' => $recentTasks,
            'nearArchivedTasks' => $nearArchivedTasks,
            'archivedTasks' => $archivedTasks
        ];
    }

}