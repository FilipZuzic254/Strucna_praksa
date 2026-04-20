<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\PressureReading;
use App\Models\InventoryItem;
use Exception;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GeneratePressure implements ShouldQueue
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
        $pressure = $this->callPressureApi();
        $isFaulty = $pressure < 40 || $pressure > 80;

        PressureReading::create([
            'inventory_item_id' => $this->item->id,
            'pressure' => $pressure,
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

            $consecutiveFaulty = PressureReading::where('inventory_item_id', $this->item->id)
                ->latest()
                ->limit(3)
                ->get()
                ->every(function ($reading) {
                    return $reading->is_faulty;
                });
            
            if ($consecutiveFaulty) {
                $item->status = 'faulty';
                $item->save();

                Log::warning("Inventory item $item->product_name SN {$item->serial_number} marked as faulty due to 3 consecutive invalid pressure readings.");
            }
        });
    }

    private function callPressureApi()
    {
        if (rand(1, 100) <= 5) {
            throw new Exception('Pressure API failed');
        }

        $pressureRange = rand(0, 100);

        if ($pressureRange < 80) {
            $pressure = rand(40, 80);
        } else {
            $pressure = rand(0, 4) > 2 ? rand(81, 100) : rand(0, 39);
        }
        
        return $pressure;
    }

    public function failed(?Throwable $exception): void
    {
        Log::warning("Failed to fetch value from API for Inventory Item ID {$this->item->id}: " . $exception?->getMessage());
    }
}
