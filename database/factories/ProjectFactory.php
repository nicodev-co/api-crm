<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_manager_id' => User::all()->random()->id,
            'name' => fake()->sentence(2),
            'description' => fake()->text(),
            'status' => fake()->boolean(3),
            'start_date' => fake()->dateTime(),
            'end_date' => fake()->dateTime(now()->addMonths(3)),
            'name_tasks' => 'Tasks',
        ];
    }
}
