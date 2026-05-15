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
                Select::make('unit')
                    ->required()
                    ->options([
                        '°C' => '°C', 
                        'bar' => 'bar', 
                        'hPa' => 'hPa', 
                        'L/min' => 'L/min', 
                        'ppm' => 'ppm', 
                        'mg/L' => 'mg/L',
                    ]),
            ]);
    }
}
