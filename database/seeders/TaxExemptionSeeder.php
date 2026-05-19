<?php

namespace Database\Seeders;

use App\Models\TaxExemption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaxExemptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('seeders/data/razlozi_oslobodenja.csv'), 'r');
        
        // skip header row
        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            TaxExemption::insert([
                'code' => $row[0],
                'description' => $row[1],
            ]);
        }

        fclose($file);
    }
}
