<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminClientControllerTest extends TestCase
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

    public function test_index_displays_clients()
    {
        $client = Client::factory()->create();

        $response = $this->get(route('admin.clients.index'));

        $response->assertStatus(200);
        $response->assertSee($client->firstname);
    }

    public function test_store_creates_client()
    {
        $data = [
            'firstname' => 'Alex',
            'lastname' => 'Vint',
            'email' => 'alex@test.com',
        ];

        $response = $this->post(route('admin.clients.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('clients', $data);
    }

    public function test_update_edits_client()
    {
        $client = Client::factory()->create();

        $response = $this->put(route('admin.clients.update', $client), [
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'johndoe@test.com',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('clients', [
            'id' => $client->id,
            'firstname' => 'John',
        ]);
    }

    public function test_destroy_deletes_client()
    {
        $client = Client::factory()->create();

        $response = $this->delete(route('admin.clients.destroy', $client));

        $response->assertRedirect();
        $this->assertDatabaseMissing('clients', ['id' => $client->id]);
    }
}
