<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required(),
                TextInput::make('sku')->required(),
                TextInput::make('manufacturer')->required(),
                Select::make('warranty_months')
                    ->options([
                        6 => '6 months',
                        12 => '1 year',
                        24 => '2 years',
                        36 => '3 years',
                        48 => '4 years',
                        60 => '5 years',
                    ])
                    ->required(),
            ]);
    }
}
