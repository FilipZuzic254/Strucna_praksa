<?php

namespace App\Filament\Resources\InventoryItems\Pages;

use App\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Livewire\AvailableItemDocuments;
use App\Livewire\ItemPressureChart;
use App\Livewire\ItemTemperatureChart;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action;

class ViewInventoryItem extends ViewRecord
{
    protected static string $resource = InventoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('exportSensors')
                ->label('Export Sensor Data')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->url(fn () => route('item.sensor.export', $this->record))
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
        return [
            AvailableItemDocuments::class,
            ItemTemperatureChart::class,
            ItemPressureChart::class,
        ];
    }
}
