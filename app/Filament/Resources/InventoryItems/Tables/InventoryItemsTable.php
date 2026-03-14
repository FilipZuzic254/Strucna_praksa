<?php

namespace App\Filament\Resources\InventoryItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;

class InventoryItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'in_stock' => 'success',
                        'delivered' => 'info',
                        'faulty' => 'danger',
                        'replaced' => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('purchased_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('warranty_expires_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('notes')
                    ->limit(40)
                    
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'in_stock' => 'In Stock',
                        'delivered' => 'Delivered',
                        'faulty' => 'Faulty',
                        'replaced' => 'Replaced',
                    ])
                    ->multiple(),
                Filter::make('warranty_expires_at')
                    ->schema([
                        Select::make('days')
                            ->options([
                                30 => 'Expiring in 30 days',
                                60 => 'Expiring in 60 days',
                            ])
                            ->placeholder('Select warranty expiration'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['days'] ?? null, 
                                function (Builder $query, $days) {
                                    $query
                                        ->where('warranty_expires_at', '<=', now()->addDays(intval($days)))
                                        ->where('warranty_expires_at', '>=', now());
                                }
                            );
                    })
                    ->indicateUsing(function (array $data) {
                        if (isset($data['days'])) {
                            return 'Warranty expiring in ' . $data['days'] . ' days';
                        }
                        return null;
                    }),
                
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
