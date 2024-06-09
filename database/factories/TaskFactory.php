<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
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
            'project_id' => Project::all()->random()->id,
            'name' => fake()->sentence(),
            'description' => fake()->text(),
            'start_date' => fake()->dateTime(),
            'end_date' => fake()->dateTime(now()->addMonths(3)),
            'status' => fake()->boolean(3),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Task $task) {
            $users = User::all()->random(rand(1, 3))->pluck('id');
            $task->users()->sync($users);
        });
    }
}
