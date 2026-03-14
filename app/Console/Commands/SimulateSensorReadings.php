<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SensorReading;
use App\Models\InventoryItem;

class SimulateSensorReadings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sensor:simulate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simulate sensor readings for inventory items that are delivered but not yet marked as faulty.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Simulating sensor readings...');

        $inventoryItems = InventoryItem::where('status', 'delivered')->orWhere('status', 'replaced')->get();

        foreach ($inventoryItems as $item) {
            $temperature = $this->randomTemperature();
            $pressure = $this->randomPressure();

            SensorReading::create([
                'inventory_item_id' => $item->id,
                'temperature' => $temperature,
                'pressure' => $pressure,
            ]);

            $this->info("Simulated reading for Inventory Item ID {$item->id}: Temperature = {$temperature}, Pressure = {$pressure}");
        }

        $this->info('Simulation completed.');
    }

    function randomTemperature()
    {
        $temperatureRange = rand(0, 100);

        if ($temperatureRange < 80) {
            $temperature = rand(5, 38);
        } else {
            $temperature = rand(0, 4) > 2 ? rand(39, 100) : rand(0, 4);
        }
        
        return $temperature;
    }

    function randomPressure()
    {
        $pressureRange = rand(0, 100);

        if ($pressureRange < 80) {
            $pressure = rand(40, 80);
        } else {
            $pressure = rand(0, 4) > 2 ? rand(81, 100) : rand(0, 39);
        }

        return $pressure;
    }
}
