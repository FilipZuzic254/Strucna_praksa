<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use App\Models\InventoryItem;

class SoonExpiring extends TableWidget
{
    protected static ?string $heading = 'Soon Expiring Warranties';
    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => InventoryItem::query()
                    ->whereNotNull('warranty_expires_at')
                    ->where('warranty_expires_at', '>=', now())
                    ->orderBy('warranty_expires_at', 'asc')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('serial_number'),
                TextColumn::make('product.name'),
                TextColumn::make('warranty_expires_at')
                    ->date(),
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

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
