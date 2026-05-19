<?php

namespace App\Filament\Resources\TaxExemptions\Schemas;

use Filament\Schemas\Schema;
use Filament\Infolists\Components\TextEntry;

class TaxExemptionsInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('description')->columnSpanFull(),
            ]);
    }
}
