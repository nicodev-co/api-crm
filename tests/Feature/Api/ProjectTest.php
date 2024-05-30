<?php

namespace Tests\Feature\Api;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test index.
     */
    public function test_index(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $projects = Project::factory(5)->create();

        $response = $this->getJson('api/projects');

        $response->assertJsonCount(5, 'data')
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
                            'name_tasks',
                        ],
                    ],
                ],
            ]);
    }

    public function test_store(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $data = [
            'project_manager_id' => $user->id,
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'status' => $this->faker->boolean,
            'name_tasks' => 'Tasks',
        ];

        $response = $this->postJson('api/projects', $data);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_show(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $response = $this->getJson("api/projects/$project->id");

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
                        'name_tasks',
                    ],
                ],
            ]);
    }

    public function test_update(): void 
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $project = Project::factory()->create();
        
        $data = [
            'project_manager_id' => $user->id,
            'name' => 'Updated name',
            'description' => 'Updated description',
            'start_date' => $this->faker->date,
            'end_date' => $this->faker->date,
            'status' => $this->faker->boolean,
            'name_tasks' => 'Tasks',
        ];

        $response = $this->putJson("api/projects/$project->id", $data);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_destroy(): void 
    {
        Sanctum::actingAs(User::factory()->create());

        $project = Project::factory()->create();

        $response = $this->deleteJson("api/projects/$project->id");

        $this->assertDatabaseMissing('projects',[
            'id' => $project->id
        ]);

        $response->assertNoContent();
    }
}
