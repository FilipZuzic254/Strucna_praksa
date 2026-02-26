<?php

namespace App\Filament\Widgets;

use App\Models\InventoryItem;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ItemsPerStatus extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Items in stock', InventoryItem::where('status', 'in_stock')->count())
                ->description('Items with in_stock status')
                ->color('success'),
            Stat::make('Items delivered', InventoryItem::where('status', 'delivered')->count())
                ->description('Items with delivered status')
                ->color('info'),
            Stat::make('Items replaced', InventoryItem::where('status', 'replaced')->count())
                ->description('Items with replaced status')
                ->color('warning'),
            Stat::make('Items faulty', InventoryItem::where('status', 'faulty')->count())
                ->description('Items with faulty status')
                ->color('danger'),
        ];
    }
}
