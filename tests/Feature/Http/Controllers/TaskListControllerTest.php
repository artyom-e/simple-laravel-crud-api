<?php

declare(strict_types=1);

namespace Feature\Http\Controllers;

use App\Http\Controllers\TaskListController;
use App\Http\Resources\TaskListResource;
use App\Models\TaskList;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TaskListControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIndexEmpty(): void
    {
        $this->getJson(action([TaskListController::class, 'index']))
            ->assertOk()
            ->assertJsonFragment([
                'data' => [],
                'total' => 0,
            ]);
    }

    public function testIndexWithoutCompleted(): void
    {
        $lists = TaskList::factory()->count(5)->create()->sortByDesc('id');
        $this->getJson(action([TaskListController::class, 'index']))
            ->assertOk()
            ->assertJsonFragment([
                'data' => TaskListResource::collection($lists)->resolve(),
                'total' => 5,
            ]);
    }

    public function testShowNotFound(): void
    {
        $this->getJson(action([TaskListController::class, 'show'], ['list' => $this->faker->numberBetween(1, 100)]))
            ->assertNotFound();
    }

    public function testShow(): void
    {
        $list = TaskList::factory()->create();
        $this->getJson(action([TaskListController::class, 'show'], ['list' => $list]))
            ->assertOk()
            ->assertJson([
                'data' => TaskListResource::make($list)->resolve(),
            ]);
    }

    public function testDestroyNotFound(): void
    {
        $this->deleteJson(action([TaskListController::class, 'destroy'], ['list' => $this->faker->numberBetween(1, 100)]))
            ->assertNotFound();
    }

    public function testDestroy(): void
    {
        $list = TaskList::factory()->create();
        $this->deleteJson(action([TaskListController::class, 'destroy'], ['list' => $list]))
            ->assertNoContent();
    }

    #[DataProvider('storeValidationErrorsProvider')]
    public function testStoreWithValidationErrors(callable $payloadGenerator, array $validationErrors): void
    {
        $this->postJson(action([TaskListController::class, 'store']), $payloadGenerator($this->faker))
            ->assertJsonValidationErrors($validationErrors);
    }

    public function testStore(): void
    {
        $name = $this->faker->sentence(3);
        $description = $this->faker->optional()->paragraph();
        $this->postJson(action([TaskListController::class, 'store']), [
            'name' => $name,
            'description' => $description,
        ])->assertCreated();
    }

    #[DataProvider('updateValidationErrorsProvider')]
    public function testUpdateWithValidationErrors(callable $payloadGenerator, array $validationErrors): void
    {
        $list = TaskList::factory()->create();
        $this->putJson(action([TaskListController::class, 'update'], ['list' => $list]), $payloadGenerator($this->faker))
            ->assertJsonValidationErrors($validationErrors);
    }

    public function testUpdate(): void
    {
        $list = TaskList::factory()->create();
        $name = $this->faker->sentence(3);
        $description = $this->faker->optional()->paragraph();
        $response = $this->putJson(action([TaskListController::class, 'update'], ['list' => $list]), [
            'name' => $name,
            'description' => $description,
        ])->assertOk()
            ->json('data');
        $this->assertEquals($list->id, $response['id']);
        $this->assertEquals($name, $response['name']);
        $this->assertEquals($description, $response['description']);
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
            'name fields are required' => [
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
}
