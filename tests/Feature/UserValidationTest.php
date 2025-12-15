<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Carbon\Carbon;

class UserValidationTest extends TestCase
{
    use RefreshDatabase; // Reseteaza baza de date dupa fiecare test

    public function test_required_fields_cannot_be_empty()
    {
        // 1. ARRANGE: Cream un admin pentru a avea acces la ruta
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. ACT: Trimitem un request POST gol catre store
        $response = $this->actingAs($admin)
                         ->post(route('admin.users.store'), []);

        // 3. ASSERT: Verificam ca avem erori pe campurile critice
        $response->assertSessionHasErrors([
            'firstname',
            'lastname',
            'email',
            'password',
            'role',
            'country',
            'county',
            'locality'
        ]);
    }

    public function test_cannot_create_user_with_duplicate_email()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        // Cream deja un user cu acest email in sistem
        User::factory()->create(['email' => 'gigel@atp-motors.ro']);

        // Incercam sa mai cream unul nou cu acelasi email
        $response = $this->actingAs($admin)
                         ->post(route('admin.users.store'), [
                             'firstname' => 'Alt',
                             'lastname' => 'Gigel',
                             'email' => 'gigel@atp-motors.ro', // DUPLICAT
                             'password' => 'password123',
                             'role' => 'user',
                             'country' => 'Romania',
                             'county' => 'Bucuresti',
                             'locality' => 'Sector 1',
                         ]);

        // Ne asteptam la eroare pe campul email
        $response->assertSessionHasErrors(['email']);
    }

    public function test_email_must_be_valid_format()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
                         ->post(route('admin.users.store'), [
                             'firstname' => 'Test',
                             'lastname' => 'User',
                             'email' => 'nu-este-un-email-valid', // FORMAT GRESIT
                             'password' => 'password123',
                             'role' => 'user',
                             'country' => 'Romania',
                             'county' => 'Cluj',
                             'locality' => 'Cluj-Napoca',
                         ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_must_be_at_least_8_characters()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
                         ->post(route('admin.users.store'), [
                             'firstname' => 'Test',
                             'lastname' => 'User',
                             'email' => 'test@valid.com',
                             'password' => 'scurta', // 6 caractere (PREA SCURTA)
                             'role' => 'user',
                             'country' => 'Romania',
                             'county' => 'Bihor',
                             'locality' => 'Oradea',
                         ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_role_must_be_valid_enum()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
                         ->post(route('admin.users.store'), [
                             'firstname' => 'Test',
                             'lastname' => 'User',
                             'email' => 'valid@email.com',
                             'password' => 'password123',
                             'role' => 'hacker', // ROL INEXISTENT
                             'country' => 'Romania',
                             'county' => 'Timis',
                             'locality' => 'Timisoara',
                         ]);

        $response->assertSessionHasErrors(['role']);
    }

    public function test_end_date_must_be_after_start_date()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)
                         ->post(route('admin.users.store'), [
                             'firstname' => 'Test',
                             'lastname' => 'User',
                             'email' => 'date@test.com',
                             'password' => 'password123',
                             'role' => 'user',
                             'country' => 'Romania',
                             'county' => 'Iasi',
                             'locality' => 'Iasi',
                             'date_start' => '2025-01-01',
                             'date_end'   => '2024-01-01', // ANTERIOARA datei de start (INVALID)
                         ]);

        $response->assertSessionHasErrors(['date_end']);
    }
}