<?php

namespace App\Filament\Resources\Clients\RelationManagers;

use App\Filament\Resources\Deliveries\DeliveryResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class DeliveriesRelationManager extends RelationManager
{
    protected static string $relationship = 'deliveries';

    protected static ?string $relatedResource = DeliveryResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->searchable(),
                TextColumn::make('delivered_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items')
                    ->sortable(),
                TextColumn::make('note')
                    ->limit(40),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => DeliveryResource::getUrl('view', ['record' => $record->id])),
            ])
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
