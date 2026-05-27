<?php

namespace App\Filament\Resources\Clients\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use app\Filament\Resources\Clients\Pages\ViewClient;

class ClientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'person' => 'info',
                        'company' => 'warning',
                    })
                    ->searchable(),
                TextColumn::make('oib')
                    ->label('OIB')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('phone')
                    ->searchable(),
                TextColumn::make('address')
                    ->searchable()
                    ->wrap(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'person' => 'Person',
                        'company' => 'Company',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make(
                    auth()->user()->isAdmin()    
                    ?[DeleteBulkAction::make()]
                    :[]
                ),
            ])
            ->striped();
            
    }
}
