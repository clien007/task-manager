<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Task;


class ArchiveCompletedTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:archive-completed';

    // The console command description
    protected $description = 'Archive completed tasks older than 30 days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = Carbon::now()->subDays(30);
        // Log::info('ArchiveOldTasksJob started');


        // Start a database transaction
        DB::transaction(function () use ($date) {
            // Query the tasks completed more than 30 days ago
            $tasks = Task::where('status_id', '=', 4) // Assuming 3 is the completed status
                        ->where('completion_date', '<', $date)
                        ->get();

            // Move each task to the archives table and delete from the tasks table
            foreach ($tasks as $task) {
                // Insert task data into archives table
                DB::table('archives')->insert([
                    'user_id'     => $task->user_id,
                    'title'       => $task->title,
                    'description' => $task->description,
                    'status_id'   => $task->status_id,
                    'category_id' => $task->category_id,
                    'completion_date' => $task->completion_date,
                    'status_change_log' => $task->status_change_log,
                    'created_at'  => Carbon::now(),
                    'updated_at'  => Carbon::now(),
                ]);

                // Delete the task from the tasks table
                $task->delete();
            }

            $this->info("Archived {$task} tasks.");
        });

        // Log::info('ArchiveOldTasksJob completed');
    }
}
