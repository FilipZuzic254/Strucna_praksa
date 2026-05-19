<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Product;
use Filament\Tables\Columns\TextColumn;

class ProductCount extends TableWidget
{
    protected static ?string $heading = 'Product Item Counts';
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Product::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('in_stock_items_count')
                    ->counts('inStockItems')
                    ->label('In Stock')
                    ->sortable(),
                TextColumn::make('delivered_items_count')
                    ->counts('deliveredItems')
                    ->label('Delivered')
                    ->sortable(),
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
