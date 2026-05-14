<?php

namespace App\Filament\Resources\KpdCodes\Pages;

use App\Filament\Resources\KpdCodes\KpdCodesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKpdCodes extends ListRecords
{
    protected static string $resource = KpdCodesResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
