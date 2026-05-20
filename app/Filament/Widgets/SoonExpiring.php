<?php

namespace App\Filament\Widgets;

use Filament\Actions\BulkActionGroup;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\InventoryItems\InventoryItemResource;

class SoonExpiring extends TableWidget
{
    protected static ?string $heading = 'Soon Expiring Warranties';
    
    public function table(Table $table): Table
    {
        return $table
            ->extraAttributes(['class' => 'soon-expiring-table'])
            ->query(
                fn (): Builder => InventoryItem::query()
                    ->whereNotNull('warranty_expires_at')
                    ->where('warranty_expires_at', '>=', now())
                    ->orderBy('warranty_expires_at', 'asc')
            )
            ->columns([
                TextColumn::make('serial_number')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable()
                    ->limit(40),
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
            ->recordUrl(fn (Model $record): string => InventoryItemResource::getUrl('view', ['record' => $record]))
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}
