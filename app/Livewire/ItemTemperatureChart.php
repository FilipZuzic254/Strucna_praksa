<?php

namespace App\Livewire;

use Filament\Widgets\ChartWidget;
use App\Models\TemperatureReading;

class ItemTemperatureChart extends ChartWidget
{
    protected ?string $heading = 'Item Temperature Chart';

    protected bool $isCollapsible = true;

    public $id=null;

    protected function getData(): array
    {
        $data = TemperatureReading::where('inventory_item_id', $this->id)
            ->select('temperature', 'created_at')
            ->latest()
            ->limit(20)  
            ->get();


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
