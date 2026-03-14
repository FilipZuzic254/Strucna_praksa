<?php

namespace App\Observers;

use App\Models\SensorReading;
use App\Models\InventoryItem;
use Illuminate\Support\Facades\Log;

class SensorReadingObserver
{
    /**
     * Handle the SensorReading "created" event.
     */
    public function created(SensorReading $sensorReading): void
    {
        $isTemperatureInvalid = $sensorReading->temperature > 38 || $sensorReading->temperature < 5;
        $isPressureInvalid = $sensorReading->pressure > 80 || $sensorReading->pressure < 40;

        if (!$isTemperatureInvalid && !$isPressureInvalid) {
            return;
        }

        $latestReadings = SensorReading::where('inventory_item_id', $sensorReading->inventory_item_id)
            ->latest()
            ->limit(3)
            ->get();
        
        $temperatureWarning = $latestReadings->filter(function ($reading) {
            return $reading->temperature > 38 || $reading->temperature < 5;
        })->count();

        $pressureWarning = $latestReadings->filter(function ($reading) {
            return $reading->pressure > 80 || $reading->pressure < 40;
        })->count();

        if ($temperatureWarning >= 3 || $pressureWarning >= 3) {

            $inventoryItem = InventoryItem::find($sensorReading->inventory_item_id);

            if ($inventoryItem) {
                $inventoryItem->status = 'faulty';
                $inventoryItem->save();

                Log::warning("Inventory item $inventoryItem->product_name SN {$inventoryItem->serial_number} marked as faulty due to 3 consecutive invalid sensor readings.");
            }
        }
    }

    /**
     * Handle the SensorReading "updated" event.
     */
    public function updated(SensorReading $sensorReading): void
    {
        //
    }

    /**
     * Handle the SensorReading "deleted" event.
     */
    public function deleted(SensorReading $sensorReading): void
    {
        //
    }

    /**
     * Handle the SensorReading "restored" event.
     */
    public function restored(SensorReading $sensorReading): void
    {
        //
    }

    /**
     * Handle the SensorReading "force deleted" event.
     */
    public function forceDeleted(SensorReading $sensorReading): void
    {
        //
    }
}
