<?php

namespace App\Filament\Resources\KpdCodes\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class KpdCodesInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('name')->columnSpanFull(),
            ]);
    }
}
