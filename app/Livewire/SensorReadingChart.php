<?php

namespace App\Livewire;

use App\Models\SensorReading;
use Filament\Widgets\ChartWidget;
use Illuminate\Contracts\Support\Htmlable;

class SensorReadingChart extends ChartWidget
{
    public ?int $inventoryItemId = null;
    public ?int $productSensorId = null;
    public string $sensorName = '';
    public string $sensorUnit = '';
    public ?string $sensorNote = '';
    public int $minValue = 0;
    public int $maxValue = 0;

    protected int | string | array $columnSpan = 'full';
    protected ?string $maxHeight = '20vh';
    protected bool $isCollapsible = true;

    public function getHeading(): string
    {
        return "{$this->sensorName} chart ({$this->sensorUnit})";
    }

    public function getDescription(): ?string
    {
        $description = "Expected range: {$this->minValue} - {$this->maxValue}. \n {$this->sensorNote}";
        return $description;
    }

    protected function getData(): array
    {
        $data = SensorReading::where('inventory_item_id', $this->inventoryItemId)
            ->where('product_sensor_id', $this->productSensorId)
            ->select('value', 'created_at')
            ->latest()
            ->limit(20)  
            ->get();
        
        $data = $data->reverse();

        return [
            'datasets' => [
                [
                    'label' => $this->sensorName,
                    'data' => $data->pluck('value'),
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('created_at')->map(function ($date) {
                return $date->format("H:i d-m-'y");
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
