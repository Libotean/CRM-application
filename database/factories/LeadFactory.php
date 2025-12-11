<?php

namespace Database\Factories;

use App\Models\Lead;
use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    public function definition()
    {
        return [
            'client_id'        => Client::factory(),
            'user_id'          => User::factory(),
            'method'           => $this->faker->randomElement(['telefon', 'email', 'sms', 'whatsapp']),
            'objective'        => $this->faker->sentence(),
            'notes'            => $this->faker->paragraph(),
            'appointment_date' => $this->faker->dateTimeBetween('+1 day', '+10 days'),
            'is_completed'     => $this->faker->boolean(),
        ];
    }

    public function completed()
    {
        return $this->state(fn () => [
            'is_completed' => true,
        ]);
    }
}
