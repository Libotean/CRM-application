<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Creaza automat un user daca nu e specificat
            'user_id' => \App\Models\User::factory(), 
            'firstname' => fake()->firstName(),
            'lastname' => fake()->lastName(),
            'type' => 'persoana_fizica',
            'email' => fake()->unique()->safeEmail(),
            'phone' => '0740123456',
            'tva_payer' => false,
            'status' => 'activ',
            'country' => 'Romania',
            'county' => 'Cluj',
            'locality' => 'Cluj-Napoca',
            'address' => 'Strada Testului nr 1',
        ];
    }
}