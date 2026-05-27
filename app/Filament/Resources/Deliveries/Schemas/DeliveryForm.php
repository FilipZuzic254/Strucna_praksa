<?php

namespace App\Filament\Resources\Deliveries\Schemas;

use App\Filament\Resources\Clients\ClientResource;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class DeliveryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('client_id')
                    ->label('Client')
                    ->relationship('client', 'name')
                    ->searchable(['name', 'email', 'oib'])
                    ->preload()
                    ->required()
                    ->afterContent([
                        Action::make('createClient')
                            ->label('New')
                            ->url(fn () => ClientResource::getUrl('create'))
                            ->button()
                            ->visible(fn ($operation) => $operation === 'create'),
                        Action::make('viewClient')
                            ->label('View')
                            ->url(fn (Get $get) => ClientResource::getUrl('view', ['record' => $get('client_id')]))
                            ->button()
                            ->visible(fn (Get $get, $operation) => $get('client_id') !== null && $operation !== 'create'),
                ]),
                DatePicker::make('delivered_at')
                    ->required(),
                TextInput::make('reference'),
                Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }
}
