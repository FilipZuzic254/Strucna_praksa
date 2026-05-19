<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\InventoryItem;
use App\Models\SensorReading;
use App\Models\ProductSensor;
use Exception;
use Throwable;

class GenerateSensorReading implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public InventoryItem $item,
        public ProductSensor $sensor
    )
    {   }

    public function handle(): void
    {
        $min = $this->sensor->min_value;
        $max = $this->sensor->max_value;

        $sensorReading = $this->callSensorApi($min, $max);
        $isFaulty = $sensorReading < $min || $sensorReading > $max;

        SensorReading::create([
            'inventory_item_id' => $this->item->id,
            'product_sensor_id' => $this->sensor->id,
            'value' => $sensorReading,
        ]);

        if ($isFaulty) {
            $this->checkConsecutiveFaulty(); 
        }

    }

    private function checkConsecutiveFaulty()
    {
        $faultyStrikes = 0;
        $minValue = $this->sensor->min_value;
        $maxValue = $this->sensor->max_value;

        $item = InventoryItem::where('id', $this->item->id)
                ->lockForUpdate()
                ->first();

        if ($item->status === 'faulty') {
            return;
        }

        $consecutiveReadings = SensorReading::where('inventory_item_id', $this->item->id)
            ->where('product_sensor_id', $this->sensor->id)
            ->latest()
            ->limit(3)
            ->get();

        foreach ($consecutiveReadings as $reading) {
            if ($reading->value < $minValue || $reading->value > $maxValue) {
                $faultyStrikes++;
                Log::info("Reading value {$reading->value} is outside range for Sensor name {$this->sensor->sensor->name}. Number of strikes: {$faultyStrikes}");
            }
        }

        if ($faultyStrikes === 3) {
            $item->status = 'faulty';
            $item->save();

            Log::warning("Inventory item $item->product_name SN {$item->serial_number} marked as faulty due to 3 consecutive invalid readings for sensor {$this->sensor->sensor->name}.");
        }

    }

    private function callSensorApi($min, $max)
    {
        if (rand(1, 100) <= 5) {
            throw new Exception('Sensor API failed');
        }

        $pressureRange = rand(0, 100);

        if ($pressureRange < 80) {
            $pressure = rand($min, $max);
        } else {
            $pressure = rand(0, 4) > 2 ? rand($max+1, 100) : rand(0, $min-1);
        }
        
        return $pressure;
    }

    public function failed(?Throwable $exception): void
    {
        Log::warning("Failed to fetch value from API for Inventory Item ID {$this->item->id}: " . $exception?->getMessage());
    }


}
