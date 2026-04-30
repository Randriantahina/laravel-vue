<?php

namespace App\Http\Controllers;

use App\DTOs\TaskData;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $service,
    ) {}

    public function index(Request $request): Response
    {
        $status = $request->filled('status') ? TaskStatus::from($request->string('status')->toString()) : null;

        $tasks = $this->service->list($request->user(), $status);

        return Inertia::render('Tasks/Index', [
            'tasks' => $tasks->map(fn (Task $task) => [
                'id' => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'status' => $task->status->value,
                'priority' => $task->priority->value,
                'due_date' => $task->due_date?->toDateString(),
                'created_at' => $task->created_at->toDateString(),
            ]),
            'filters' => ['status' => $request->input('status', '')],
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        $this->service->store($request->user(), TaskData::fromRequest($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Tâche créée.']);

        return to_route('tasks.index');
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $this->service->update($task, TaskData::fromRequest($request));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Tâche mise à jour.']);

        return to_route('tasks.index');
    }

    public function destroy(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $this->service->delete($task);

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Tâche supprimée.']);

        return to_route('tasks.index');
    }
}
