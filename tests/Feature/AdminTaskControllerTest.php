<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminTaskControllerTest extends TestCase
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

    public function test_index_displays_tasks()
    {
        $task = Task::factory()->create();

        $response = $this->get(route('admin.tasks.index'));

        $response->assertStatus(200);
        $response->assertSee($task->title);
    }

    public function test_store_creates_task()
    {
        $project = Project::factory()->create();

        $data = [
            'title' => 'New Task',
            'project_id' => $project->id,
            'status' => 'open',
        ];

        $response = $this->post(route('admin.tasks.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', $data);
    }

    public function test_update_edits_task()
    {
        $task = Task::factory()->create();

        $response = $this->put(route('admin.tasks.update', $task), [
            'title' => 'Updated Task',
            'status' => 'closed',
            'project_id' => $task->project_id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated Task',
        ]);
    }

    public function test_destroy_deletes_task()
    {
        $task = Task::factory()->create();

        $response = $this->delete(route('admin.tasks.destroy', $task));

        $response->assertRedirect();
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }
}
