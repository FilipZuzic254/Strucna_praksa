<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Client;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client_contact>
 */
class ClientContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'client_id' => Client::factory(),
            'first_name' => fake('hr_HR')->firstName(),
            'last_name' => fake('hr_HR')->lastName(),
            'email' => fake('hr_HR')->unique()->companyEmail(),
            'position' => fake()->jobTitle(),
            'phone' => fake('hr_HR')->phoneNumber(),
        ];
    }
}
