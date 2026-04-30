<?php

namespace App\DTOs;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

final class TaskData
{
    public function __construct(
        public readonly string $title,
        public readonly ?string $description,
        public readonly TaskStatus $status,
        public readonly TaskPriority $priority,
        public readonly ?Carbon $dueDate,
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            title: $request->string('title')->toString(),
            description: $request->string('description')->toString() ?: null,
            status: TaskStatus::from($request->string('status')->toString()),
            priority: TaskPriority::from($request->string('priority')->toString()),
            dueDate: $request->filled('due_date') ? Carbon::parse($request->input('due_date')) : null,
        );
    }
}
