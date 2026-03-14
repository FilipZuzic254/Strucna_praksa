<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\SensorReading;

class ItemPressureChart extends ChartWidget
{
    protected ?string $heading = 'Item Pressure Chart';

    protected bool $isCollapsible = true;

    public $id=null;

    protected function getData(): array
    {
        $data = SensorReading::where('inventory_item_id', $this->id)->get();


        return [
            'datasets' => [
                [
                    'label' => 'Pressure',
                    'data' => $data->pluck('pressure'),
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
