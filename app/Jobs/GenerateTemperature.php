<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\TemperatureReading;
use App\Models\InventoryItem;
use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GenerateTemperature implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    /**
     * Create a new job instance.
     */
    public function __construct(public InventoryItem $item)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $temperature = $this->callTemperatureApi();
        $isFaulty = $temperature < 5 || $temperature > 38;

        TemperatureReading::create([
            'inventory_item_id' => $this->item->id,
            'temperature' => $temperature,
            'is_faulty' => $isFaulty,
        ]);

        if ($isFaulty) {
            $this->checkConsecutiveFaulty(); 
        }
    }

    private function checkConsecutiveFaulty()
    {
        DB::transaction(function() {
            $item = InventoryItem::where('id', $this->item->id)
                ->lockForUpdate()
                ->first();

            if ($item->status === 'faulty') {
                return;
            }

            $consecutiveFaulty = TemperatureReading::where('inventory_item_id', $this->item->id)
                ->latest()
                ->limit(3)
                ->get()
                ->every(function ($reading) {
                    return $reading->is_faulty;
                });
            
            if ($consecutiveFaulty) {
                $item->status = 'faulty';
                $item->save();

                Log::warning("Inventory item $item->product_name SN {$item->serial_number} marked as faulty due to 3 consecutive invalid temperature readings.");
            }
        });
    }

    private function callTemperatureApi()
    {
        if (rand(1, 100) <= 5) {
            throw new Exception('Temperature API failed');
        }

        $temperatureRange = rand(0, 100);

        if ($temperatureRange < 80) {
            $temperature = rand(5, 38);
        } else {
            $temperature = rand(0, 4) > 2 ? rand(39, 100) : rand(0, 4);
        }
        
        return $temperature;
    }

    public function failed(?Throwable $exception): void
    {
        Log::warning("Failed to fetch value from API for Inventory Item ID {$this->item->id}: " . $exception?->getMessage());
    }
}
