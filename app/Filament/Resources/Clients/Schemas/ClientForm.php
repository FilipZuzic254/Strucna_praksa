<?php

namespace App\Filament\Resources\Clients\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('type')
                    ->options([
                        'person' => 'Person',
                        'company' => 'Company',
                    ])
                    ->required(),
                TextInput::make('oib')
                    ->label('OIB')
                    ->requiredIf('type', 'company')
                    ->unique(ignoreRecord: true),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('address'),

                Repeater::make('contacts')
                    ->relationship()
                    ->minItems(1)
                    ->required()
                    ->schema([
                        TextInput::make('first_name')
                            ->required(),
                        TextInput::make('last_name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required(),
                        TextInput::make('position'),
                        TextInput::make('phone')
                            ->tel(),
                    ])
                    ->itemLabel(fn (array $state): ?string => $state['first_name'] . ' ' . $state['last_name'] ?? null)
                    ->columns(2)
                    ->columnSpanFull()
                    ->addActionLabel('Add contact')
                    ->visible(fn ($operation) => $operation === 'create'),
            ]);
    }
}
