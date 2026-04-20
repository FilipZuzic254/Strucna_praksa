<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\GenerateTemperature;
use App\Jobs\GeneratePressure;
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
        $inventoryItems = InventoryItem::where('status', 'delivered')
            ->orWhere('status', 'replaced')
            ->get();

        foreach ($inventoryItems as $item) {
            GenerateTemperature::dispatch($item);
            GeneratePressure::dispatch($item);
        }
    }
}
