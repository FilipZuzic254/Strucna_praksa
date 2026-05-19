<?php

namespace App\Filament\Resources\KpdCodes;

use App\Filament\Resources\KpdCodes\Pages\ListKpdCodes;
use App\Filament\Resources\KpdCodes\Pages\ViewKpdCodes;
use App\Filament\Resources\KpdCodes\RelationManagers\ProductsRelationManager;
use App\Filament\Resources\KpdCodes\Schemas\KpdCodesInfolist;
use App\Filament\Resources\KpdCodes\Tables\KpdCodesTable;
use App\Models\KpdCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KpdCodesResource extends Resource
{
    protected static ?string $model = KpdCode::class;

    protected static ?string $modelLabel = 'KPD Codes';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    public static function infolist(Schema $schema): Schema
    {
        return KpdCodesInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KpdCodesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ProductsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKpdCodes::route('/'),
            'view' => ViewKpdCodes::route('/{record}'),
        ];
    }
}
