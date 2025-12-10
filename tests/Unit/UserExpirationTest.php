<?php

namespace Tests\Unit;

use Tests\TestCase; // ATENTIE: Foloseste Tests\TestCase, nu PHPUnit\Framework\TestCase pentru acces la DB
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class UserExpirationTest extends TestCase
{
    use RefreshDatabase; // Reseteaza baza de date dupa fiecare test

    public function test_it_deactivates_users_with_expired_dates()
    {
        // 1. ARRANGE (Pregatirea)
        // Cream un user care trebuia sa expire ieri
        $user = User::factory()->create([
            'is_active' => true,
            'date_end' => Carbon::yesterday(),
            'role' => 'user'
        ]);

        // 2. ACT (Actiunea)
        // Rulam functia statica pe care ai scris-o
        User::updateExpiredStatus();

        // 3. ASSERT (Verificarea)
        // Reincarcam userul din baza de date sa vedem modificarile
        $user->refresh();

        // Ne asteptam ca is_active sa fie acum false (0)
        $this->assertFalse((bool)$user->is_active);
    }
}