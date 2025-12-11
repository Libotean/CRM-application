<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUserControllerTest extends TestCase
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

    public function test_index_displays_users()
    {
        $user = User::factory()->create();

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertSee($user->firstname);
    }

    public function test_store_creates_user()
    {
        $data = [
            'firstname' => 'Ana',
            'lastname' => 'Pop',
            'email' => 'ana@test.com',
            'password' => 'password',
            'role' => 'employee',
            'is_active' => true,
        ];

        $response = $this->post(route('admin.users.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('users', [
            'email' => 'ana@test.com',
            'role' => 'employee',
        ]);
    }

    public function test_update_edits_user()
    {
        $user = User::factory()->create();

        $response = $this->put(route('admin.users.update', $user), [
            'firstname' => 'Updated',
            'lastname' => $user->lastname,
            'email' => $user->email,
            'role' => $user->role,
            'is_active' => $user->is_active,
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'firstname' => 'Updated',
        ]);
    }

    public function test_destroy_deletes_user()
    {
        $user = User::factory()->create();

        $response = $this->delete(route('admin.users.destroy', $user));

        $response->assertRedirect();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
