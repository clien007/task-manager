<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Services\CategoryService;
use App\Services\StatusService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;


class DashboardController extends Controller
{
    protected $taskService;
    protected $categoryService;
    protected $statusService;

    public function __construct(
        TaskService $taskService,
        CategoryService $categoryService,
        StatusService $statusService
    ) {
        $this->taskService = $taskService;
        $this->categoryService = $categoryService;
        $this->statusService = $statusService;
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = auth()->id();

        $taskStatistics = Cache::remember("user_{$userId}_task_statistics", now()->addMinutes(10), function () use ($userId) {
            return $this->taskService->getTaskStatistics($userId);
        });
    
        return view('dashboard', [
            'taskCountsByStatus' => $taskStatistics['taskCountsByStatus'],
            'taskCountsByCategory' => $taskStatistics['taskCountsByCategory'],
            'recentTasks' => $taskStatistics['recentTasks'],
            'nearArchivedTasks' => $taskStatistics['nearArchivedTasks'],
            'archivedTasks' => $taskStatistics['archivedTasks']
        ]);
    }
}
