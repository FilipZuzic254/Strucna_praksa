<?php

namespace App\Filament\Resources\InventoryItems\RelationManagers;

use App\Models\ComponentItem;
use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class ItemComponentsRelationManager extends RelationManager
{
    protected static string $relationship = 'itemComponents';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('serial_number')
            ->columns([
                TextColumn::make('component.name')
                    ->searchable(),
                TextColumn::make('serial_number')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'in_stock' => 'success',
                        'installed' => 'info',
                        'faulty' => 'danger',
                    })
                    ->sortable(),
                TextColumn::make('installed_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('warranty_expires_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'installed' => 'Installed',
                        'faulty' => 'Faulty',
                    ])
                    ->multiple(),
            ])
            ->headerActions([
                AssociateAction::make()
                    ->label('Add component')
                    ->modalHeading('Add component to item')
                    ->modalSubmitActionLabel('Add')
                    ->extraModalFooterActions(fn (AssociateAction $action): array => [
                        $action->makeModalSubmitAction('associateAnother', arguments: ['another' => true])
                            ->label('Save and add another'),
                    ])
                    ->recordSelect(fn (Select $select) => $select
                        ->searchable()
                        ->getSearchResultsUsing(function (string $search) {
                            $componentIds = $this->getOwnerRecord()->product->components->pluck('id');

                            return ComponentItem::where('status', 'in_stock')
                                ->whereIn('component_id', $componentIds)
                                ->where(function ($query) use ($search) {
                                    $query->where('serial_number', 'like', "%{$search}%")
                                        ->orWhereHas('component', fn ($q) => 
                                            $q->where('name', 'like', "%{$search}%")
                                        );
                                })
                                ->with('component')
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn ($item) => [
                                    $item->id => "{$item->component->name} - {$item->serial_number}"
                                ]);
                        })
                    )
                    ->after(function (AssociateAction $action) {
                        $componentItem = $action->getRecord();

                        if ($componentItem) {
                            $componentItem->update([
                                'status' => 'installed',
                                'installed_at' => now(),
                            ]);
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                ]),
            ]);
    }
}
