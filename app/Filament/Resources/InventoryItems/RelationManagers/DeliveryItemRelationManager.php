<?php

namespace App\Filament\Resources\InventoryItems\RelationManagers;

use Filament\Actions\ViewAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Clients\ClientResource;

class DeliveryItemRelationManager extends RelationManager
{
    protected static string $relationship = 'deliveryItem';

    protected static ?string $title = 'Client';

    public function table(Table $table): Table
    {
        return $table
            ->heading(fn () => "Client: {$this->getOwnerRecord()->deliveryItem?->delivery?->client?->name}")
            ->columns([
                TextColumn::make('delivery.client.name'),
                TextColumn::make('delivery.client.type')
                    ->badge()
                    ->label('Client Type')
                    ->color(fn ($state) => match ($state) {
                        'person' => 'info',
                        'company' => 'warning',
                    }),
                TextColumn::make('delivery.client.oib')
                    ->label('OIB'),
                TextColumn::make('delivery.client.email')
                    ->label('Email address'),
                TextColumn::make('delivery.client.phone')
                    ->label('Phone'),
                TextColumn::make('delivery.client.address')
                    ->label('Address'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => ClientResource::getUrl('view', ['record' => $record->delivery->client_id])),
            ])
            ->emptyStateHeading('No clients yet')
            ->paginated(false);
    }
}
