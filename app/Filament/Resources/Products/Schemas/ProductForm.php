<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use App\Models\KpdCode;
use Filament\Schemas\Components\Utilities\Get;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),

                TextInput::make('sku')
                    ->required()
                    ->unique(ignoreRecord: true),

                Select::make('kpd_code_id')
                    ->label('KPD Code')
                    ->relationship('kpdCode', 'id')
                    ->searchable(['code', 'name'])
                    ->getOptionLabelFromRecordUsing(fn (KpdCode $record) => "{$record->code} - {$record->name}")
                    ->preload()
                    ->nullable(),

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

                TextInput::make('unit_price')
                    ->prefix('€')
                    ->numeric()
                    ->required(),

                Select::make('unit_of_measure')
                    ->options([
                        'piece' => 'Piece',
                        'kg' => 'Kilogram',
                        'l' => 'Liter',
                        'month' => 'Month',
                        'day' => 'Day',
                        'hour' => 'Hour',
                    ])
                    ->required(),

                Select::make('tax_rate')
                    ->options([
                        '0' => '0%',
                        '5' => '5%',
                        '13' => '13%',
                        '25' => '25%',
                    ])
                    ->live()
                    ->required(),

                Select::make('tax_exemption_id')
                    ->label('Tax Exemption')
                    ->relationship('taxExemption', 'id')
                    ->searchable(['code', 'description'])
                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->description}")
                    ->preload()
                    ->visible(fn (Get $get) => $get('tax_rate') === '0%')
                    ->required(fn (Get $get) => $get('tax_rate') === '0%'),

                TextInput::make('discount')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100),
                
                Textarea::make('description')
                    ->maxLength(1000)
                    ->autosize(),
                
            ]);
    }
}
