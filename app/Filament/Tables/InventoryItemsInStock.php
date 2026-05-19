<?php

namespace App\Filament\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use App\Models\InventoryItem;

class InventoryItemsInStock
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => InventoryItem::query()->where('status', 'in_stock'))
            ->columns([
                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('notes')
                    ->limit(40)
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
