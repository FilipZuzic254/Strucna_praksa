<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\KpdCode;
use App\Models\TaxExemption;
use App\Models\InventoryItem;
use App\Models\ProductSensor;

use Illuminate\Support\Facades\Log;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = fopen(database_path('seeders/data/dataset_proizvodi.csv'), 'r');

        fgetcsv($file);

        while (($row = fgetcsv($file)) !== false) {
            $name = trim($row[0]);
            $sku = trim($row[1]);
            $kpdCode = trim($row[2]);
            $unitPrice = trim($row[3]);
            $unitOfMeasure = trim($row[4]);
            $discount = (int) trim($row[5]);
            $taxRate = (int) trim($row[6]);
            $taxExemptionCode = trim($row[7]);
            $description = trim($row[9]);

            if ($sku === '') {
                $sku = fake()->unique()->bothify('???-###');
            }
            
            if (preg_match('/^\d{2}\.\d{2}\.\d{2}$/', $kpdCode)) {
                $kpdCodeId = KpdCode::where('code', $kpdCode)->first()->id ?? null;
            }

            if ($unitPrice) {
                $unitPrice = floatval(str_replace(',', '.', str_replace('.', '', $unitPrice)));
            }
            else {
                $unitPrice = 0;
            }

            if (!in_array($unitOfMeasure, ['piece', 'kg', 'l', 'month', 'day', 'hour'])) {
                $unitOfMeasure = 'piece';
            }

            if ($taxRate === 0) {
                $exemptionId = TaxExemption::where('code', $taxExemptionCode)->first()->id ?? null;
            }
            else {
                $exemptionId = null;
            }

            Product::insert([
                'name' => $name,
                'sku' => $sku,
                'kpd_code_id' => $kpdCodeId,
                'unit_price' => $unitPrice,
                'unit_of_measure' => $unitOfMeasure,
                'discount' => $discount,
                'tax_rate' => $taxRate,
                'tax_exemption_id' => $exemptionId,
                'description' => $description,
                'warranty_months' => fake()->randomElement([6, 12, 24, 36, 48, 60]),
            ]);
        }

        fclose($file);

        $this->call(ProductComponentSeeder::class);

        Product::get()
            ->each(function ($product) {
                ProductSensor::factory()->count(rand(1, 3))->for($product)->create();
                InventoryItem::factory()->count(rand(2, 3))->for($product)->inStock()->create();
                InventoryItem::factory()->count(rand(2, 3))->for($product)->delivered()->create();
                InventoryItem::factory()->count(rand(1, 2))->for($product)->replaced()->create();
                InventoryItem::factory()->count(rand(1, 2))->for($product)->faulty()->create();
            });
    }
}
