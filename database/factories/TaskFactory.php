<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskList;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'task_list_id' => TaskList::factory(),
            'name' => $this->faker->sentence(),
            'description' => $this->faker->optional()->paragraph(),
            'completed_at' => $this->faker->optional()->dateTimeBetween('-1 week'),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn(array $attributes) => [
            'completed_at' => $this->faker->dateTimeBetween('-1 week'),
        ]);
    }

    public function incompleted(): static
    {
        return $this->state(fn(array $attributes) => [
            'completed_at' => null,
        ]);
    }
}
