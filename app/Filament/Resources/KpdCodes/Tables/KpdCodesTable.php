<?php

namespace App\Filament\Resources\KpdCodes\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class KpdCodesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Products')
                    ->sortable(),
            ])
            ->defaultSort(function (Builder $query): Builder{
                return $query
                    ->orderBy('products_count', 'desc')
                    ->orderBy('code', 'asc');
            })
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ])
            ->toolbarActions([
            ]);
    }
}
