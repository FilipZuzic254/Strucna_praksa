<?php

namespace App\Filament\Resources\Sensors\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Products\ProductResource;

class ProductSensorsRelationManager extends RelationManager
{
    protected static string $relationship = 'productSensors';


    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('min_value')
                    ->searchable(),
                TextColumn::make('max_value')
                    ->searchable(),
                TextColumn::make('note')
                    ->wrap(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('info')
                    ->icon('heroicon-o-information-circle')
                    ->iconButton()
                    ->tooltip('To edit product-sensor relationships, please edit the product itself.')
                    ->color('warning'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => 
                        ProductResource::getUrl(
                            'edit',
                            ['record' => $record->product_id]
                        )
                    )
            ])
            ->toolbarActions([
            ]);
    }
}
