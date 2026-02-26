<?php

namespace App\Filament\Resources\InventoryItems\Schemas;

use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Schema;

class InventoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->searchable(['name', 'sku'])
                    ->preload()
                    ->required()
                    ->default(fn () => request()->get('product_id')),
                TextInput::make('serial_number')
                    ->required(),
                Select::make('status')
                    ->required()
                    ->default('in_stock')
                    ->options([
                        'in_stock' => 'In Stock',
                        'delivered' => 'Delivered',
                        'faulty' => 'Faulty',
                        'replaced' => 'Replaced',
                    ])
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'faulty') {
                            $set('serviceLogs', [[
                                'serviced_at' => now(),
                                'action' => 'Faulty item',
                                'description' => '',
                            ]]);
                        }

                        if ($state === 'replaced') {
                            $set('serviceLogs', [[
                                'serviced_at' => now(),
                                'action' => 'Replaced with new item',
                                'description' => '',
                            ]]);
                        }
                    }),
                Grid::make()
                    ->schema([
                        DatePicker::make('purchased_at')
                            ->required(),
                        DatePicker::make('warranty_expires_at')
                            ->afterOrEqual('purchased_at')
                            ->hintAction(
                                Action::make('info')
                                    ->icon('heroicon-m-information-circle')
                                    ->tooltip('Value will be automatically calculated from product warranty length if left empty')
                                    ->disabled(),
                            ),
                    ])
                    ->columns(2)
                    ->hidden(fn (Get $get): bool => $get('status') === 'in_stock'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                
                Repeater::make('serviceLogs')
                    ->relationship()
                    ->minItems(1)
                    ->schema([
                        DatePicker::make('serviced_at')
                            ->required(),
                        TextInput::make('action')
                            ->required(),
                        Textarea::make('description')
                            ->required(),
                    ])
                    ->itemLabel(fn (array $state): ?string => $state['serviced_at'] ?? null)
                    ->columns(2)
                    ->columnSpanFull()
                    ->addActionLabel('Add service log')
                    ->hidden(fn (Get $get): bool => $get('status') === 'in_stock' || $get('status') === 'delivered')
                    ->visible(fn ($operation) => $operation === 'create'),
            ]);
    }
}
