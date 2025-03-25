<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\TaskList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskList>
 */
class TaskListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->sentence(),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}
