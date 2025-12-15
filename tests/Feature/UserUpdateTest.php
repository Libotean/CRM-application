<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_becomes_inactive_automatically_if_end_date_is_past()
    {
        // 1. ARRANGE
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Cream un user activ
        $userTarget = User::factory()->create([
            'is_active' => true,
            'role' => 'user'
        ]);

        // 2. ACT
        // Adminul ii actualizeaza data de expirare la "ieri"
        $response = $this->actingAs($admin)
                         ->put(route('admin.users.update', $userTarget->id), [
                             'firstname' => 'NumeNou',
                             'lastname' => 'PrenumeNou',
                             'email' => $userTarget->email,
                             'role' => 'user',
                             'country' => 'Ro', 'county' => 'Buc', 'locality' => 'Sec1', // Campuri obligatorii
                             'date_start' => now()->subMonth(),
                             'date_end' => Carbon::yesterday(), 
                         ]);

        // 3. ASSERT
        $response->assertRedirect();
        
        // Verificam ca userul a devenit INACTIV in baza de date
        $this->assertDatabaseHas('users', [
            'id' => $userTarget->id,
            'is_active' => false, // Trebuie sa fie false (0)
        ]);
    }
}