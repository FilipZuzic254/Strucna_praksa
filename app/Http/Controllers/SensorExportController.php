<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\ProductSensor;
use App\Models\SensorReading;
use Illuminate\Http\Request;

class SensorExportController extends Controller
{
    public function export(InventoryItem $inventoryItem, Request $request)
    {

        $productSensor = ProductSensor::with('sensor')
            ->findOrFail($request->query('product_sensor_id'));

        

        $sensorName = $productSensor->sensor->name;
        $filename = strtolower($sensorName) . '_export_' . $inventoryItem->id . '_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($inventoryItem, $productSensor, $sensorName) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [$sensorName, 'Recorded At']);

            SensorReading::where('inventory_item_id', $inventoryItem->id)
                ->where('product_sensor_id', $productSensor->id)
                ->select('value', 'created_at')
                ->latest()
                ->chunk(500, function($chunk) use ($handle) {
                    foreach ($chunk as $reading) {
                        fputcsv($handle, [
                            $reading->value,
                            $reading->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                });
                
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);

    }
}
