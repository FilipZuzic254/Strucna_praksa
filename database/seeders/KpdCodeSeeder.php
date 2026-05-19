<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KpdCode;

class KpdCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('seeders/data/KPD_2025_struktura.csv'), 'r');
        
        // skip header row
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $code = trim($row[0]);
            $name = trim($row[1]);

            if (!preg_match('/^\d{2}\.\d{2}\.\d{2}$/', $code)) {
                continue;
            }

            KpdCode::insert([
                'code' => $code,
                'name' => $name,
            ]);
        }

        fclose($file);
    }
}
