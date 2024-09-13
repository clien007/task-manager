<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Services\CategoryService;
use App\Services\StatusService;
use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Requests\TaskRequest;
use App\Http\Controllers\Controller;
use App\Traits\FiltersTasks;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;




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

    public function index(Request $request)
    {
        $userId = auth()->id();
        
        $query = $this->taskService->getQueryBuilderForUserTasks($userId);
    
        $query = $this->applyFilters($query, $request);

        $tasks = $query->orderBy('id','desc');
    
        $tasks = $query->paginate(10);

        foreach ($tasks as $task) {
            $task->next_status = $this->taskService->getNextStatus($task->status->name);
        }
    
        $categories = $this->categoryService->getAllCategory();
        $statuses = $this->statusService->getAllStatus();
    
        return view('tasks.index', compact('tasks', 'categories', 'statuses'));
    }

    public function create()
    {
        $task = New Task();
        $categories = $this->categoryService->getAllCategory();
        $statuses = $this->statusService->getAllStatus();

        return view('tasks.form', compact('task','categories','statuses'));
    }

    public function store(TaskRequest $request)
    {
        $request->merge(['user_id' => auth()->id()]);
        $request->merge(['status_id' => 1]);
        $this->taskService->createTask($request->all());

        return redirect()->route('tasks.index', ['locale' => app()->getLocale()])->with('success',  __('messages.task_added'));
    }

    public function edit($locale,$id){
        $task = $this->taskService->findTaskById($id);
        $categories = $this->categoryService->getAllCategory();
        $statuses = $this->statusService->getAllStatus();

        return view('tasks.form', compact('task','categories','statuses'));
    }

    public function update(TaskRequest $request, $locale, $taskId){
        $task = $this->taskService->findTaskById($taskId);

        $this->taskService->updateTask($task, $request->all());

        return redirect()->route('tasks.edit', ['locale' => app()->getLocale(), 'task' => $taskId])->with('success',  __('messages.task_updated'));
    }

    public function updateStatus(Request $request, $locale, $taskId){
        $task = $this->taskService->findTaskById($taskId);
        $this->taskService->updateStatus($task, $request->input('next_status'));

        return redirect()->route('tasks.index', ['locale' => app()->getLocale()])->with('success',  __('messages.task_status_updated')); 
    }

    public function destroy($locale, $taskId)
    {
        $this->taskService->deleteTask($taskId);

        return redirect()->route('tasks.index', ['locale' => app()->getLocale()])->with('success',  __('messages.task_deleted'));
    }

}
