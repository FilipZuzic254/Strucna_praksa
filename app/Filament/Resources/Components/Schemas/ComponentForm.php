<?php

namespace App\Filament\Resources\Components\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ComponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('warranty_months')
                    ->options([
                        6 => '6 months',
                        12 => '12 months',
                        24 => '24 months',
                        36 => '36 months',
                        48 => '48 months',
                        60 => '60 months',
                    ])
                    ->default(24)
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                
            ]);
    }
}
