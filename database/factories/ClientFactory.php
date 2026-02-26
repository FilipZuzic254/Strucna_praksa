<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ClientContact;
use App\Models\Client;

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
            'oib' => fake('hr_HR')->unique()->numerify('###########'),
            'phone' => fake('hr_HR')->phoneNumber(),
            'address' => fake('hr_HR')->address(),
        ];
    }

    public function company()
    {
        return $this->state(function () {
            return [
                'name' => fake('hr_HR')->company(),
                'type' => 'company',
                'email' => fake('hr_HR')->unique()->companyEmail(),
            ];
        })
        ->has(
            ClientContact::factory()->count(rand(1, 5)),
            'contacts'
        );
    }

    public function person()
    {
        return $this->state(function () {
            return [
                'name' => fake('hr_HR')->name(),
                'type' => 'person',
                'email' => fake('hr_HR')->unique()->safeEmail(),
            ];
        })->has(
            ClientContact::factory()->count(rand(1, 2)),
            'contacts'
        );
    }
}
