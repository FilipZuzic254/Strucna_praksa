<?php

namespace App\Filament\Resources\TaxExemptions;

use App\Filament\Resources\TaxExemptions\Pages\CreateTaxExemptions;
use App\Filament\Resources\TaxExemptions\Pages\EditTaxExemptions;
use App\Filament\Resources\TaxExemptions\Pages\ListTaxExemptions;
use App\Filament\Resources\TaxExemptions\Pages\ViewTaxExemptions;
use App\Filament\Resources\TaxExemptions\Schemas\TaxExemptionsForm;
use App\Filament\Resources\TaxExemptions\Schemas\TaxExemptionsInfolist;
use App\Filament\Resources\TaxExemptions\Tables\TaxExemptionsTable;
use App\Filament\Resources\TaxExemptions\RelationManagers\ProductsRelationManager;
use App\Models\TaxExemption;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaxExemptionsResource extends Resource
{
    protected static ?string $model = TaxExemption::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $modelLabel  = 'Tax Exemption Codes';

    public static function infolist(Schema $schema): Schema
    {
        return TaxExemptionsInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxExemptionsTable::configure($table);
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
            'index' => ListTaxExemptions::route('/'),
            'view' => ViewTaxExemptions::route('/{record}'),
        ];
    }
}
