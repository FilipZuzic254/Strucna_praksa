<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake('hr_HR')->company(),
            'type' => 'company',
            'oib' => fake('hr_HR')->unique()->numerify('###########'),
            'email' => fake('hr_HR')->unique()->companyEmail(),
            'phone' => fake('hr_HR')->phoneNumber(),
            'address' => fake('hr_HR')->address(),
        ];
    }
}
