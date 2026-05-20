<?php

namespace App\Filament\Resources\InventoryItems;

use App\Filament\Resources\InventoryItems\Pages\CreateInventoryItem;
use App\Filament\Resources\InventoryItems\Pages\EditInventoryItem;
use App\Filament\Resources\InventoryItems\Pages\ListInventoryItems;
use App\Filament\Resources\InventoryItems\Pages\ViewInventoryItem;
use App\Filament\Resources\InventoryItems\Schemas\InventoryItemForm;
use App\Filament\Resources\InventoryItems\Tables\InventoryItemsTable;
use App\Models\InventoryItem;
use App\Filament\Resources\InventoryItems\RelationManagers\ServiceLogsRelationManager;
use App\Filament\Resources\InventoryItems\RelationManagers\DeliveryItemRelationManager;
use App\Filament\Resources\InventoryItems\RelationManagers\ItemComponentsRelationManager;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InventoryItemResource extends Resource
{
    protected static ?string $model = InventoryItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquaresPlus;
    
    protected static ?string $pluralModelLabel  = 'Stock';

    protected static ?string $recordTitleAttribute = 'serial_number';

    public static function form(Schema $schema): Schema
    {
        return InventoryItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InventoryItemsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DeliveryItemRelationManager::class,
            ItemComponentsRelationManager::class,
            ServiceLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInventoryItems::route('/'),
            'create' => CreateInventoryItem::route('/create'),
            'view' => ViewInventoryItem::route('/{record}'),
            'edit' => EditInventoryItem::route('/{record}/edit'),
        ];
    }

    
}
