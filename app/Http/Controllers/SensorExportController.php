<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\TemperatureReading;
use App\Models\PressureReading;

class SensorExportController extends Controller
{
    public function export(InventoryItem $inventoryItem)
    {
        $temperature = TemperatureReading::where('inventory_item_id', $inventoryItem->id)
            ->select('temperature', 'is_faulty', 'created_at')
            ->latest()
            ->get();
        
        $pressure = PressureReading::where('inventory_item_id', $inventoryItem->id)
            ->select('pressure', 'is_faulty', 'created_at')
            ->latest()
            ->get();
        
        $filename = 'sensor_export_' . $inventoryItem->id . '_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($temperature, $pressure) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['--- Temperature Readings ---']);
            fputcsv($handle, ['Temperature', 'Is Faulty', 'Recorded At']);
            foreach ($temperature as $reading) {
                fputcsv($handle, [
                    $reading->temperature,
                    $reading->is_faulty ? 'Yes' : 'No',
                    $reading->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fputcsv($handle, []); 

            fputcsv($handle, ['--- Pressure Readings ---']);
            fputcsv($handle, ['Pressure', 'Is Faulty', 'Recorded At']);
            foreach ($pressure as $reading) {
                fputcsv($handle, [
                    $reading->pressure,
                    $reading->is_faulty ? 'Yes' : 'No',
                    $reading->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
