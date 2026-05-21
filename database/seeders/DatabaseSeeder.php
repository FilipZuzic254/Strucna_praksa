<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Marin Pavlic',
            'email' => 'marin.pavlic@vodissima.hr',
            'password' => 'admin',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Shop manager',
            'email' => 'shop.manager@vodissima.hr',
            'password' => 'test',
            'role' => 'shop_manager',
        ]);

        $this->call([
            KpdCodeSeeder::class,
            TaxExemptionSeeder::class,
            ClientSeeder::class,
            DeliverySeeder::class,
            SensorSeeder::class,
            ComponentSeeder::class,
            GallerySeeder::class,
            ProductSeeder::class,
            DocumentSeeder::class,
        ]);
    }
}
