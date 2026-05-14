<?php

namespace App\Filament\Resources\InventoryItems\Pages;

use App\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Livewire\AvailableItemDocuments;
use App\Livewire\SensorReadingChart;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;

class ViewInventoryItem extends ViewRecord
{
    protected static string $resource = InventoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(), 
            ActionGroup::make($this->generateSensorExportActions())
                ->label('Export Sensor Data')
                ->color('success')
                ->icon('heroicon-o-chevron-down')
                ->button(),
            ];
    }

    public function getWidgetData(): array
    {
        return [
            'id' => $this->record->id,
        ];
    }

    protected function getFooterWidgets(): array
    {
        $widgets = $this->generateSensorCharts();

        array_unshift($widgets, AvailableItemDocuments::make());

        return $widgets;
    }

    private function generateSensorCharts(): array
    {
        $charts = [];

        $productSensors = $this->record->product->productSensors()->with('sensor')->get();

        foreach ($productSensors as $productSensor) {
             $charts[] = SensorReadingChart::make([
                'inventoryItemId' => $this->record->id,
                'productSensorId' => $productSensor->id,
                'sensorName' => $productSensor->sensor->name,
                'sensorUnit' => $productSensor->sensor->unit,
                'sensorNote' => $productSensor->note ?? null,
            ]);
        }

        return $charts;
    }

    private function generateSensorExportActions(): array
    {
        $actions = [];

        $productSensors = $this->record->product->productSensors()->with('sensor')->get();

        foreach ($productSensors as $productSensor) {
            $actions[] = Action::make("export_sensor_{$productSensor->id}")
                ->label("Export {$productSensor->sensor->name} Readings")
                ->color('amber')
                ->icon('heroicon-o-arrow-down-tray')
                ->openUrlInNewTab()
                ->url(fn() => route('item.sensors.export', [
                    $this->record,
                    'product_sensor_id' => $productSensor->id,
                ]));
        }

        return $actions;
    }
}
