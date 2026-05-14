<?php

namespace App\Filament\Resources\Sensors\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SensorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('Unit')
                    ->required()
                    ->options([
                        '°C', 
                        'bar', 
                        'hPa', 
                        'L/min', 
                        'ppm', 
                        'mg/L',
                    ]),
            ]);
    }
}
