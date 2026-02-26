<?php

namespace App\Filament\Resources\Deliveries\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('inventory_item_id')
                    ->label('Inventory Item')
                    ->relationship(
                        name: 'inventoryItem',
                        titleAttribute: 'serial_number',
                        ignoreRecord: true,
                        modifyQueryUsing: function (Builder $query) {
                            $query->where('status', 'in_stock');
                        }
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) =>
                        $record->product->name . ' - ' . $record->serial_number
                    )
                    ->searchable('serial_number', 'product.name')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Item')
            ->columns([
                TextColumn::make('inventoryItem.product.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('inventoryItem.serial_number')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()
                    ->after(function ($record) {
                        $record->inventoryItem->update(['status' => 'delivered', 'purchased_at' => now()]);
                    }),
            ])
            ->recordActions([
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
