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
                        12 => '1 year',
                        24 => '2 years',
                        36 => '3 years',
                        48 => '4 years',
                        60 => '5 years',
                    ])
                    ->default(24)
                    ->required(),
                Textarea::make('description')
                    ->columnSpanFull(),
                
            ]);
    }
}
