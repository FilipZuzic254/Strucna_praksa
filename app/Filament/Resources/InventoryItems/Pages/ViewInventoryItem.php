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
            Action::make('exportTemperature')
                ->label('Export Temperature Data')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->url(fn () => route('item.sensors.export', [$this->record, 'type' => 'temperature'])),
            Action::make('exportPressure')
                ->label('Export Pressure Data')
                ->icon('heroicon-o-arrow-down-tray')
                ->color('info')
                ->url(fn () => route('item.sensors.export', [$this->record, 'type' => 'pressure'])),
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
