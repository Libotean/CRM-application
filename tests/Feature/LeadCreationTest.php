<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class LeadCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_consilier_can_create_a_lead()
    {
        // 1. ARRANGE
        // Cream un consilier si ne logam cu el
        $consilier = User::factory()->create(['role' => 'user']);
        
        // Cream un client alocat acestui consilier
        $client = Client::factory()->create(['user_id' => $consilier->id]);

        // 2. ACT
        // Simulam ca suntem logati
        $response = $this->actingAs($consilier)
                         ->post(route('consilier.leads.store', $client->id), [
                             'appointment_date' => '2025-12-01',
                             'appointment_time' => '14:00',
                             'method' => 'Telefon',
                             'objective' => 'Oferta',
                             'notes' => 'Clientul este interesat.'
                         ]);

        // 3. ASSERT
        // Verificam ca nu am primit eroare si ne-a trimis inapoi (redirect back)
        $response->assertRedirect();
        $response->assertSessionHas('success'); // Verificam mesajul flash

        // Verificam in baza de date daca exista lead-ul
        $this->assertDatabaseHas('leads', [
            'client_id' => $client->id,
            'user_id' => $consilier->id,
            'method' => 'Telefon',
            'objective' => 'Oferta',
            // Atentie: data e salvata combinat in controller
            'appointment_date' => '2025-12-01 14:00:00', 
        ]);
    }
}