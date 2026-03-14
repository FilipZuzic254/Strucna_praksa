<?php

namespace App\Filament\Resources\InventoryItems\Pages;

use App\Filament\Resources\InventoryItems\InventoryItemResource;
use App\Livewire\ItemPressureChart;
use App\Livewire\ItemTemperatureChart;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewInventoryItem extends ViewRecord
{
    protected static string $resource = InventoryItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
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
            ItemTemperatureChart::class,
            ItemPressureChart::class,
        ];
    }
}
