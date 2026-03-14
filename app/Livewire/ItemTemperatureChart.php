<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\SensorReading;

class ItemTemperatureChart extends ChartWidget
{
    protected ?string $heading = 'Item Temperature Chart';

    protected bool $isCollapsible = true;

    public $id=null;

    protected function getData(): array
    {
        $data = SensorReading::where('inventory_item_id', $this->id)->get();


        return [
            'datasets' => [
                [
                    'label' => 'Temperature',
                    'data' => $data->pluck('temperature'),
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $data->pluck('created_at')->map(function ($date) {
                return $date->format('H:i');
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
