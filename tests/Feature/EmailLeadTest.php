<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactClientMail;
use Tests\TestCase;
use App\Models\User;
use App\Models\Client;

class EmailLeadTest extends TestCase
{
    use RefreshDatabase;

    public function test_sending_email_creates_interaction_automatically()
    {
        // Oprim trimiterea reala a mailurilor
        Mail::fake();

        // 1. ARRANGE
        $consilier = User::factory()->create(['role' => 'user']);
        $client = Client::factory()->create(['user_id' => $consilier->id, 'email' => 'test@client.com']);

        // 2. ACT
        $response = $this->actingAs($consilier)
                         ->post(route('consilier.leads.sendEmail', $client->id), [
                             'subject' => 'Ofertă Specială',
                             'message' => 'Bună ziua, avem o ofertă...'
                         ]);

        // 3. ASSERT
        $response->assertSessionHas('succes'); // Sau 'success', cum ai pus in controller

        // A. Verificam daca s-a incercat trimiterea mailului
        Mail::assertSent(ContactClientMail::class, function ($mail) use ($client) {
            return $mail->hasTo($client->email) &&
                   $mail->detalii['subiect'] === 'Ofertă Specială';
        });

        // B. Verificam daca s-a creat automat Lead-ul in baza de date (FR-13)
        $this->assertDatabaseHas('leads', [
            'client_id' => $client->id,
            'method' => 'Email',
            'is_completed' => true, // Trebuie sa fie marcat ca finalizat
        ]);
    }
}