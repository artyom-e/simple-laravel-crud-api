<?php

namespace Tests\Feature;

use App\Http\Controllers\TaskController;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_empty(): void
    {
        $this->getJson(action([TaskController::class, 'index']))
            ->assertOk()
            ->assertJsonFragment([
                'data' => [],
                'total' => 0,
            ]);
    }

    public function test_index_without_completed(): void
    {
        $tasks = Task::factory()->count(5)->incompleted()->create()->sortByDesc('id');
        Task::factory()->count(2)->completed()->create();
        $this->getJson(action([TaskController::class, 'index']))
            ->assertOk()
            ->assertJsonFragment([
                'data' => TaskResource::collection($tasks)->resolve(),
                'total' => 5,
            ]);
    }

    public function test_index_with_completed(): void
    {
        $task1 = Task::factory()->incompleted()->create();
        $task2 = Task::factory()->completed()->create();
        $this->getJson(action([TaskController::class, 'index'], ['filters' => ['include_completed' => true]]))
            ->assertOk()
            ->assertJsonFragment([
                'data' => TaskResource::collection([$task2, $task1])->resolve(),
                'total' => 2,
            ]);
    }

    public function test_show_not_found(): void
    {
        $this->getJson(action([TaskController::class, 'show'], ['task' => $this->faker->numberBetween(1, 100)]))
            ->assertNotFound();
    }

    public function test_show(): void
    {
        $task = Task::factory()->create();
        $this->getJson(action([TaskController::class, 'show'], ['task' => $task->id]))
            ->assertOk()
            ->assertJson([
                'data' => TaskResource::make($task)->resolve(),
            ]);
    }

    public function test_destroy_not_found(): void
    {
        $this->deleteJson(action([TaskController::class, 'destroy'], ['task' => $this->faker->numberBetween(1, 100)]))
            ->assertNotFound();
    }

    public function test_destroy(): void
    {
        $task = Task::factory()->create();
        $this->deleteJson(action([TaskController::class, 'destroy'], ['task' => $task->id]))
            ->assertNoContent();
    }

    public function test_store_with_validation_errors(): void
    {
        // todo implement this test
        $this->markTestIncomplete('To be implemented');
    }

    public function test_store(): void
    {
        // todo implement this test
        $this->markTestIncomplete('To be implemented');
    }

    public function test_update_with_validation_errors(): void
    {
        // todo implement this test
        $this->markTestIncomplete('To be implemented');
    }

    public function test_update(): void
    {
        // todo implement this test
        $this->markTestIncomplete('To be implemented');
    }
}
