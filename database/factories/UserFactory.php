<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'firstname'  => $this->faker->firstName(),
            'lastname'   => $this->faker->lastName(),
            'country'    => $this->faker->country(),
            'county'     => $this->faker->state(),
            'locality'   => $this->faker->city(),
            'email'      => $this->faker->unique()->safeEmail(),
            'password'   => 'password', // hash automat prin cast
            'date_start' => now()->subDays(rand(1, 10)),
            'date_end'   => now()->addDays(rand(5, 30)),
            'role'       => $this->faker->randomElement(['admin', 'user']),
            'is_active'  => true,
        ];
    }

    public function inactive()
    {
        return $this->state(fn () => [
            'is_active' => false,
        ]);
    }
}
