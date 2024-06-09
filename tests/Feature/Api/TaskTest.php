<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test index.
     */
    public function test_index(): void
    {
        Sanctum::actingAs(User::factory()->create());

        User::factory(10)->create();
        Project::factory(5)->create();
        Task::factory(10)->create();

        $response = $this->getJson('api/tasks');

        $response->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    [
                        'id',
                        'type',
                        'attributes' => [
                            'name',
                            'description',
                            'start_date',
                            'end_date',
                            'status',
                        ],
                        'relationships' => [
                            'users' => [],
                        ],
                    ],
                ],
                'links' => [],
                'meta' => [],
            ]);
    }

    public function test_store(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $project = Project::factory()->create();
        $users = User::factory(3)->create();

        $data = [
            'project_id' => $project->id,
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'status' => $this->faker->boolean,
            'users_id' => $users->pluck('id'),
        ];

        $response = $this->postJson('api/tasks', $data);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_show(): void
    {
        Sanctum::actingAs(User::factory()->create());

        User::factory(5)->create();
        Project::factory()->create();
        $task = Task::factory()->create();

        $response = $this->getJson("api/tasks/$task->id");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'attributes' => [
                        'name',
                        'description',
                        'start_date',
                        'end_date',
                        'status',
                    ],
                    'relationships' => [],
                ],
            ]);
    }

    public function test_update(): void
    {
        Sanctum::actingAs(User::factory()->create());

        User::factory(3)->create();
        $project = Project::factory()->create();
        $task = Task::factory()->create();
        $users = User::all()->random(rand(1, 3));

        $data = [
            'project_id' => $project->id,
            'name' => 'Updated name',
            'description' => 'Updated description',
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'status' => $this->faker->boolean,
            'users_id' => $users->pluck('id'),
        ];

        $response = $this->putJson("api/tasks/$task->id", $data);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_destroy(): void
    {
        Sanctum::actingAs(User::factory()->create());

        User::factory(5)->create();
        Project::factory()->create();
        $task = Task::factory()->create();

        $response = $this->deleteJson("api/tasks/$task->id");

        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);

        $response->assertNoContent();
    }
}
