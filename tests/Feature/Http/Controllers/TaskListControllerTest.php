<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\TaskListController;
use App\Http\Resources\TaskListResource;
use App\Models\TaskList;
use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TaskListControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public function testIndexUnauthorized(): void
    {
        $this->getJson(action([TaskListController::class, 'index']))
            ->assertUnauthorized();
    }

    public function testIndexEmpty(): void
    {
        $this->actingAs(User::factory()->create());
        $this->getJson(action([TaskListController::class, 'index']))
            ->assertOk()
            ->assertJsonFragment([
                'data' => [],
                'total' => 0,
            ]);
    }

    public function testIndexWithoutCompleted(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $lists = TaskList::factory()->count(5)->for($user)->create()->sortByDesc('id');
        $this->getJson(action([TaskListController::class, 'index']))
            ->assertOk()
            ->assertJsonFragment([
                'data' => TaskListResource::collection($lists)->resolve(),
                'total' => 5,
            ]);
    }

    public function testIndexForAuthenticatedUser(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user1);
        $lists = TaskList::factory()->count(5)->for($user1)->create()->sortByDesc('id');
        TaskList::factory()->count(4)->for($user2)->create();
        $this->getJson(action([TaskListController::class, 'index']))
            ->assertOk()
            ->assertJsonFragment([
                'data' => TaskListResource::collection($lists)->resolve(),
                'total' => 5,
            ]);
    }

    public function testShowUnauthorized(): void
    {
        $list = TaskList::factory()->create();
        $this->getJson(action([TaskListController::class, 'show'], ['list' => $list]))
            ->assertUnauthorized();
    }

    public function testShowNotFound(): void
    {
        $this->actingAs(User::factory()->create());
        $this->getJson(action([TaskListController::class, 'show'], ['list' => $this->faker->numberBetween(1, 100)]))
            ->assertNotFound();
    }

    public function testShow(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $list = TaskList::factory()->for($user)->create();
        $this->getJson(action([TaskListController::class, 'show'], ['list' => $list]))
            ->assertOk()
            ->assertJson([
                'data' => TaskListResource::make($list)->resolve(),
            ]);
    }

    public function testShowForAnotherUser(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user2);
        $list = TaskList::factory()->for($user1)->create();
        $this->getJson(action([TaskListController::class, 'show'], ['list' => $list]))
            ->assertForbidden();
    }

    public function testDestroyUnauthorized(): void
    {
        $list = TaskList::factory()->create();
        $this->deleteJson(action([TaskListController::class, 'destroy'], ['list' => $list]))
            ->assertUnauthorized();
    }

    public function testDestroyNotFound(): void
    {
        $this->actingAs(User::factory()->create());
        $this->deleteJson(action([TaskListController::class, 'destroy'], ['list' => $this->faker->numberBetween(1, 100)]))
            ->assertNotFound();
    }

    public function testDestroy(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $list = TaskList::factory()->for($user)->create();
        $this->deleteJson(action([TaskListController::class, 'destroy'], ['list' => $list]))
            ->assertNoContent();
    }

    public function testDestroyForAnotherUser(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user2);
        $list = TaskList::factory()->for($user1)->create();
        $this->deleteJson(action([TaskListController::class, 'destroy'], ['list' => $list]))
            ->assertForbidden();
    }

    #[DataProvider('storeValidationErrorsProvider')]
    public function testStoreWithValidationErrors(callable $payloadGenerator, array $validationErrors): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson(action([TaskListController::class, 'store']), $payloadGenerator($this->faker))
            ->assertJsonValidationErrors($validationErrors);
    }

    public function testStore(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
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
        $user = User::factory()->create();
        $this->actingAs($user);
        $list = TaskList::factory()->for($user)->create();
        $this->putJson(action([TaskListController::class, 'update'], ['list' => $list]), $payloadGenerator($this->faker))
            ->assertJsonValidationErrors($validationErrors);
    }

    public function testUpdateUnauthorized(): void
    {
        $list = TaskList::factory()->create();
        $name = $this->faker->sentence(3);
        $description = $this->faker->optional()->paragraph();
        $this->putJson(action([TaskListController::class, 'update'], ['list' => $list]), [
            'name' => $name,
            'description' => $description,
        ])->assertUnauthorized();
    }

    public function testUpdate(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $list = TaskList::factory()->for($user)->create();
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

    public function testUpdateForAnotherUser(): void
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $this->actingAs($user2);
        $list = TaskList::factory()->for($user1)->create();
        $name = $this->faker->sentence(3);
        $description = $this->faker->optional()->paragraph();
        $this->putJson(action([TaskListController::class, 'update'], ['list' => $list]), [
            'name' => $name,
            'description' => $description,
        ])->assertForbidden();
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
