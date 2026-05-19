<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\BulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Collection;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kpdCode.code')
                    ->label('KPD Code')
                    ->description(fn (Product $record): string => $record->kpdCode->name ?? '')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('unit_price')
                    ->money('EUR')
                    ->sortable(),
                TextColumn::make('unit_of_measure')
                    ->sortable(),
                TextColumn::make('tax_rate')
                    ->sortable(),
                TextColumn::make('taxExemption.code')
                    ->label('Tax Exemption Code')
                    ->description(fn (Product $record): string => $record->taxExemption->description ?? '')
                    ->sortable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('warranty_months')
                    ->sortable(),
                TextColumn::make('in_stock_items_count')
                    ->counts('inStockItems')
                    ->label('In Stock')
                    ->sortable(),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    BulkAction::make('exportSensors')
                        ->label('Export Sensor Data')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (Collection $records) {
                            $ids = $records->pluck('id')->join(',');
                            return redirect()->away(route('products.sensors.export', ['product_ids' => $ids]));
                        }),
                ]),
            ])
            ->striped();
    }
}
