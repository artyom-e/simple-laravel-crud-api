<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\TaskController;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIndexEmpty(): void
    {
        $this->getJson(action([TaskController::class, 'index']))
            ->assertOk()
            ->assertJsonFragment([
                'data' => [],
                'total' => 0,
            ]);
    }

    public function testIndexWithoutCompleted(): void
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

    public function testIndexWithCompleted(): void
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

    public function testShowNotFound(): void
    {
        $this->getJson(action([TaskController::class, 'show'], ['task' => $this->faker->numberBetween(1, 100)]))
            ->assertNotFound();
    }

    public function testShow(): void
    {
        $task = Task::factory()->create();
        $this->getJson(action([TaskController::class, 'show'], ['task' => $task]))
            ->assertOk()
            ->assertJson([
                'data' => TaskResource::make($task)->resolve(),
            ]);
    }

    public function testDestroyNotFound(): void
    {
        $this->deleteJson(action([TaskController::class, 'destroy'], ['task' => $this->faker->numberBetween(1, 100)]))
            ->assertNotFound();
    }

    public function testDestroy(): void
    {
        $task = Task::factory()->create();
        $this->deleteJson(action([TaskController::class, 'destroy'], ['task' => $task]))
            ->assertNoContent();
    }

    #[DataProvider('storeValidationErrorsProvider')]
    public function testStoreWithValidationErrors(callable $payloadGenerator, array $validationErrors): void
    {
        $this->postJson(action([TaskController::class, 'store']), $payloadGenerator($this->faker))
            ->assertJsonValidationErrors($validationErrors);
    }

    public function testStore(): void
    {
        $name = $this->faker->sentence(3);
        $description = $this->faker->optional()->paragraph();
        $this->postJson(action([TaskController::class, 'store']), [
            'name' => $name,
            'description' => $description,
        ])->assertCreated();
    }

    #[DataProvider('updateValidationErrorsProvider')]
    public function testUpdateWithValidationErrors(callable $payloadGenerator, array $validationErrors): void
    {
        $task = Task::factory()->create();
        $this->putJson(action([TaskController::class, 'update'], ['task' => $task]), $payloadGenerator($this->faker))
            ->assertJsonValidationErrors($validationErrors);
    }

    public function testUpdate(): void
    {
        $task = Task::factory()->incompleted()->create();
        $name = $this->faker->sentence(3);
        $description = $this->faker->optional()->paragraph();
        $response = $this->putJson(action([TaskController::class, 'update'], ['task' => $task]), [
            'name' => $name,
            'description' => $description,
            'is_completed' => !$task->is_completed,
        ])->assertOk()
            ->json('data');
        $this->assertEquals($task->id, $response['id']);
        $this->assertEquals($name, $response['name']);
        $this->assertEquals($description, $response['description']);
        $this->assertEquals(!$task->is_completed, $response['is_completed']);
    }

    public static function storeValidationErrorsProvider(): array
    {
        return [
            'name field is required' => [
                fn(Generator $faker) => [], // payload
                ['name'], // validation errors
            ],
            'name must be less than or equal to 255' => [
                fn(Generator $faker) => [
                    'name' => $faker->realTextBetween(256, 300),
                ], // payload
                ['name'], // validation errors
            ],
            'description must be less than or equal to 2048' => [
                fn(Generator $faker) => [
                    'description' => $faker->realTextBetween(2049, 4098),
                ], // payload
                ['description'], // validation errors
            ],
        ];
    }

    public static function updateValidationErrorsProvider(): array
    {
        return [
            'name and is_completed fields are required' => [
                fn(Generator $faker) => [], // payload
                ['name', 'is_completed'], // validation errors
            ],
            'name must be less than or equal to 255' => [
                fn(Generator $faker) => [
                    'name' => $faker->realTextBetween(256, 300),
                ], // payload
                ['name'], // validation errors
            ],
            'description must be less than or equal to 2048' => [
                fn(Generator $faker) => [
                    'description' => $faker->realTextBetween(2049, 4098),
                ], // payload
                ['description'], // validation errors
            ],
            'is_completed must be a boolean' => [
                fn(Generator $faker) => [
                    'is_completed' => $faker->word(),
                ], // payload
                ['is_completed'], // validation errors
            ],
        ];
    }
}
