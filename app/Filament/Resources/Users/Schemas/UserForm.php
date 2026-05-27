<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                    ->placeholder('Leave empty to keep current password')
                    ->required(fn ($operation) => $operation === 'create'),
                Select::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'shop_manager' => 'Shop Manager',
                        'technician' => 'Technician',
                    ])
                    ->required(),
            ]);
    }
}
