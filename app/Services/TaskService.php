<?php

namespace App\Services;

use App\DTOs\TaskData;
use App\Enums\TaskStatus;
use App\Events\TaskCompleted;
use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    public function __construct(
        private readonly TaskRepository $repository,
    ) {}

    public function list(User $user, ?TaskStatus $status = null): Collection
    {
        return $this->repository->allForUser($user, $status);
    }

    public function store(User $user, TaskData $data): Task
    {
        return $this->repository->create($user, $data);
    }

    public function update(Task $task, TaskData $data): Task
    {
        $previousStatus = $task->status;

        $updated = $this->repository->update($task, $data);

        if ($previousStatus !== TaskStatus::Done && $updated->status === TaskStatus::Done) {
            TaskCompleted::dispatch($updated);
        }

        return $updated;
    }

    public function delete(Task $task): void
    {
        $this->repository->delete($task);
    }
}
