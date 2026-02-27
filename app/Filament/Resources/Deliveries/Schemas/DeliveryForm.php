<?php

namespace App\Filament\Resources\Deliveries\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                    ->required(),
                DatePicker::make('delivered_at')
                    ->required(),
                TextInput::make('reference'),
                Textarea::make('note')
                    ->columnSpanFull(),
            ]);
    }
}
