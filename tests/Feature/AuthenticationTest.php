<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    // Testam ca un vizitator neautentificat nu poate vedea lista de clienti
    public function test_guests_are_redirected_to_login()
    {
        // Incercam sa accesam ruta fara actingAs()
        $response = $this->get(route('consilier.clients.index'));

        // Trebuie sa ne trimita la login
        $response->assertRedirect('/login');
    }
    
    // Testam si pentru admin
    public function test_guests_cannot_see_users_list()
    {
        $response = $this->get(route('admin.users.index'));
        $response->assertRedirect('/login');
    }
}