<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;

class CreateTaskInteractive extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:create-interactive';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Interactive task creation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $title = $this->ask('Enter task title');
        $description = $this->ask('Description (optional)');
        $dueDate = $this->ask('Deadline date (YYYY-MM-DD)');
        $status = $this->choice('Status', ['new', 'in_progress', 'done'], 0);
        $assigneeId = $this->ask('Assignee ID (or leave empty)');
        $projectId = $this->ask('Project ID');
        $authorId = $this->ask('Author ID');

        $confirm = $this->confirm('Create this task?', true);

        if (!$confirm) {
            return $this->warn('Creation cancelled.');
        }

        $task = Task::create([
            'title' => $title,
            'description' => $description,
            'status' => $status,
            'due_date' => $dueDate,
            'assignee_id' => $assigneeId ?: null,
            'project_id' => $projectId,
            'author_id' => $authorId,
        ]);

        $this->info("Task '{$task->title}' created with ID: {$task->id}");
    }
}
