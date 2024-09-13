<?php

namespace App\Http\Controllers\API;

use App\Services\TaskService;
use App\Services\CategoryService;
use App\Services\StatusService;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Traits\FiltersTasks;



class TaskController extends Controller
{

    use FiltersTasks;

    protected $taskService;
    protected $categoryService;

    public function __construct(
        TaskService $taskService,
        CategoryService $categoryService,
        StatusService $statusService
        )
    {
        $this->taskService = $taskService;
        $this->categoryService = $categoryService;
        $this->statusService = $statusService;

        $this->middleware('auth');
        $this->middleware('task.ownership');
    }

    public function taskStatistics()
    {
        $userId = auth()->id();

        $taskStatistics = Cache::remember("user_{$userId}_task_statistics", now()->addMinutes(10), function () use ($userId) {
            return $this->taskService->getTaskStatistics($userId);
        });
    
        return response()->json($taskStatistics);
    }
    

    public function index(Request $request)
    {
        $userId = auth()->id();
        
        $query = $this->taskService->getQueryBuilderForUserTasks($userId);
    
        $query = $this->applyFilters($query, $request);
    
        $tasks = $query->paginate(10);
    
        $categories = $this->categoryService->getAllCategory();
        $statuses = $this->statusService->getAllStatus();

        return response()->json([
            'tasks' => $tasks,
            'categories' => $categories,
            'statuses' => $statuses
        ]);
    
    }

    public function create()
    {
        $task = New Task();
        $categories = $this->categoryService->getAllCategory();
        $statuses = $this->statusService->getAllStatus();

        return response()->json([
            'task' => $task,
            'categories' => $categories,
            'statuses' => $statuses
        ]);
    }

    public function store(TaskRequest $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        $request->merge(['status_id' => 1]);
        $task = $this->taskService->createTask($request->all());

        return response()->json([
            'task' => $task,
            'message' => 'Task Successfully Added',
        ]);
    }

    public function edit($id){
        $task = $this->taskService->findTaskById($id);
        $categories = $this->categoryService->getAllCategory();
        $statuses = $this->statusService->getAllStatus();

        return response()->json([
            'tasks' => $tasks,
            'categories' => $categories,
            'statuses' => $statuses
        ]);
    }

    public function update(TaskRequest $request, $taskId){
        $task = $this->taskService->findTaskById($taskId);

        $updated_task = $this->taskService->updateTask($task, $request->all());

        return response()->json([
            'task' => $updated_task,
            'message' => 'Task Successfully Updated',
        ]);

    }

    public function updateStatus(Request $request, $taskId){
        $task = $this->taskService->findTaskById($taskId);
        $this->taskService->updateStatus($task, $request->input('next_status'));

        return response()->json([
            'task' => $task,
            'message' => 'Task Status Successfully Updated',
        ]);
    }

    public function destroy($taskId)
    {
        $task = $this->taskService->deleteTask($taskId);

        return response()->json([
            'task_id' => $taskId,
            'message' => 'Task Successfully Deleted',
        ]);

    }

}

