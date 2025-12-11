<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($this->admin);
    }

    public function test_index_displays_projects()
    {
        $project = Project::factory()->create();

        $response = $this->get(route('admin.projects.index'));

        $response->assertStatus(200);
        $response->assertSee($project->name);
    }

    public function test_store_creates_project()
    {
        $client = Client::factory()->create();

        $data = [
            'name' => 'New Project',
            'client_id' => $client->id,
            'status' => 'active',
        ];

        $response = $this->post(route('admin.projects.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('projects', $data);
    }

    public function test_update_edits_project()
    {
        $project = Project::factory()->create();

        $response = $this->put(route('admin.projects.update', $project), [
            'name' => 'Updated',
            'status' => 'completed',
            'client_id' => $project->client_id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Updated']);
    }

    public function test_destroy_deletes_project()
    {
        $project = Project::factory()->create();

        $response = $this->delete(route('admin.projects.destroy', $project));

        $response->assertRedirect();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}
