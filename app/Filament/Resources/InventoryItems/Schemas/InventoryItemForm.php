<?php

namespace App\Filament\Resources\InventoryItems\Schemas;

use App\Filament\Resources\Products\ProductResource;
use App\Models\DeliveryItem;
use App\Models\Product;
use Filament\Actions\Action;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class InventoryItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('product_id')
                    ->relationship(name: 'product', titleAttribute: 'name')
                    ->searchable(['name', 'serial_number'])
                    ->preload()
                    ->required()
                    ->default(fn () => request()->get('product_id'))
                    ->afterContent([
                        Action::make('createProduct')
                            ->label('New')
                            ->url(fn () => ProductResource::getUrl('create'))
                            ->button()
                            ->visible(fn ($operation, Get $get) => $operation === 'create' || ($operation === 'edit' && $get('product_id') === null)),
                        Action::make('viewProduct')
                            ->label('View')
                            ->url(fn (Get $get) => ProductResource::getUrl('view', ['record' => $get('product_id')]))
                            ->button()
                            ->visible(fn (Get $get) => $get('product_id') !== null),
                    ])
                    ->disabled(fn () => auth()->user()->isTechnician()),
                TextInput::make('sku')
                    ->required()
                    ->disabled(fn () => auth()->user()->isTechnician()),
                Select::make('status')
                    ->required()
                    ->disabledOn('create')
                    ->default('in_stock')
                    ->options(function ($record) {

                        $isDelivered = DeliveryItem::where('inventory_item_id', $record?->id)->exists();

                        if ($isDelivered) {
                            $options = [
                                'delivered' => 'Delivered',
                                'replaced' => 'Replaced',
                                'faulty' => 'Faulty',
                            ];
                        }
                        else{
                            $options = [
                                'in_stock' => 'In Stock',
                                'faulty' => 'Faulty',
                            ];
                        }

                        return $options;

                    })
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
                DatePicker::make('purchased_at')
                    ->disabled(fn ($record) => ! DeliveryItem::where('inventory_item_id', $record?->id)->exists())
                    ->required(fn ($record) => DeliveryItem::where('inventory_item_id', $record?->id)->exists()),
                Grid::make()
                    ->schema([
                        DatePicker::make('installed_at')
                            ->required()
                            ->afterOrEqual('purchased_at')
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($state && !$get('warranty_expires_at')) {
                                    $product = Product::where('id', $get('product_id'))->first();

                                    if ($product && $product->warranty_months) {
                                        $set('warranty_expires_at', Carbon::parse($state)->addMonths($product->warranty_months));
                                    }
                                }
                            }),
                        DatePicker::make('warranty_expires_at')
                            ->afterOrEqual('installed_at')
                            ->hintAction(
                                Action::make('info')
                                    ->icon('heroicon-m-information-circle')
                                    ->tooltip('Value will be automatically calculated from product warranty length, starting from installation date if left empty')
                                    ->disabled(),
                            ),
                    ])
                    ->columns(2)
                    ->hidden(fn ($record) => !DeliveryItem::where('inventory_item_id', $record?->id)->exists()),
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
