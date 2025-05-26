<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;

class GenerateTaskReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:report {--project_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Output list of tasks (all or from certain project)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $projectId = $this->option('project_id');

        if ($projectId) {
            $tasks = Task::where('project_id', $projectId)->get();
            $this->info("Tasks from project ID {$projectId}");
        } else {
            $tasks = Task::all();
            $this->info("All tasks in the system");
        }

        if ($tasks->isEmpty()) {
            return $this->warn('Tasks not found.');
        }

        $data = $tasks->map(function ($task) {
            return [
                'ID' => $task->id,
                'Title' => $task->title,
                'Status' => $task->status,
                'Deadline' => $task->due_date,
            ];
        });

        $this->table(['ID', 'Title', 'Status', 'Deadline'], $data);
    }
}
