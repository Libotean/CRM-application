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

    /** @test **/
    public function a_user_cannot_modify_leads_that_are_not_his()
    {

        $consilierA = User::factory()->create(['role' => 'user']);
        
        $consilierB = User::factory()->create(['role' => 'user']);
        
        $clientB = Client::factory()->create(['user_id' => $consilierB->id]);
        $leadB = Lead::create([
            'client_id' => $clientB->id,
            'user_id' => $consilierB->id, 
            'appointment_date' => now(),
            'method' => 'Telefon',
            'objective' => 'Oferta',
            'is_completed' => false
        ]);

        $response = $this->actingAs($consilierA)
                         ->patch(route('consilier.leads.toggle', $leadB->id));

        $response->assertStatus(403);
        
        $this->assertDatabaseHas('leads', [
            'id' => $leadB->id,
            'is_completed' => false,
        ]);
    }
}