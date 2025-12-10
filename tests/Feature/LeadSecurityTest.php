<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;
use App\Models\Lead;

class LeadSecurityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function un_consilier_nu_poate_modifica_leadul_altui_coleg()
    {
        // 1. ARRANGE
        // Cream Consilierul A (Agresorul)
        $consilierA = User::factory()->create(['role' => 'user']);
        
        // Cream Consilierul B (Victima)
        $consilierB = User::factory()->create(['role' => 'user']);
        
        // Cream un Client si un Lead care apartin lui B
        $clientB = Client::factory()->create(['user_id' => $consilierB->id]);
        $leadB = Lead::create([
            'client_id' => $clientB->id,
            'user_id' => $consilierB->id, // Lead-ul este al lui B
            'appointment_date' => now(),
            'method' => 'Telefon',
            'objective' => 'Oferta',
            'is_completed' => false
        ]);

        // 2. ACT
        // Ne logam ca Consilierul A si incercam sa modificam statusul lead-ului lui B
        $response = $this->actingAs($consilierA)
                         ->patch(route('consilier.leads.toggle', $leadB->id));

        // 3. ASSERT
        // Trebuie sa primim 403 FORBIDDEN
        $response->assertStatus(403);
        
        // Verificam in baza de date ca statusul NU s-a schimbat
        $this->assertDatabaseHas('leads', [
            'id' => $leadB->id,
            'is_completed' => false,
        ]);
    }
}