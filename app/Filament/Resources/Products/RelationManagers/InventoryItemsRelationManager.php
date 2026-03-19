<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\InventoryItems\InventoryItemResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class InventoryItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'inventoryItems';

    protected static ?string $relatedResource = InventoryItemResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'in_stock' => 'success',
                        'delivered' => 'info',
                        'faulty' => 'danger',
                        'replaced' => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('purchased_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('installed_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('warranty_expires_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('notes')
                    ->limit(40)
                    
            ])
            ->headerActions([
                CreateAction::make()
                ->url(fn () =>
                    InventoryItemResource::getUrl(
                        'create',
                        ['product_id' => $this->ownerRecord->getKey()]
                    )
                )
            ]);
    }
}
