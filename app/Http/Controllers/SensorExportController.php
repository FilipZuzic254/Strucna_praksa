<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\TemperatureReading;
use App\Models\PressureReading;
use Illuminate\Http\Request;

class SensorExportController extends Controller
{
    public function export(InventoryItem $inventoryItem, Request $request)
    {

        $type = $request->query('type');

        if ($type === "temperature") {
            $data = TemperatureReading::where('inventory_item_id', $inventoryItem->id)
            ->select('temperature', 'is_faulty', 'created_at')
            ->latest()
            ->get();
        }
        else if ($type === "pressure") {
            $data = PressureReading::where('inventory_item_id', $inventoryItem->id)
                ->select('pressure', 'is_faulty', 'created_at')
                ->latest()
                ->get();
        }
        
        $filename = $type . '_export_' . $inventoryItem->id . '_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];

        $callback = function() use ($data, $type) {
            
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [$type === 'temperature' ? 'Temperature' : 'Pressure', 'Is Faulty', 'Recorded At']);
            foreach ($data as $reading) {
                fputcsv($handle, [
                    $reading->temperature ?? $reading->pressure,
                    $reading->is_faulty ? 'Yes' : 'No',
                    $reading->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
