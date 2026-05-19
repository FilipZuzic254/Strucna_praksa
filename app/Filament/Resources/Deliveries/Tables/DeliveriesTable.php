<?php

namespace App\Filament\Resources\Deliveries\Tables;

use BcMath\Number;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DeliveriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('client.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('delivered_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items')
                    ->sortable(),
                TextColumn::make('note')
                    ->limit(40),
            ])
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
                ]),
            ]);
    }
}
