<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskOwnershipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        
        if ($request->is('tasks/*')) {
            $taskId = $request->route('task');
            $userId = auth()->id();
            $task = \App\Models\Task::find($taskId);

            if ($task && $task->user_id !== $userId) {
                return redirect('/dashboard')->with('error', 'Unauthorized action.');
            }
        }

        return $next($request);
    }
}
