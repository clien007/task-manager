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


class ArchiveController extends Controller
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
        $this->middleware('archive.ownership');
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        
        $query = $this->taskService->getQueryBuilderForArchivedTasks($userId);
    
        $query = $this->applyFilters($query, $request);

        $tasks = $query->orderBy('id','desc');
    
        $tasks = $query->paginate(10);
    
        $categories = $this->categoryService->getAllCategory();
        $statuses = $this->statusService->getAllStatus();
    
        return view('archives.index', compact('tasks', 'categories', 'statuses'));
    }
}
