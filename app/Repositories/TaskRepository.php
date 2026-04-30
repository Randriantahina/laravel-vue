<?php

namespace App\Repositories;

use App\DTOs\TaskData;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository
{
    public function allForUser(User $user, ?TaskStatus $status = null): Collection
    {
        return Task::query()
            ->where('user_id', $user->id)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->orderByRaw("CASE status WHEN 'in_progress' THEN 0 WHEN 'todo' THEN 1 ELSE 2 END")
            ->orderBy('due_date')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function findOrFail(int $id): Task
    {
        return Task::findOrFail($id);
    }

    public function create(User $user, TaskData $data): Task
    {
        return Task::create([
            'user_id' => $user->id,
            'title' => $data->title,
            'description' => $data->description,
            'status' => $data->status,
            'priority' => $data->priority,
            'due_date' => $data->dueDate,
        ]);
    }

    public function update(Task $task, TaskData $data): Task
    {
        $task->update([
            'title' => $data->title,
            'description' => $data->description,
            'status' => $data->status,
            'priority' => $data->priority,
            'due_date' => $data->dueDate,
        ]);

        return $task->fresh();
    }

    public function delete(Task $task): void
    {
        $task->delete();
    }
}
