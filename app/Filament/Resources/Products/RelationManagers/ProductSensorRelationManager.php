<?php

namespace App\Filament\Resources\Products\RelationManagers;

use App\Filament\Resources\Sensors\SensorResource;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use App\Models\Sensor;
use Filament\Actions\ViewAction;

class ProductSensorRelationManager extends RelationManager
{
    protected static string $relationship = 'productSensors';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ])
            ->columns([
                TextColumn::make('sensor.name')
                    ->label('Sensor Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('min_value')
                    ->label('Min Value')
                    ->sortable(),
                TextColumn::make('max_value')
                    ->label('Max Value')
                    ->sortable(),
                TextColumn::make('note')
                    ->label('Note'),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => SensorResource::getUrl('view', ['record' => $record->sensor_id])),
                DeleteAction::make(),
                EditAction::make()
            ]);
            
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('sensor_id')
                    ->label('Sensor')
                    ->options(
                        Sensor::all()->pluck('name', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),
                Grid::make()
                    ->schema([
                        TextInput::make('min_value')
                            ->label('Min Value')
                            ->numeric()
                            ->required(),
                        TextInput::make('max_value')
                            ->label('Max Value')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
                TextInput::make('note')
                    ->label('Note')
                    ->columnSpanFull(),
            ]);
    }
}
