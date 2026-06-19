<?php

namespace App\Filament\Resources\InventoryItems\Pages;

use App\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Livewire\GalleryWidget;
use App\Livewire\ItemDocuments;
use App\Livewire\SensorReadingChart;
use App\Models\SensorReading;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\ActionGroup;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Log;

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
                ->button()
                ->hidden(fn () => auth()->user()->isShopManager()),
            ActionGroup::make($this->addSensorValueActions())
                ->label('Add Sensor Value')
                ->color('primary')
                ->icon('heroicon-o-chevron-down')
                ->button()
                ->hidden(fn () => auth()->user()->isShopManager()),
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

        array_unshift($widgets, ItemDocuments::make([
            'product_id' => $this->record->product_id,
            'inventory_item_id' => $this->record->id,
        ]));

        array_unshift($widgets, GalleryWidget::make([
            'productId' => $this->record->id,
        ]));

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
                'minValue' => $productSensor->min_value,
                'maxValue' => $productSensor->max_value,
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
                ->color('black')
                ->icon('heroicon-o-arrow-down-tray')
                ->openUrlInNewTab()
                ->url(fn() => route('item.sensors.export', [
                    $this->record,
                    'product_sensor_id' => $productSensor->id,
                ]));
        }

        return $actions;
    }

    private function addSensorValueActions(): array
    {
        $actions = [];

        $productSensors = $this->record->product
            ->productSensors()
            ->with('sensor')
            ->whereHas('sensor', fn($query) => $query->where('unit', 'L/min'))
            ->get();


        foreach ($productSensors as $productSensor) {
            $actions[] = Action::make("add_sensor_value_{$productSensor->id}")
                ->label("Add {$productSensor->sensor->name} Value")
                ->color('black')
                ->icon('heroicon-o-plus')
                ->form([
                    TextInput::make('value')
                        ->label($productSensor->sensor->name . ' (' . $productSensor->sensor->unit . ') value')
                        ->numeric()
                        ->required(),
                ])
                ->action(function (array $data) use ($productSensor) {
                    SensorReading::create([
                        'inventory_item_id' => $this->record->id,
                        'product_sensor_id' => $productSensor->id,
                        'value' => $data['value'],
                    ]);
                });
        }

        return $actions;
    }
}
