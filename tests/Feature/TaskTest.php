<?php

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Events\TaskCompleted;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    $this->withHeaders(['X-Inertia' => 'true']);
});

test('guests are redirected to login when accessing tasks', function () {
    auth()->logout();
    $this->get(route('tasks.index'))->assertRedirect(route('login'));
});

test('authenticated user can view their tasks', function () {
    Task::factory()->count(3)->create(['user_id' => $this->user->id]);
    Task::factory()->count(2)->create();

    $response = $this->get(route('tasks.index'));

    $response->assertOk()
        ->assertJsonPath('component', 'Tasks/Index');

    expect(count($response->json('props.tasks')))->toBe(3);
});

test('tasks can be filtered by status', function () {
    Task::factory()->todo()->create(['user_id' => $this->user->id]);
    Task::factory()->done()->create(['user_id' => $this->user->id]);

    $response = $this->get(route('tasks.index', ['status' => 'todo']));

    $response->assertOk();
    expect(count($response->json('props.tasks')))->toBe(1);
});

test('authenticated user can create a task', function () {
    $this->post(route('tasks.store'), [
        'title' => 'Nouvelle tâche',
        'description' => 'Une description',
        'status' => 'todo',
        'priority' => 'medium',
        'due_date' => null,
    ])->assertRedirect(route('tasks.index'));

    $this->assertDatabaseHas('tasks', [
        'user_id' => $this->user->id,
        'title' => 'Nouvelle tâche',
        'status' => 'todo',
    ]);
});

test('task creation requires a title', function () {
    $this->post(route('tasks.store'), [
        'title' => '',
        'status' => 'todo',
        'priority' => 'medium',
    ])->assertSessionHasErrors('title');
});

test('authenticated user can update their own task', function () {
    $task = Task::factory()->todo()->create(['user_id' => $this->user->id]);

    $this->put(route('tasks.update', $task), [
        'title' => 'Titre modifié',
        'status' => 'in_progress',
        'priority' => 'high',
        'due_date' => null,
    ])->assertRedirect(route('tasks.index'));

    expect($task->fresh()->title)->toBe('Titre modifié')
        ->and($task->fresh()->status)->toBe(TaskStatus::InProgress);
});

test('user cannot update another user\'s task', function () {
    $task = Task::factory()->create();

    $this->put(route('tasks.update', $task), [
        'title' => 'Tentative',
        'status' => 'todo',
        'priority' => 'low',
    ])->assertForbidden();
});

test('user cannot delete another user\'s task', function () {
    $task = Task::factory()->create();

    $this->delete(route('tasks.destroy', $task))->assertForbidden();
});

test('authenticated user can delete their own task', function () {
    $task = Task::factory()->create(['user_id' => $this->user->id]);

    $this->delete(route('tasks.destroy', $task))
        ->assertRedirect(route('tasks.index'));

    $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
});

test('TaskCompleted event is dispatched when status changes to done', function () {
    Event::fake();

    $task = Task::factory()->todo()->create(['user_id' => $this->user->id]);

    $this->put(route('tasks.update', $task), [
        'title' => $task->title,
        'status' => 'done',
        'priority' => $task->priority->value,
        'due_date' => null,
    ]);

    Event::assertDispatched(TaskCompleted::class, fn ($event) => $event->task->id === $task->id);
});

test('TaskCompleted event is not dispatched when status stays done', function () {
    Event::fake();

    $task = Task::factory()->done()->create(['user_id' => $this->user->id]);

    $this->put(route('tasks.update', $task), [
        'title' => $task->title,
        'status' => 'done',
        'priority' => $task->priority->value,
        'due_date' => null,
    ]);

    Event::assertNotDispatched(TaskCompleted::class);
});
