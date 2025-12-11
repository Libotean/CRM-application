<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'user_id'   => User::factory(),
            'type'      => $this->faker->randomElement(['pf', 'pj']),
            'firstname' => $this->faker->firstName(),
            'lastname'  => $this->faker->lastName(),
            'cnp'       => $this->faker->numerify('###########'), // 13 cifre
            'cui'       => $this->faker->numerify('########'),
            'tva_payer' => $this->faker->boolean(),
            'email'     => $this->faker->safeEmail(),
            'phone'     => $this->faker->phoneNumber(),
            'country'   => $this->faker->country(),
            'county'    => $this->faker->state(),
            'locality'  => $this->faker->city(),
            'address'   => $this->faker->address(),
            'status'    => $this->faker->randomElement(['active', 'inactive']),
        ];
    }
}
